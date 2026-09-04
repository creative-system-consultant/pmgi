<div class="ml-4" @if ($isRunning) wire:poll.3s="pollStatus" @endif>
  <h2 class="pb-2 mt-6 mb-4 text-2xl font-semibold text-gray-800 border-b border-gray-300 dark:text-gray-100">
    Laporan PMGi (Raw Master)
  </h2>

  <div class="p-6 mt-6 text-gray-900 bg-white rounded-lg shadow-inner dark:bg-gray-900 dark:text-gray-100">

    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
      Laporan ini mengandungi jumlah rekod yang sangat besar. Data akan dijana di latar belakang dan
      pautan muat turun (Excel) akan dipaparkan setelah siap. Sila jangan tutup halaman ini.
    </p>

    <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2 xl:grid-cols-4">
      <x-select class="z-50" label="Tarikh Laporan" placeholder="Sila Pilih" :options="$reportDates"
        option-label="label" option-value="value" wire:model.live="reportDate" :disabled="$isRunning" />

      <x-select class="z-40" label="Negeri" placeholder="Sila Pilih" :options="$stateSelection"
        option-label="label" option-value="value" wire:model.live="state" :disabled="$isRunning" />

      <x-select class="z-30" label="Cawangan" empty-message="Sila Pilih Negeri" placeholder="Sila Pilih"
        :options="$branchSelection" option-label="label" option-value="value" wire:model.live="branch"
        :disabled="$isRunning" />

      <x-select class="z-20" label="Pegawai Akaun" empty-message="Sila Pilih Cawangan" placeholder="Sila Pilih"
        :options="$officerSelection" option-label="label" option-value="value" wire:model.live="officer"
        :disabled="$isRunning" />
    </div>

    <div class="flex gap-3 justify-end">
      <button type="button" wire:click="resetFilters" @disabled($isRunning)
        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
        Set Semula
      </button>

      @if ($isReady)
        <a href="{{ route('report.admin.raw_master.download', $jobToken) }}"
          class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-lime-500 rounded-lg hover:bg-lime-800 focus:ring-4 focus:outline-none focus:ring-lime-300 dark:bg-lime-600 dark:hover:bg-lime-700 dark:focus:ring-lime-800">
          <x-icon name="download" class="mr-2 w-5 h-5" />
          Muat Turun
        </a>
      @endif

      <button type="button" wire:click="generate" @disabled($isRunning)
        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
        <span wire:loading.remove wire:target="generate">Jana Laporan</span>
        <span wire:loading wire:target="generate">Menghantar...</span>
      </button>
    </div>

    {{-- Job status --}}
    @if ($isRunning)
      <div class="flex gap-3 items-center p-4 mt-6 text-sm text-blue-800 bg-blue-50 rounded-lg border border-blue-200 dark:bg-gray-800 dark:text-blue-300 dark:border-gray-700">
        <svg class="w-5 h-5 animate-spin shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <span>
          @if (($jobStatus['status'] ?? null) === 'queued')
            Laporan sedang menunggu giliran untuk dijana...
          @else
            Menjana laporan... {{ number_format($jobStatus['rows'] ?? 0) }} rekod telah diproses.
          @endif
        </span>
      </div>
    @elseif ($isReady)
      <div class="p-4 mt-6 text-sm text-green-800 bg-green-50 rounded-lg border border-green-200 dark:bg-gray-800 dark:text-green-300 dark:border-gray-700">
        Laporan <strong>{{ $jobStatus['filename'] }}</strong> telah siap dijana
        ({{ number_format($jobStatus['rows'] ?? 0) }} rekod,
        {{ number_format(($jobStatus['size'] ?? 0) / 1048576, 2) }} MB).
        Sila klik <strong>Muat Turun</strong>. Fail ini akan dipadam secara automatik selepas
        {{ \App\Jobs\PmgiRawMasterJob::RETENTION_HOURS }} jam.
      </div>
    @elseif (($jobStatus['status'] ?? null) === 'failed')
      <div class="p-4 mt-6 text-sm text-red-800 bg-red-50 rounded-lg border border-red-200 dark:bg-gray-800 dark:text-red-300 dark:border-gray-700">
        Laporan gagal dijana: {{ $jobStatus['message'] ?? 'Ralat tidak dijangka.' }}
      </div>
    @endif
  </div>
</div>
