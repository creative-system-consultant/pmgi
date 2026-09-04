<?php

namespace App\Livewire\Admin\Report;

use App\Jobs\PmgiRawMasterJob;
use App\Services\Report\PmgiReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;
use WireUi\Traits\Actions;

class PmgiRawMaster extends Component
{
    use Actions;

    public $reportDate;
    public $state = PmgiReportService::ALL;
    public $branch = PmgiReportService::ALL;
    public $officer = PmgiReportService::ALL;

    /** Token of the export currently tracked by this page; the cache key is derived from it. */
    public ?string $jobToken = null;

    /** Latest status published by the job: status, rows, filename, size, message. */
    public array $jobStatus = [];

    protected PmgiReportService $pmgiReportService;

    public function boot()
    {
        $this->pmgiReportService = new PmgiReportService();
    }

    public function mount()
    {
        $this->reportDate = $this->pmgiReportService->reportDates()->first()?->value
            ?? now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'reportDate' => 'required|date',
            'state' => 'nullable|string',
            'branch' => 'nullable|string',
            'officer' => 'nullable|string',
        ];
    }

    protected function messages(): array
    {
        return [
            'reportDate.required' => 'Sila pilih tarikh laporan.',
        ];
    }

    public function updatedReportDate()
    {
        $this->reset('state', 'branch', 'officer');
        $this->clearJob();
    }

    public function updatedState()
    {
        $this->reset('branch', 'officer');
        $this->clearJob();
    }

    public function updatedBranch()
    {
        $this->reset('officer');
        $this->clearJob();
    }

    public function updatedOfficer()
    {
        $this->clearJob();
    }

    public function generate()
    {
        $this->validate();

        if ($this->isRunning()) {
            return;
        }

        $filename = $this->buildFilename();
        $this->jobToken = (string) Str::uuid();
        $this->jobStatus = ['status' => 'queued', 'rows' => 0];

        Cache::put($this->jobKey(), $this->jobStatus, now()->addHours(PmgiRawMasterJob::RETENTION_HOURS));

        PmgiRawMasterJob::dispatch(
            $this->jobKey(),
            PmgiRawMasterJob::DIRECTORY . '/' . Str::uuid() . '.' . PmgiRawMasterJob::EXTENSION,
            $filename,
            $this->reportDate,
            $this->state,
            $this->branch,
            $this->officer,
        );
    }

    /** Polled by the view while an export is queued or running. */
    public function pollStatus()
    {
        if (! $this->jobToken) {
            return;
        }

        $status = Cache::get($this->jobKey());

        if (! $status) {
            // The status expired (or the queue worker is not running) - stop polling.
            $this->jobStatus = ['status' => 'failed', 'message' => 'Status laporan tidak ditemui. Sila jana semula.'];

            return;
        }

        $wasRunning = $this->isRunning();
        $this->jobStatus = $status;

        if (! $wasRunning) {
            return;
        }

        if ($this->jobStatus['status'] === 'ready') {
            $this->notification()->success(
                'Laporan Sedia',
                number_format($this->jobStatus['rows'] ?? 0) . ' rekod telah dijana. Sila muat turun.'
            );
        }

        if ($this->jobStatus['status'] === 'failed') {
            $this->notification()->error('Ralat!', $this->jobStatus['message'] ?? 'Laporan gagal dijana.');
        }
    }

    public function resetFilters()
    {
        $this->reset('state', 'branch', 'officer');
        $this->reportDate = $this->pmgiReportService->reportDates()->first()?->value
            ?? now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d');
        $this->clearJob();
    }

    public function isRunning(): bool
    {
        return in_array($this->jobStatus['status'] ?? null, ['queued', 'processing'], true);
    }

    public function isReady(): bool
    {
        return ($this->jobStatus['status'] ?? null) === 'ready';
    }

    private function jobKey(): ?string
    {
        return $this->jobToken
            ? PmgiRawMasterJob::cacheKey(auth()->user()->USERID, $this->jobToken)
            : null;
    }

    private function clearJob(): void
    {
        $this->jobToken = null;
        $this->jobStatus = [];
    }

    private function buildFilename(): string
    {
        $parts = ['PMGI_RAW_MASTER', Carbon::parse($this->reportDate)->format('Ymd')];

        foreach ([$this->state, $this->branch, $this->officer] as $filter) {
            if ($this->pmgiReportService->isFiltered($filter)) {
                $parts[] = Str::of($filter)->upper()->replaceMatches('/[^A-Z0-9]+/', '_')->trim('_')->value();
            }
        }

        return implode('_', $parts) . '.' . PmgiRawMasterJob::EXTENSION;
    }

    public function render()
    {
        return view('livewire.admin.report.pmgi-raw-master', [
            'reportDates' => $this->pmgiReportService->reportDates(),
            'stateSelection' => $this->pmgiReportService->states($this->reportDate),
            'branchSelection' => $this->pmgiReportService->branches($this->reportDate, $this->state),
            'officerSelection' => $this->pmgiReportService->officers($this->reportDate, $this->state, $this->branch),
            'isRunning' => $this->isRunning(),
            'isReady' => $this->isReady(),
        ])->extends('layouts.main');
    }
}
