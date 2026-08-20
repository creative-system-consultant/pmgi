<?php

namespace App\Services\Prestasi;

use App\Models\RefEvalPctg;
use App\Models\SummMthOfficer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Builds the rows rendered by the "Perincian Prestasi" (keseluruhan) monthly report.
 *
 * pmgi_summ_mth_officer also stores pre-aggregated total rows (incl_pmgi_flag N / S / W, all
 * with a null officer_id), but those are ignored here: they still count the placeholder pegawai
 * this report leaves out. The totals are recalculated instead, following the same rules the
 * stored rows were built on - sum every pegawai row in the scope except the cawangan-wide B
 * rows, derive each percentage from the summed columns, and mark a kriteria "capai" when it
 * reaches the passing mark in pmgi_ref_eval_pctg.
 *
 * How far the filter is narrowed decides which total closes the table:
 *
 *   negeri + cawangan       -> the total of that cawangan
 *   negeri + semua cawangan -> the total of that negeri, cawangan sub-totals in the body
 *   semua negeri            -> the overall total, negeri and cawangan sub-totals in the body
 *
 * A pegawai filter narrows the listing only - the totals keep describing the whole cawangan
 * or negeri that was selected.
 */
class PrestasiBulananKeseluruhanService
{
    public const ALL_STATES = '%';
    public const ALL_BRANCHES = '%%';

    public const BRANCH_TOTAL = 'N';
    public const STATE_TOTAL = 'S';
    public const OVERALL_TOTAL = 'W';

    private const SCOPE_BRANCH = 'branch';
    private const SCOPE_STATE = 'state';
    private const SCOPE_OVERALL = 'overall';

    /**
     * A cawangan's pengurus is measured on the whole cawangan, so their row already carries
     * every account of that cawangan. Adding it to the sum would count each account twice.
     */
    private const BRANCH_MANAGER = 'B';

    /** Placeholder pegawai that stand in for unassigned accounts - never listed, never counted. */
    private const EXCLUDED_OFFICER_IDS = ['XXXXXXXX', 'ZZZZZZZZ'];

    /** Columns that a total is the plain sum of. */
    private const SUM_COLUMNS = [
        'rm_patut_kutip',
        'rm_dapat_kutip',
        'bil_patut_kutip',
        'bil_dapat_kutip',
        'bil_selia',
        'bil_lawat',
        'bil_kawal_npf_sblm',
        'bil_kawal_npf_tukar',
        'bil_kawal_npf_kekal',
        'bil_pulih_npf_sblm',
        'bil_pulih_npf_tukar',
    ];

    /**
     * The five kriteria: [percentage column, numerator, denominator, evaluation id, capai flag].
     * The evaluation id points at the passing mark held in pmgi_ref_eval_pctg.
     */
    private const CRITERIA = [
        ['rm_dapat_kutip_pts', 'rm_dapat_kutip', 'rm_patut_kutip', 1, 'rm_dapat_kutip_capai_flag'],
        ['bil_dapat_kutip_pts', 'bil_dapat_kutip', 'bil_patut_kutip', 2, 'bil_dapat_kutip_capai_flag'],
        ['bil_lawat_pts', 'bil_lawat', 'bil_selia', 3, 'bil_lawat_capai_flag'],
        ['bil_kawal_npf_pts', 'bil_kawal_npf_kekal', 'bil_kawal_npf_sblm', 4, 'bil_kawal_npf_capai_flag'],
        ['bil_pulih_npf_pts', 'bil_pulih_npf_tukar', 'bil_pulih_npf_sblm', 5, 'bil_pulih_npf_capai_flag'],
    ];

    /** Separator that sorts before any printable character, keeping the key segments distinct. */
    private const KEY_SEPARATOR = "\x1f";

    /**
     * Rows for URUSETIA / ADMIN / JSM, filtered by the selected negeri, cawangan and
     * (optionally) a single pegawai.
     */
    public function forAdmin(string $date, ?string $state, ?string $branch, ?string $officerId = null): Collection
    {
        $scopeRows = $this->order(
            $this->baseQuery($this->reportDate($date))
                ->when($this->hasState($state), fn (Builder $query) => $query->where('branch_state_code', $state))
                ->when($this->hasBranch($branch), fn (Builder $query) => $query->where('acct_branch_code', $branch))
                ->get()
        );

        $listed = $this->oneRowPerOfficer(
            $officerId ? $scopeRows->where('officer_id', $officerId) : $scopeRows
        );

        return $this->assemble($listed, $scopeRows, $this->scope($state, $branch), $this->passingMarks($date));
    }

    /**
     * Rows for a PYD, who only ever sees their own record. No total applies here.
     */
    public function forOfficer(string $date, string $officerId): Collection
    {
        $branchCode = SummMthOfficer::whereOfficerId($officerId)
            ->orderBy('report_date', 'desc')
            ->value('officer_branch_code');

        if (! $branchCode) {
            return collect();
        }

        return $this->oneRowPerOfficer(
            $this->order(
                $this->baseQuery($this->reportDate($date))
                    ->whereAcctBranchCode($branchCode)
                    ->whereOfficerId($officerId)
                    ->get()
            )
        );
    }

    /**
     * Lays the listing out negeri by negeri and cawangan by cawangan, closing each group with
     * its sub-total and the whole table with the total matching the filter.
     */
    private function assemble(Collection $listed, Collection $scopeRows, string $scope, array $passingMarks): Collection
    {
        $rows = [];

        foreach ($listed->groupBy('branch_state_code') as $stateCode => $stateRows) {
            foreach ($stateRows->groupBy('acct_branch_code') as $branchCode => $branchRows) {
                foreach ($branchRows as $row) {
                    $rows[] = $row;
                }

                // With a single cawangan on screen its total is the grand total already.
                if ($scope !== self::SCOPE_BRANCH) {
                    $rows[] = $this->total(
                        $scopeRows->where('acct_branch_code', $branchCode),
                        self::BRANCH_TOTAL,
                        $passingMarks
                    );
                }
            }

            // Likewise for a single negeri.
            if ($scope === self::SCOPE_OVERALL) {
                $rows[] = $this->total(
                    $scopeRows->where('branch_state_code', $stateCode),
                    self::STATE_TOTAL,
                    $passingMarks
                );
            }
        }

        if ($rows === []) {
            return collect();
        }

        $rows[] = $this->total($scopeRows, $this->grandTotalFlag($scope), $passingMarks);

        return collect($rows);
    }

    /**
     * A total row shaped like a SummMthOfficer, so the table renders it like any other row.
     */
    private function total(Collection $rows, string $flag, array $passingMarks): SummMthOfficer
    {
        $countable = $rows->where('incl_pmgi_flag', '!=', self::BRANCH_MANAGER);

        $total = new SummMthOfficer();
        $total->incl_pmgi_flag = $flag;

        foreach (self::SUM_COLUMNS as $column) {
            $total->{$column} = round($countable->sum(fn (SummMthOfficer $row) => (float) $row->{$column}), 2);
        }

        $achieved = 0;

        foreach (self::CRITERIA as [$ptsColumn, $numerator, $denominator, $evaluationId, $flagColumn]) {
            $percentage = $this->percentage($total->{$numerator}, $total->{$denominator});
            $capai = $percentage >= ($passingMarks[$evaluationId] ?? 0.0) ? 'Y' : 'N';

            $total->{$ptsColumn} = $percentage;
            $total->{$flagColumn} = $capai;

            $achieved += $capai === 'Y' ? 1 : 0;
        }

        // A pegawai / cawangan / negeri only "capai" by meeting every kriteria.
        $total->pmgi_capai_flag = $achieved === count(self::CRITERIA) ? 'Y' : 'N';

        return $this->label($total, $rows->first(), $flag);
    }

    /**
     * Carries over just enough of a member row for the table to name the total.
     */
    private function label(SummMthOfficer $total, ?SummMthOfficer $member, string $flag): SummMthOfficer
    {
        if (! $member || $flag === self::OVERALL_TOTAL) {
            return $total;
        }

        $total->branch_state_code = $member->branch_state_code;
        $total->negeri = $member->negeri;

        if ($flag === self::BRANCH_TOTAL) {
            $total->acct_branch_code = $member->acct_branch_code;
            $total->cawangan = $member->cawangan;
            $total->setRelation('branch', $member->branch);
        }

        return $total;
    }

    private function percentage(float $numerator, float $denominator): float
    {
        return $denominator == 0.0 ? 0.0 : round($numerator / $denominator * 100, 2);
    }

    /**
     * The passing mark of each kriteria, keyed by evaluation id.
     */
    private function passingMarks(string $date): array
    {
        return RefEvalPctg::where('state_code', '01') // once da approve utk by negeri, tukar ni.. skrg pkai 01 sbb smua negeri sama value
            ->whereDate('effective_date', '<=', $date)
            ->orderBy('effective_date', 'ASC') // latest applicable rate wins the key
            ->pluck('evaluation_percentage', 'evaluation_id')
            ->map(fn ($percentage) => (float) $percentage)
            ->all();
    }

    /**
     * Negeri by negeri, cawangan by cawangan, pegawai by pegawai.
     */
    private function order(Collection $rows): Collection
    {
        return $rows
            ->sortBy(fn (SummMthOfficer $row) => implode(self::KEY_SEPARATOR, [
                $row->branch_state_code ?? '',
                $row->cawangan ?? '',
                $row->incl_pmgi_flag ?? '',
                $row->officer_name ?? '',
            ]))
            ->values();
    }

    /**
     * A pengurus who also holds their own accounts has two rows - the cawangan-wide one (B)
     * and their own (H). The table has always listed only the first, so keep that, but note
     * that the totals are worked out before this runs: both rows count towards the figures.
     */
    private function oneRowPerOfficer(Collection $rows): Collection
    {
        return $rows
            ->unique(fn (SummMthOfficer $row) => implode(self::KEY_SEPARATOR, [
                $row->branch_state_code,
                $row->acct_branch_code,
                $row->officer_id,
            ]))
            ->values();
    }

    private function baseQuery(string $reportDate): Builder
    {
        return SummMthOfficer::with(['branch', 'officerBranch', 'fmsBankOfficers'])
            ->whereDate('report_date', $reportDate)
            ->whereNotNull('officer_id')
            ->whereNotIn('officer_id', self::EXCLUDED_OFFICER_IDS);
    }

    private function scope(?string $state, ?string $branch): string
    {
        if (! $this->hasState($state)) {
            return self::SCOPE_OVERALL;
        }

        return $this->hasBranch($branch) ? self::SCOPE_BRANCH : self::SCOPE_STATE;
    }

    private function grandTotalFlag(string $scope): string
    {
        return match ($scope) {
            self::SCOPE_BRANCH => self::BRANCH_TOTAL,
            self::SCOPE_STATE => self::STATE_TOTAL,
            default => self::OVERALL_TOTAL,
        };
    }

    private function reportDate(string $date): string
    {
        return Carbon::parse($date)->endOfMonth()->format('Y-m-d');
    }

    private function hasState(?string $state): bool
    {
        return filled($state) && $state !== self::ALL_STATES;
    }

    private function hasBranch(?string $branch): bool
    {
        return filled($branch) && $branch !== self::ALL_BRANCHES;
    }
}
