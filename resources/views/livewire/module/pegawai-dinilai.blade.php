<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Ulasan Pegawai Yang Dinilai (PYD)</h3>
                    <span class="text-base font-normal text-gray-500 ">Ulasan Prestasi Semasa Dan Keperluan Penambahbaikan Oleh Pegawai Yang Dinilai (PYD)</span>
                    <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex">
                            <div class="grid w-[60%] grid-cols-3 gap-4">
                                <p class="flex items-center font-semibold">Nama Pegawai Yang Dinilai</p>
                                <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                                <p class="flex items-center font-semibold">Jawatan</p>
                                <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                                <p class="flex items-center font-semibold">Nombor Pekerja</p>
                                <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 w-[70%]">
                <div class="flex items-center w-full px-4 py-2 rounded-lg bg-lime-300">
                    <h3 class="mr-4 text-lg font-medium text-gray-900">Masalah yang dihadapi :</h3>
                    <select id="small" class="flex-1 block p-2 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                        <option selected>Sila Pilh</option>
                        <option value="1">Tiada bimbingan daripada Pejabat Negeri</option>
                        <option value="2">Masalah Kesihatan</option>
                        <option value="3">Tiada tunjuk ajar daripada penyelia ( nyatakan masalah dan keperluan tersebut )</option>
                    </select>
                    <x-icon solid  name="information-circle" class="w-6 h-6 bg-white rounded-xl text-primary-500" />
                </div>
                <div class="my-4">
                    <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Nyatakan punca bagi masalah tersebut :</label>
                    <textarea id="punca" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="pelan" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Pelan tindakan untuk meningkatkan prestasi :</label>
                    <textarea id="pelan" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Ulasan (Jika ada) :</label>
                    <textarea id="punca" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex">
                    <button type="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                        Hantar
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
