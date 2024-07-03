<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Mesyuarat Jawatankuasa Timbang Tara</h3>
                    <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between">
                            <div class="grid flex-1 gap-x-4 gap-y-2" style="margin-right: 10rem;grid-template-columns: repeat(5, minmax(0, 1fr));">
                                <div>
                                    <label for="negeri" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">No Pekerja</label>
                                    <input type="text" id="negeri" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label for="negeri" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Negeri</label>
                                    <input type="text" id="negeri" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label for="cawangan" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Cawangan</label>
                                    <input type="text" id="cawangan" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div class="col-span-2">
                                    <label for="bulan" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama Pegawai</label>
                                    <input type="text" id="bulan" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </div>
                            <div class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                Cari
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="flex flex-col mt-8">
                <h3 class="mb-2 text-xl font-bold text-gray-900 ">Rekod PMGi </h3>
                <div class="mt-4 mb-4">
                    <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 ">
                        <li class="me-2">
                            <a href="#" class="inline-block px-4 py-3 text-white rounded-lg bg-primary-600 active" aria-current="page">PMGi 1</a>
                        </li>
                        <li class="me-2">
                            <a href="#" class="inline-block px-4 py-3 rounded-lg hover:text-gray-900 hover:bg-gray-100 ">PMGi 2</a>
                        </li>
                        <li class="me-2">
                            <a href="#" class="inline-block px-4 py-3 rounded-lg hover:text-gray-900 hover:bg-gray-100 ">PMGi 3</a>
                        </li>
                    </ul>
                </div>
                <div class="items-center">
                    <div class="mb-4 lg:mb-0">
                        <div class="p-6 bg-gray-100 border border-gray-200 rounded-lg shadow">
                            <div class="grid w-1/2 grid-cols-2 gap-2 mx-auto">
                                <p class="flex items-center font-semibold">Tarikh</p>
                                <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                                <p class="flex items-center font-semibold">Pegawai Yang Menilai</p>
                                <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                                <p class="flex items-center font-semibold">Status</p>
                                <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                                <p class="flex items-center font-semibold">Keputusan</p>
                                <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            </div>
                        </div>
                        <div class="w-1/2 mx-auto mt-8">
                            <h3 class="text-lg font-medium text-center text-gray-900">Keputusan :</h3>
                            <select id="small" wire:model.live="result" class="block w-1/2 p-2 mx-auto text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                <option selected>Sila Pilih</option>
                                <option value="1">Diberi Tempoh</option>
                                <option value="2">Domestic Inquiry (DI)</option>
                            </select>

                            @if($result == 1)
                                <div class="flex items-center justify-center mt-2">
                                    <input type="text" id="negeri" class="block p-1 text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500" style=" width: 10%;">
                                    <label for="negeri" class="block ml-4 font-medium text-gray-900 dark:text-white">Bulan</label>
                                </div>
                            @endif

                            <h3 class="mt-6 text-lg font-medium text-center text-gray-900">Ulasan :</h3>
                            <textarea id="pelan" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-4">
                <button type="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                    Simpan
                </button>
            </div>

        </div>
    </div>
</main>
