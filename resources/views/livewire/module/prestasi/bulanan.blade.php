@push('style')
<style>
    .table-container {
        height: 300px;
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

    .headcol-clear {
        position: sticky;
        top: 0;
    }

    .headcol-pengurus {
        position: sticky;
        top: 0;
    }

    thead th:nth-child(1) {
        left: 0;
        z-index: 999;
    }

    tbody th:nth-child(1) {
        left: 0;
        z-index: 999;
    }

    thead th:nth-child(2) {
        left: 236px;
        z-index: 999;
    }

    tbody th:nth-child(2) {
        left: 236px;
        z-index: 999;
    }

    thead th:nth-child(3) {
        left: 409px;
        z-index: 999;
    }

    tbody th:nth-child(3) {
        left: 409px;
        z-index: 999;
    }

</style>
@endpush

<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Prestasi Bulanan</h3>
                    <span class="text-base font-normal text-gray-500 ">Ringkasan Prestasi Bulanan Pegawai </span>
                    <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between">
                            <div class="grid w-[70%] grid-cols-4 gap-x-4 gap-y-2">
                                <div>
                                    <label for="jenis" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Jenis</label>
                                    <select wire:model="type" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                        <option selected>Sila Pilih</option>
                                        <option value="1">Ringkasan</option>
                                        <option value="2">Keseluruhan</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="negeri" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Negeri</label>
                                    <input type="text" id="negeri" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label for="cawangan" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Cawangan</label>
                                    <input type="text" id="cawangan" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label for="bulan" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Bulan</label>
                                    <input type="text" id="bulan" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </div>
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
            <!-- Result -->
            @if($result)
                @if($type == 1)  <!-- ringkasan -->
                    <livewire:module.prestasi.bulanan.ringkasan />
                @else <!-- keseluruhan -->
                    <livewire:module.prestasi.bulanan.keseluruhan />
                @endif
            @endif
        </div>
    </div>
</main>
