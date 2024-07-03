<main x-data="{ showPrestasiKumulatif: @entangle('showPrestasiKumulatif') }">
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center mb-2 ">
                        <h3 class="text-xl font-bold text-gray-900 ">Ulasan Pegawai Pemudah Cara (PMC)</h3>
                        @if($edit)
                        <a href="{{ route('pegawai-pemudah-cara') }}" class="inline-flex items-center px-4 py-2 ml-4 font-medium text-center text-white bg-indigo-700 rounded-lg focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 hover:bg-indigo-800">
                            Kemaskini
                        </a>
                        @endif
                    </div>
                    @if($search)
                        <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex">
                                <div class="grid w-[60%] grid-cols-3 gap-4">
                                    <p class="flex items-center font-semibold">Nama Pegawai Yang Dinilai</p>
                                    <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 ">
                                    <p class="flex items-center font-semibold">Jawatan</p>
                                    <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 ">
                                    <p class="flex items-center font-semibold">Nombor Pekerja</p>
                                    <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 ">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($search)
                <div class="flex mt-8">
                    <button wire:click="togglePrestasiKumulatif" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 focus:ring-teal-200 dark:focus:ring-teal-900 hover:bg-teal-800">
                        {{ $showPrestasiKumulatif ? 'Tutup' : 'Lihat' }} Prestasi Kumulatif
                    </button>
                </div>

                {{-- Prestasi Kumulatif --}}
                <div x-show="showPrestasiKumulatif" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                    @if($showPrestasiKumulatif)
                        <livewire:module.prestasi.kumulatif :search="false">
                    @endif
                </div>
                {{-- end prestasi kumulatif --}}
            @endif

            @if($search == false)
            <div class="mt-4">
            @else
            <div class="mt-4 w-[70%]">
            @endif
                <div class="mt-4 mb-8">
                    <div class="mb-2">
                        <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Adakah sesi ini telah dilaksanakan dengan adil dan saksama bagi kedua-dua belah pihak?</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input id="pmcYa" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pmcYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">YA</label>
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input checked id="pmcTidak" type="radio" value="2" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pmcTidak" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">TIDAK</label>
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
                        <label for="pyd" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">PYD memahami dengan jelas prestasi semasa dan bersetuju dengan pelan tindakan yang perlu dilaksanakan?</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input id="pydYa" type="radio" value="" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pydYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">YA</label>
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input checked id="pydTidak" type="radio" value="" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pydTidak" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">TIDAK</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="my-8">
                    <label for="lainPerkara" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Lain-lain perkara (Jika ada) :</label>
                    <textarea id="lainPerkara" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>
                <hr>
                <div class="my-8">
                    <div class="mb-2">
                        <label for="syorKeluar" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Disyorkan keluar senarai?</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input id="syorKeluarYa" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500" wire:model.live="syorKeluar">
                                <label for="syorKeluarYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">YA</label>
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input checked id="syorKeluarTidak" type="radio" value="0" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500" wire:model.live="syorKeluar">
                                <label for="syorKeluarTidak" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">TIDAK</label>
                            </div>
                        </div>
                    </div>
                    <div class="{{ $syorKeluar == 1 ? 'block' : 'hidden'}}">
                        <div class="flex items-center w-full my-4">
                            <label for="jenisKeluar" class="block mr-4 font-medium text-gray-900 text-md dark:text-white">Sila pilih jenis keluar senarai :</label>
                            <select id="small" class="flex-1 block mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                <option selected>Sila Pilih</option>
                                <option value="1">Tanpa Syarat</option>
                                <option value="2">Bersyarat</option>
                                <option value="3">Penangguhan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Ulasan :</label>
                        <textarea id="punca" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                    </div>
                </div>
                @if($search)
                    <div class="mb-4">
                        <label for="muatnaik" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Muat Naik Fail (Jika berkaitan) :</label>
                        <input class="block w-full mb-5 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700" id="default_size" type="file">
                    </div>
                    <div class="flex">
                        <button type="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                            Hantar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
