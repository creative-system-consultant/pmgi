<?php

namespace App\Services\Report;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PmgiReportService
{
    /** Sentinel used by the filter dropdowns to mean "do not filter on this column". */
    public const ALL = '%';

    /**
     * Tokens that must stay upper case when a stored value is title cased for display.
     * The source data is entirely upper case, so these cannot be detected - they are listed.
     */
    private const ACRONYMS = ['HQ', 'W.P'];

    public const TABLE = 'PMGI.dbo.PMGI_RAW_MASTER';

    /** How long the filter dropdown values stay cached. */
    private const OPTIONS_TTL = 600;

    public const COLUMNS = [
        'REPORT_DATE', 'NEGERI', 'CAWANGAN', 'PARLIMEN', 'NAMA', 'NO_KP', 'AKAUN', 'BILPINJAMAN', 'PINJAMAN', 'PRODUK', 'PRODUK_KATEGORI',
        'BAKI_PINJAMAN', 'TUNGGAKAN', 'STATUS_HISTORY', 'STATUS_SEMASA', 'BULANTUNGGAK', 'BAKIPOKOK', 'MOD_BAYARAN', 'BAKIOWING', 'SEKTOR',
        'TEMPOH', 'TMB', 'TAB_ORI', 'TMBPS2', 'TAB', 'ANSURANPCS', 'ANSURANPC', 'BAYAR_KESELURUHAN', 'BAYAR_BULAN_SEMASA', 'FLAG_BYR_BULAN_SEMASA',
        'ANSURAN_MTH', 'PTTKUTIP_SBENAR', 'TRA', 'NAMA_PEGAWAI', 'TARIKH_PENGELUARAN', 'TARIKH_TAGGING_SELIAAN', 'PEGAWAI_AKAUN', 'NOPEKERJA',
        'AKTIVITI', 'CATEGORI_BARU', 'FLAG_PRESTASI_NPF', 'CAT_BEFORE', 'TAHUN_LULUS', 'TAHUN_LULUS_2', 'SELIAANOWNER', 'RESCHED2FLAG', 'AMTPKUTIP',
        'PINJAMAN_CAJ', 'SIMP_PTT_BAYAR', 'TEMPOHTANGGUH', 'ID_GROUP', 'BAKICAJ', 'BULANAGEING', 'AGEING_BEFORE', 'WEEK_ARR', 'WEEKARR_BEFORE',
        'BULAN_MORA', 'TEMPOH_PS', 'TAM', 'EMANDATE', 'APPLY_DATE', 'MORA_EXPIRYDT', 'LAST_TAG_MAC', 'LASTTAGMACBY', 'LASTTAGMACPEGSELIAAN', 'BIL_LAWAT_MAC',
        'FLAGLAWAT_PEGSELIABLNSEMASA', 'FLAG_LAWATAN', 'TARIKH_SELESAI_BAYAR', 'TARIKH_TUTUP_TAMAT', 'AMTAKHIR', 'JENIS',
    ];

    /**
     * The raw master rows for one report date, ordered the same way as the original report.
     *
     * Returned as a builder (not a result set) so the caller can stream it with cursor() —
     * a single report date is ~170k rows, which must never be hydrated all at once.
     */
    public function loadQuery(string $reportDate, ?string $state = null, ?string $branch = null, ?string $officer = null): Builder
    {
        return DB::table(self::TABLE)
            ->select(self::COLUMNS)
            ->where('REPORT_DATE', $reportDate)
            ->when($this->isFiltered($state), fn ($query) => $query->where('NEGERI', $state))
            ->when($this->isFiltered($branch), fn ($query) => $query->where('CAWANGAN', $branch))
            ->when($this->isFiltered($officer), fn ($query) => $query->where('PEGAWAI_AKAUN', $officer))
            ->orderBy('NEGERI')
            ->orderBy('CAWANGAN')
            ->orderBy('PEGAWAI_AKAUN')
            ->orderBy('AKAUN');
    }

    /** Report dates present in the raw master, newest first. */
    public function reportDates(): Collection
    {
        return Cache::remember('pmgi-raw-master:report-dates', self::OPTIONS_TTL, function () {
            return DB::table(self::TABLE)
                ->select('REPORT_DATE')
                ->distinct()
                ->orderByDesc('REPORT_DATE')
                ->pluck('REPORT_DATE')
                ->map(fn ($date) => (object) [
                    'value' => substr((string) $date, 0, 10),
                    'label' => \Carbon\Carbon::parse($date)->format('d/m/Y'),
                ])
                ->values();
        });
    }

    public function states(?string $reportDate): Collection
    {
        return $this->withAllOption(
            'Semua Negeri',
            $this->distinctValues($reportDate, 'NEGERI')
        );
    }

    public function branches(?string $reportDate, ?string $state): Collection
    {
        // "Semua Negeri" spans every branch, so there is nothing meaningful to pick here.
        if (! $this->isFiltered($state)) {
            return $this->withAllOption('Semua Cawangan', collect());
        }

        return $this->withAllOption(
            'Semua Cawangan',
            $this->distinctValues($reportDate, 'CAWANGAN', ['NEGERI' => $state])
        );
    }

    public function officers(?string $reportDate, ?string $state, ?string $branch): Collection
    {
        if (! $this->isFiltered($branch) || ! $reportDate) {
            return $this->withAllOption('Semua Pegawai', collect());
        }

        $key = "pmgi-raw-master:officers:{$reportDate}:{$state}:{$branch}";

        $officers = Cache::remember($key, self::OPTIONS_TTL, function () use ($reportDate, $state, $branch) {
            return DB::table(self::TABLE)
                ->select('PEGAWAI_AKAUN', 'NAMA_PEGAWAI', 'NOPEKERJA')
                ->where('REPORT_DATE', $reportDate)
                ->when($this->isFiltered($state), fn ($query) => $query->where('NEGERI', $state))
                ->where('CAWANGAN', $branch)
                ->whereNotNull('PEGAWAI_AKAUN')
                ->groupBy('PEGAWAI_AKAUN', 'NAMA_PEGAWAI', 'NOPEKERJA')
                ->orderBy('NAMA_PEGAWAI')
                ->get()
                ->map(fn ($row) => (object) [
                    'value' => $row->PEGAWAI_AKAUN,
                    'label' => $this->titleCase($row->NAMA_PEGAWAI ?: $row->PEGAWAI_AKAUN) . ' (' . $row->NOPEKERJA . ')',
                ])
                ->values();
        });

        return $this->withAllOption('Semua Pegawai', $officers);
    }

    /** A filter value that is null, empty or the ALL sentinel means "every row". */
    public function isFiltered(?string $value): bool
    {
        return filled($value) && $value !== self::ALL;
    }

    private function distinctValues(?string $reportDate, string $column, array $where = []): Collection
    {
        if (! $reportDate) {
            return collect();
        }

        $key = "pmgi-raw-master:{$column}:{$reportDate}:" . implode('|', $where);

        return Cache::remember($key, self::OPTIONS_TTL, function () use ($reportDate, $column, $where) {
            return DB::table(self::TABLE)
                ->select($column)
                ->distinct()
                ->where('REPORT_DATE', $reportDate)
                ->where($where)
                ->whereNotNull($column)
                ->orderBy($column)
                ->pluck($column)
                ->map(fn ($value) => (object) ['value' => $value, 'label' => $this->titleCase($value)])
                ->values();
        });
    }

    /**
     * The raw master stores every label in upper case; the dropdowns read better in title case.
     * Only the label is converted - the value keeps the stored casing so filtering still matches.
     */
    public function titleCase(?string $value): string
    {
        $title = Str::title(trim((string) $value));

        foreach (self::ACRONYMS as $acronym) {
            $title = preg_replace('/\b' . preg_quote($acronym, '/') . '\b/i', $acronym, $title);
        }

        return $title;
    }

    /**
     * How each exported column should be written to the spreadsheet, keyed by column name:
     * ['kind' => 'string'|'numeric'|'date', 'scale' => decimal places].
     *
     * Read from the schema rather than hardcoded so the export follows the table if it changes.
     * This is what stops NO_KP and AKAUN (varchar, all digits) being coerced into numbers.
     */
    public function columnTypes(): array
    {
        return Cache::remember('pmgi-raw-master:column-types', self::OPTIONS_TTL, function () {
            $columns = DB::table('PMGI.INFORMATION_SCHEMA.COLUMNS')
                ->select('COLUMN_NAME', 'DATA_TYPE', 'NUMERIC_SCALE')
                ->where('TABLE_NAME', 'PMGI_RAW_MASTER')
                ->get();

            $types = [];

            foreach ($columns as $column) {
                $types[$column->COLUMN_NAME] = [
                    'kind' => match (strtolower($column->DATA_TYPE)) {
                        'date', 'datetime', 'datetime2', 'smalldatetime', 'datetimeoffset' => 'date',
                        'numeric', 'decimal', 'money', 'smallmoney', 'float', 'real',
                        'int', 'bigint', 'smallint', 'tinyint' => 'numeric',
                        default => 'string',
                    },
                    'scale' => (int) ($column->NUMERIC_SCALE ?? 0),
                ];
            }

            return $types;
        });
    }

    private function withAllOption(string $label, Collection $options): Collection
    {
        return $options->prepend((object) ['value' => self::ALL, 'label' => $label]);
    }
}
