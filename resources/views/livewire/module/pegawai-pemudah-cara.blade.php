<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Ulasan Pegawai Pemudah Cara (PMC)</h3>
                </div>
            </div>

            <div class="mt-8 w-[70%]">
                <div class="mt-4 mb-8">
                    <div class="mb-2">
                        <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Adakah sesi ini telah dilaksanakan dengan adil dan saksama bagi kedua-dua belah pihak?</label>
                        <div class="flex">
                            <div class="flex items-center px-20 mr-4 border border-gray-200 rounded ps-4 dark:border-gray-700">
                                <input id="pmc1" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pmc1" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">YA</label>
                            </div>
                            <div class="flex items-center px-20 border border-gray-200 rounded ps-4 dark:border-gray-700">
                                <input checked id="pmc2" type="radio" value="2" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pmc2" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">TIDAK</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Sila tuliskan ulasan anda :</label>
                        <textarea id="punca" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                    </div>
                </div>
                <hr>
                <div class="my-8">
                    <div class="mb-2">
                        <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">PYD memahami dengan jelas prestasi semasa dan bersetuju dengan pelan tindakan yang perlu dilaksanakan?</label>
                        <div class="flex">
                            <div class="flex items-center px-20 mr-4 border border-gray-200 rounded ps-4 dark:border-gray-700">
                                <input id="bordered-radio-1" type="radio" value="" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="bordered-radio-1" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">YA</label>
                            </div>
                            <div class="flex items-center px-20 border border-gray-200 rounded ps-4 dark:border-gray-700">
                                <input checked id="bordered-radio-2" type="radio" value="" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="bordered-radio-2" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">TIDAK</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="my-8">
                    <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Lain-lain perkara (Jika ada) :</label>
                    <textarea id="punca" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>
                <hr>
                <div class="my-8">
                    <div class="mb-2">
                        <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Adakah penangguhan perlu dilakukan untuk PYD tersebut?</label>
                        <div class="flex">
                            <div class="flex items-center px-20 mr-4 border border-gray-200 rounded ps-4 dark:border-gray-700">
                                <input id="bordered-radio-1" type="radio" value="" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="bordered-radio-1" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">YA</label>
                            </div>
                            <div class="flex items-center px-20 border border-gray-200 rounded ps-4 dark:border-gray-700">
                                <input checked id="bordered-radio-2" type="radio" value="" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="bordered-radio-2" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">TIDAK</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center w-full my-4">
                        <label for="punca" class="block mb-2 mr-4 font-medium text-gray-900 text-md dark:text-white">Sila masukkan faktor penangguhan :</label>
                        <select id="small" class="flex-1 block mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                            <option selected>Sila Pilh</option>
                            <option value="1">Tiada bimbingan daripada Pejabat Negeri</option>
                            <option value="2">Masalah Kesihatan</option>
                            <option value="3">Tiada tunjuk ajar daripada penyelia ( nyatakan masalah dan keperluan tersebut )</option>
                        </select>
                    </div>
                    <div>
                        <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Ulasan :</label>
                        <textarea id="punca" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                    </div>
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
