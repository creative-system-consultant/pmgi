<?php

namespace App\Jobs;

use App\Services\Report\PmgiReportService;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Cell\DateTimeCell;
use OpenSpout\Common\Entity\Cell\EmptyCell;
use OpenSpout\Common\Entity\Cell\NumericCell;
use OpenSpout\Common\Entity\Cell\StringCell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Entity\SheetView;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;
use Throwable;

/**
 * Streams one report date of PMGI_RAW_MASTER (~170k rows x 72 columns) into an XLSX file
 * on the local disk. Progress is published to the cache so the Livewire page can poll it.
 *
 * OpenSpout is used rather than PhpSpreadsheet because the latter holds every cell in memory -
 * 12.4 million cells would exhaust the worker long before the file was written.
 */
class PmgiRawMasterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** The export is expensive; never silently run it twice. */
    public $tries = 1;

    public $timeout = 3600;

    /** How long a finished export (and its status entry) stays available. */
    public const RETENTION_HOURS = 6;

    public const DISK = 'local';

    public const DIRECTORY = 'exports/pmgi-raw-master';

    public const EXTENSION = 'xlsx';

    /** Rows written between cache progress updates. */
    private const PROGRESS_EVERY = 5000;

    /** Wide enough for account numbers and officer names without measuring every cell. */
    private const COLUMN_WIDTH = 18.0;

    /** Status cache key for an export, derived so it can never be spoofed by the browser. */
    public static function cacheKey(string $userId, string $token): string
    {
        return 'pmgi-raw-master:export:' . $userId . ':' . $token;
    }

    public function __construct(
        public string $cacheKey,
        public string $path,
        public string $filename,
        public string $reportDate,
        public ?string $state = null,
        public ?string $branch = null,
        public ?string $officer = null,
    ) {}

    /**
     * A full export runs for minutes, which can exceed the queue's retry_after window and get
     * the same payload handed to a second worker. The lock keeps the duplicate from writing
     * over the file the first worker is still building.
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->cacheKey))->dontRelease()->expireAfter($this->timeout + 60)];
    }

    public function handle(PmgiReportService $service): void
    {
        $this->publish(['status' => 'processing', 'rows' => 0]);

        $disk = Storage::disk(self::DISK);
        $disk->makeDirectory(self::DIRECTORY);

        $this->pruneExpiredExports();

        // Written to a .part file first so a poll can never hand the user a half-finished workbook.
        $partial = $this->path . '.part';

        $writer = new Writer(new Options(
            DEFAULT_COLUMN_WIDTH: self::COLUMN_WIDTH,
            tempFolder: storage_path('app/' . self::DIRECTORY),
        ));

        $rows = 0;

        try {
            $writer->openToFile($disk->path($partial));
            $writer->getCurrentSheet()
                ->setName('RAW MASTER')
                ->setSheetView((new SheetView())->withFreezeRow(2));

            $writer->addRow(Row::fromValuesWithStyle(
                PmgiReportService::COLUMNS,
                (new Style())->withFontBold(true)
            ));

            // Built once: a Style per cell would defeat OpenSpout's style de-duplication.
            $plan = $this->columnPlan($service->columnTypes());

            foreach ($service->loadQuery($this->reportDate, $this->state, $this->branch, $this->officer)->cursor() as $record) {
                $cells = [];

                foreach ($plan as [$column, $kind, $style]) {
                    $cells[] = $this->cell($record->{$column} ?? null, $kind, $style);
                }

                $writer->addRow(new Row($cells));

                if (++$rows % self::PROGRESS_EVERY === 0) {
                    $this->publish(['status' => 'processing', 'rows' => $rows]);
                }
            }

            $writer->close();
        } catch (Throwable $e) {
            $disk->delete($partial);

            throw $e;
        }

        $disk->delete($this->path);
        $disk->move($partial, $this->path);

        $this->publish([
            'status' => 'ready',
            'rows' => $rows,
            'path' => $this->path,
            'filename' => $this->filename,
            'size' => $disk->size($this->path),
            'finished_at' => now()->toDateTimeString(),
        ]);
    }

    public function failed(?Throwable $e): void
    {
        Log::error('PmgiRawMasterJob gagal: ' . $e?->getMessage(), ['cacheKey' => $this->cacheKey]);

        Storage::disk(self::DISK)->delete($this->path . '.part');

        $this->publish([
            'status' => 'failed',
            'message' => $e?->getMessage() ?: 'Ralat tidak dijangka semasa menjana laporan.',
        ]);
    }

    /**
     * Pairs every exported column with how it should be written, so the row loop does no
     * lookups: [column name, kind, shared style].
     *
     * @return array<int, array{0: string, 1: string, 2: ?Style}>
     */
    private function columnPlan(array $types): array
    {
        $dateStyle = (new Style())->withFormat('dd/mm/yyyy');
        $decimalStyle = (new Style())->withFormat('#,##0.00');

        $plan = [];

        foreach (PmgiReportService::COLUMNS as $column) {
            $kind = $types[$column]['kind'] ?? 'string';
            $scale = $types[$column]['scale'] ?? 0;

            $plan[] = [$column, $kind, match (true) {
                $kind === 'date' => $dateStyle,
                $kind === 'numeric' && $scale > 0 => $decimalStyle,
                default => null,
            }];
        }

        return $plan;
    }

    private function cell(mixed $value, string $kind, ?Style $style): Cell
    {
        if ($value === null || $value === '') {
            return new EmptyCell(null, $style);
        }

        // A malformed date must not abort a 170k-row export - fall back to the raw text.
        if ($kind === 'date') {
            try {
                return new DateTimeCell(
                    $value instanceof DateTimeInterface ? $value : new DateTimeImmutable((string) $value),
                    $style
                );
            } catch (Throwable) {
                return new StringCell((string) $value);
            }
        }

        if ($kind === 'numeric' && is_numeric($value)) {
            return new NumericCell($value + 0, $style);
        }

        return new StringCell((string) $value, $style);
    }

    /** Drop workbooks left behind by earlier runs once they are past the retention window. */
    private function pruneExpiredExports(): void
    {
        $disk = Storage::disk(self::DISK);
        $cutoff = now()->subHours(self::RETENTION_HOURS)->getTimestamp();

        foreach ($disk->files(self::DIRECTORY) as $file) {
            if ($file !== $this->path && $disk->lastModified($file) < $cutoff) {
                $disk->delete($file);
            }
        }
    }

    private function publish(array $status): void
    {
        Cache::put(
            $this->cacheKey,
            array_merge(['updated_at' => now()->toDateTimeString()], $status),
            now()->addHours(self::RETENTION_HOURS)
        );
    }
}
