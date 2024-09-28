@push('style')
<style>
    .main-container {
        height: 85vh;
        display: flex;
        flex-direction: column;
    }

    .header-section {
        flex-shrink: 0;
    }

    .result-section {
        flex-grow: 1;
        overflow: hidden;
    }

    .table-container {
        height: 100%;
        overflow: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .headcol {
        position: sticky;
        top: 0;
    }

    thead th:nth-child(1) {
        position: sticky;
        left: 0;
    }

    tbody th:nth-child(1) {
        position: sticky;
        left: 0;
    }

</style>
@endpush

<main class="main-container">
    <div class="h-full px-4 pt-6 2xl:px-0">
        <div class="flex flex-col h-full p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="header-section">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Prestasi Bulanan</h3>
                    <span class="text-base font-normal text-gray-500">Ringkasan Prestasi Bulanan Pegawai</span>
                    <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between">
                            <div class="grid w-[70%] grid-cols-4 gap-x-4 gap-y-2">
                                <div>
                                    <x-select class="z-50" label="Jenis" placeholder="Sila Pilih" :options="[
                                            ['desc' => 'RINGKASAN',  'id' => 1],
                                            ['desc' => 'KESELURUHAN', 'id' => 2],
                                        ]" option-label="desc" option-value="id" wire:model.live="type" />
                                </div>
                                @if($role == 'admin')
                                <div>
                                    <x-select class="z-50" label="Negeri" placeholder="Sila Pilih" :options="$stateSelection" option-label="description" option-value="code" wire:model.live="state" />
                                </div>
                                <div>
                                    <x-select empty-message="Sila Pilih Negeri" class="z-50" label="Cawangan" placeholder="Sila Pilih" :options="$branchSelection" option-label="branch_name" option-value="branch_code" wire:model.live="branch" />
                                </div>
                                @endif
                                <div class="z-50 ">
                                    <x-datetime-picker label="Bulan" placeholder="Bulan" display-format="MMM-YYYY" wire:model="date" without-time />
                                </div>
                            </div>

                            <div class="flex">
                                @if($result)
                                    <div class="inline-flex items-center px-3 py-2 mt-4 mr-4 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-lime-500 hover:bg-lime-800 focus:ring-4 focus:outline-none focus:ring-lime-300 dark:bg-lime-600 dark:hover:bg-lime-700 dark:focus:ring-lime-800" wire:click="download">
                                        <x-icon name="download" class="w-6 h-6 mr-2" />
                                        Muat Turun
                                    </div>
                                @endif
                                <div class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" wire:click="generate">
                                    Cari
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Result -->
            <div class="mt-6 result-section">
                @if($result)
                    @if($type == 1)
                        <livewire:module.prestasi.bulanan.ringkasan
                            :key="'ringkasan-'.$state.'-'.$branch.'-'.$date"
                            :role=$role :state=$state :branch=$branch :date=$date
                        />
                    @else
                        <livewire:module.prestasi.bulanan.keseluruhan
                            :key="'keseluruhan-'.$state.'-'.$branch.'-'.$date"
                            :role=$role :state=$state :branch=$branch :date=$date
                        />
                    @endif
                @endif
            </div>
        </div>
    </div>
</main>
