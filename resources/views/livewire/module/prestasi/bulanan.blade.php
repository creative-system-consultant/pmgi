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
        /* Remove border-collapse to make sure borders appear */
        border-spacing: 0;
        border: 1px solid black;
        /* Add outer border around the table */
    }

    th,
    td {
        /* Ensure borders are visible for table cells */
        border: 1px solid black;
    }

    /* Sticky Header Styling */
    thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        /* Ensure header is above the table rows */
        background-color: #f1f1f1;
        /* Replace with your desired header color */
    }

    /* Sticky First Column */
    .headcol {
        position: sticky;
        left: 0;
        z-index: 2;
        /* Ensure this is above other columns */
        !background-color: #9ca3af;
        /* Replace with your desired column color */
    }

    /* Sticky first column in header */
    thead th:nth-child(1) {
        position: sticky;
        left: 0;
        z-index: 2;
        background-color: #f1f1f1;
        /* Replace with your desired header color */
    }

    tbody th:nth-child(1) {
        position: sticky;
        left: 0;
    }

</style>
@endpush

<main class="main-container">
    <div class="px-4 pt-6 h-full 2xl:px-0">
        <div class="flex flex-col p-4 my-4 h-full bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="header-section">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Prestasi Bulanan</h3>
                    <span class="text-base font-normal text-gray-500">Ringkasan Prestasi Bulanan Pegawai</span>
                    <div class="p-6 mt-4 rounded-lg border shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        @if($role != 'pyd')
                            <!-- Form Fields Row -->
                            <div class="grid grid-cols-12 gap-y-2 gap-x-4 mb-4">
                                <div class="col-span-2">
                                    <x-select class="z-50" label="Jenis" placeholder="Sila Pilih" :options="[
                                            ['desc' => 'RINGKASAN PRESTASI',  'id' => 1],
                                            ['desc' => 'PERINCIAN PRESTASI', 'id' => 2],
                                        ]" option-label="desc" option-value="id" wire:model.live="type" />
                                </div>
                                @if ($role == 'admin')
                                    <div class="col-span-2">
                                        <x-select class="z-50" label="Negeri" placeholder="Sila Pilih" :options="$stateSelection" option-label="description" option-value="code" wire:model.live="state" />
                                    </div>
                                @endif
                                <div class="col-span-2">
                                    <x-select empty-message="Sila Pilih Negeri" class="z-50" label="Cawangan" placeholder="Sila Pilih" :options="$branchSelection" option-label="branch_name" option-value="branch_code" wire:model.live="branch" />
                                </div>
                                <div class="col-span-4">
                                    <x-select
                                        label="Nama Pegawai (Pilihan)"
                                        wire:model="staffName"
                                        placeholder="Kosongkan untuk semua pegawai"
                                        :async-data="route('staff-name-search-by-branch', ['branch_code' => $branch])"
                                        option-label="officer_name"
                                        option-value="officer_name"
                                    />
                                </div>
                                <div class="z-50 col-span-2">
                                    <x-datetime-picker label="Bulan" placeholder="Bulan" display-format="MMM-YYYY" wire:model="date" without-time />
                                </div>
                            </div>
                            
                            <!-- Buttons Row -->
                            <div class="flex gap-4 justify-end">
                                @if($result)
                                    <div class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-lime-500 rounded-lg cursor-pointer hover:bg-lime-800 focus:ring-4 focus:outline-none focus:ring-lime-300 dark:bg-lime-600 dark:hover:bg-lime-700 dark:focus:ring-lime-800" wire:click="download">
                                        <x-icon name="download" class="mr-2 w-6 h-6" />
                                        Muat Turun
                                    </div>
                                @endif
                                <div class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" wire:click="generate">
                                    Cari
                                    <svg class="w-3.5 h-3.5 rtl:rotate-180 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                </div>
                            </div>
                        @else
                            <!-- PYD Layout -->
                            <div class="grid grid-cols-6 gap-y-2 gap-x-4 mb-4">
                                <div class="col-span-3">
                                    <x-select class="z-50" label="Jenis" placeholder="Sila Pilih" :options="[
                                            ['desc' => 'RINGKASAN PRESTASI',  'id' => 1],
                                            ['desc' => 'PERINCIAN PRESTASI', 'id' => 2],
                                        ]" option-label="desc" option-value="id" wire:model.live="type" />
                                </div>
                                <div class="z-50 col-span-3">
                                    <x-datetime-picker label="Bulan" placeholder="Bulan" display-format="MMM-YYYY" wire:model="date" without-time />
                                </div>
                            </div>
                            
                            <!-- Buttons Row -->
                            <div class="flex gap-4 justify-end">
                                @if($result)
                                    <div class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-lime-500 rounded-lg cursor-pointer hover:bg-lime-800 focus:ring-4 focus:outline-none focus:ring-lime-300 dark:bg-lime-600 dark:hover:bg-lime-700 dark:focus:ring-lime-800" wire:click="download">
                                        <x-icon name="download" class="mr-2 w-6 h-6" />
                                        Muat Turun
                                    </div>
                                @endif
                                <div class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" wire:click="generate">
                                    Cari
                                    <svg class="w-3.5 h-3.5 rtl:rotate-180 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Result -->
            <div class="mt-6 result-section">
                @if($result)
                    @if($type == 1)
                        <livewire:module.prestasi.bulanan.ringkasan
                            :key="'ringkasan-'.$state.'-'.$branch.'-'.$pydId.'-'.$date"
                            :role=$role :state=$state :branch=$branch :pydId=$pydId :date=$date
                        />
                    @else
                        <livewire:module.prestasi.bulanan.keseluruhan
                            :key="'keseluruhan-'.$state.'-'.$branch.'-'.$pydId.'-'.$date"
                            :role=$role :state=$state :branch=$branch :pydId=$pydId :date=$date
                        />
                    @endif
                @endif
            </div>
        </div>
    </div>
</main>
