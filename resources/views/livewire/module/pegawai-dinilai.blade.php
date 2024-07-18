<main x-data="{ showPrestasiKumulatif: @entangle('showPrestasiKumulatif') }">
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center mb-2 ">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 ">Ulasan Pegawai Yang Dinilai (PYD)</h3>
                        @if($edit)
                        <a href="{{ route('pegawai-dinilai') }}" class="inline-flex items-center px-4 py-2 ml-4 font-medium text-center text-white bg-indigo-700 rounded-lg focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 hover:bg-indigo-800">
                            Kemaskini
                        </a>
                        @endif
                    </div>

                    @if($search)
                        <span class="text-base font-normal text-gray-500 ">Ulasan Prestasi Semasa Dan Keperluan Penambahbaikan Oleh Pegawai Yang Dinilai (PYD)</span>
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
                <div
                    x-show="showPrestasiKumulatif"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                >
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
                <div class="flex items-center w-full px-4 py-2 rounded-lg bg-lime-300">
                    <h3 class="mr-4 text-lg font-medium text-gray-900">Masalah yang dihadapi :</h3>
                    <x-select class="flex-1 block w-24 mr-4 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Sila Pilih"
                        multiselect
                        :options="$problem"
                        option-label="description"
                        option-value="id"
                        wire:model="model"
                    />
                    <x-icon solid  name="information-circle" class="w-6 h-6 bg-white cursor-pointer rounded-xl text-primary-500" wire:click="openInfo" />
                </div>

                <div class="my-4">
                    <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Nyatakan punca bagi masalah tersebut :</label>
                    <textarea id="punca" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="pelan" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Pelan tindakan untuk meningkatkan prestasi :</label>
                    <textarea id="pelan" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="ulasan" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Ulasan (Jika ada) :</label>
                    <textarea id="ulasan" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
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

    <x-modal wire:model="infoModal" blur align="center" max-width="6xl">
        <x-card title="Info Pegawai Yang Dinilai">
            <div class="flex items-center justify-center">
                <img class="w-90% h-90%" src="{{ asset('storage/' . $savedFile->filename) }}" alt="Tiada Fail">
            </div>
        </x-card>
    </x-modal>

</main>
