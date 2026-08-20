<?php

namespace App\Services\Prestasi;

use App\Models\SummMthOfficer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Builds the rows rendered by the "Ringkasan Prestasi" monthly report, which puts the selected
 * month next to the one before it.
 *
 * Unlike the perincian report, this one deliberately keeps the rules it has always had: it
 * reads the total rows pmgi_summ_mth_officer already stores (incl_pmgi_flag N / S / W) instead
 * of working them out, and it still lists every pegawai row the table holds. Only the shape
 * changed - the table used to walk three nested groupBy levels itself, and now gets one entry
 * per pegawai, already in order.
 */
class PrestasiBulananRingkasanService
{
    public const ALL_STATES = '%';
    public const ALL_BRANCHES = '%%';

    /** The report shows the selected month and the one before it. */
    private const MONTHS = 2;

    /**
     * Rows for URUSETIA / ADMIN / JSM, filtered by the selected negeri, cawangan and
     * (optionally) a single pegawai.
     *
     * @return array{rows: Collection, months: Collection}
     */
    public function forAdmin(string $date, ?string $state, ?string $branch, ?string $officerId = null): array
    {
        return $this->present(
            $this->baseQuery(Carbon::parse($date))
                ->when($this->hasState($state), fn (Builder $query) => $query->where('branch_state_code', $state))
                ->when($this->hasBranch($branch), fn (Builder $query) => $query->where('acct_branch_code', $branch))
                ->when($officerId, fn (Builder $query) => $query->where('officer_id', $officerId))
                ->orderBy('report_date', 'asc')
                ->orderBy('branch_state_code', 'asc')
                ->orderBy('cawangan', 'asc')
                ->orderBy('incl_pmgi_flag', 'asc') // total rows come after the pegawai they cover
                ->get()
        );
    }

    /**
     * Rows for a PYD, who only ever sees their own record.
     *
     * @return array{rows: Collection, months: Collection}
     */
    public function forOfficer(string $date, string $officerId): array
    {
        $branchCode = SummMthOfficer::whereOfficerId($officerId)
            ->orderBy('report_date', 'desc')
            ->value('officer_branch_code');

        if (! $branchCode) {
            return ['rows' => collect(), 'months' => collect()];
        }

        return $this->present(
            $this->baseQuery(Carbon::parse($date))
                ->whereAcctBranchCode($branchCode)
                ->whereOfficerId($officerId)
                ->orderBy('report_date', 'asc')
                ->orderBy('incl_pmgi_flag', 'asc')
                ->get()
        );
    }

    /**
     * @return array{rows: Collection, months: Collection}
     */
    private function present(Collection $records): array
    {
        $records->transform(function (SummMthOfficer $record) {
            $record->report_date = Carbon::parse($record->report_date)->translatedFormat('F Y');

            return $record;
        });

        return [
            'months' => $records->pluck('report_date')->unique()->take(self::MONTHS)->values(),
            'rows' => $this->rows($records),
        ];
    }

    /**
     * One entry per pegawai holding their record for each month, negeri by negeri and cawangan
     * by cawangan. Flattened from the grouping the report has always used, so the order of the
     * table is unchanged.
     */
    private function rows(Collection $records): Collection
    {
        $rows = [];

        foreach ($records->groupBy('branch_state_code') as $stateRecords) {
            foreach ($stateRecords->groupBy('acct_branch_code') as $branchRecords) {
                foreach ($branchRecords->groupBy('officer_id') as $officerRecords) {
                    $rows[] = $officerRecords->take(self::MONTHS);
                }
            }
        }

        return collect($rows);
    }

    private function baseQuery(Carbon $reportDate): Builder
    {
        return SummMthOfficer::with(['branch', 'officerBranch', 'fmsBankOfficers'])
            ->whereBetween('report_date', [
                $reportDate->copy()->subMonthNoOverflow()->startOfMonth(),
                $reportDate->copy()->endOfMonth(),
            ]);
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
