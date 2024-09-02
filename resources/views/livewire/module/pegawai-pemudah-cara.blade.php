<main x-data="{ showPrestasiKumulatif: @entangle('showPrestasiKumulatif') }">
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center mb-2 ">
                        <h3 class="text-xl font-bold text-gray-900 ">Ulasan Pegawai Pemudah Cara (PMC)</h3>
                        @if($perakuan && auth()->user()->userid == $sessionSetting->pmc_id)
                        <a href="{{ route('pegawai-pemudah-cara') }}" class="inline-flex items-center px-4 py-2 ml-4 font-medium text-center text-white bg-indigo-700 rounded-lg focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 hover:bg-indigo-800" wire:click="updates">
                            Kemaskini
                        </a>
                        @endif
                    </div>

                    @if(!$perakuan)
                        <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex">
                                <div class="grid w-[60%] grid-cols-3 gap-4">
                                    <p class="flex items-center font-semibold">Nama Pegawai Yang Dinilai</p>
                                    <div class="block w-full col-span-2">
                                        <x-input placeholder="Nama" wire:model="pydName" disabled />
                                    </div>
                                    <p class="flex items-center font-semibold">Jawatan</p>
                                    <div class="block w-full col-span-2">
                                        <x-input placeholder="Jawatan" wire:model="pydPosition" disabled />
                                    </div>
                                    <p class="flex items-center font-semibold">Nombor Pekerja</p>
                                    <div class="block w-full col-span-2">
                                        <x-input placeholder="Staff No" wire:model="pydStaffNo" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if(!$perakuan)
                <div class="flex mt-8">
                    <button wire:click="togglePrestasiKumulatif" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 focus:ring-teal-200 dark:focus:ring-teal-900 hover:bg-teal-800">
                        {{ $showPrestasiKumulatif ? 'Tutup' : 'Lihat' }} Prestasi Kumulatif
                    </button>
                </div>

                {{-- Prestasi Kumulatif --}}
                <div x-show="showPrestasiKumulatif" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                    @if($showPrestasiKumulatif)
                        <livewire:module.prestasi.kumulatif :pmgiSession="true" :pmgiSessionId=$sessionId >
                    @endif
                </div>
                {{-- end prestasi kumulatif --}}
            @endif

            @if($perakuan)
            <div class="mt-4">
            @else
            <div class="mt-4 w-[70%]">
            @endif
                <div class="mt-4 mb-8">
                    <div class="mb-2">
                        <label for="punca" class="block mb-2 font-medium @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id) text-gray-700 opacity-60 @else text-gray-900 @endif text-md dark:text-white @error('fairFlag') text-red-700 @enderror">Adakah sesi ini telah dilaksanakan dengan adil dan saksama bagi kedua-dua belah pihak?</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('fairFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                                    <input id="adilYa" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('fairFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="fairFlag" disabled>
                                    <label for="adilYa" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2 @error('fairFlag') text-red-700 @enderror">YA</label>
                                @else
                                    <input id="adilYa" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('fairFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="fairFlag">
                                    <label for="adilYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 @error('fairFlag') text-red-700 @enderror">YA</label>
                                @endif
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('fairFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                                    <input checked id="adilTidak" type="radio" value="2" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('fairFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="fairFlag" disabled>
                                    <label for="adilTidak" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2  @error('fairFlag') text-red-700 @enderror">TIDAK</label>
                                @else
                                    <input checked id="adilTidak" type="radio" value="2" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('fairFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="fairFlag">
                                    <label for="adilTidak" class="w-full py-4 text-sm font-medium text-gray-900 ms-2  @error('fairFlag') text-red-700 @enderror">TIDAK</label>
                                @endif
                            </div>
                        </div>
                        @error('fairFlag')<p class="mt-2 text-sm text-negative-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                            <x-textarea label="Sila tuliskan ulasan anda :" placeholder="Tuliskan ulasan anda" wire:model="fairComment" disabled />
                        @else
                            <x-textarea label="Sila tuliskan ulasan anda :" placeholder="Tuliskan ulasan anda" wire:model="fairComment" />
                        @endif
                    </div>
                </div>
                <hr>
                <div class="my-8">
                    <div class="mb-2">
                        <label for="pyd" class="block mb-2 font-medium @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id) text-gray-700 opacity-60 @else text-gray-900 @endif text-md dark:text-white @error('undrstdFlag') text-red-700 @enderror">PYD memahami dengan jelas prestasi semasa dan bersetuju dengan pelan tindakan yang perlu dilaksanakan?</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('undrstdFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                                    <input id="pydYa" type="radio" value="1" name="bordered-radio2" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('undrstdFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="undrstdFlag" disabled>
                                    <label for="pydYa" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2 @error('undrstdFlag') text-red-700 @enderror">YA</label>
                                @else
                                    <input id="pydYa" type="radio" value="1" name="bordered-radio2" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('undrstdFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="undrstdFlag">
                                    <label for="pydYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 @error('undrstdFlag') text-red-700 @enderror">YA</label>
                                @endif
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('undrstdFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                                    <input checked id="pydTidak" type="radio" value="2" name="bordered-radio2" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('undrstdFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="undrstdFlag" disabled>
                                    <label for="pydTidak" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2  @error('undrstdFlag') text-red-700 @enderror">TIDAK</label>
                                @else
                                    <input checked id="pydTidak" type="radio" value="2" name="bordered-radio2" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('undrstdFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="undrstdFlag">
                                    <label for="pydTidak" class="w-full py-4 text-sm font-medium text-gray-900 ms-2  @error('undrstdFlag') text-red-700 @enderror">TIDAK</label>
                                @endif
                            </div>
                        </div>
                        @error('undrstdFlag')<p class="mt-2 text-sm text-negative-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <hr>
                <div class="my-8">
                    @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                        <x-textarea label="Lain-lain perkara (Jika ada) :" wire:model="others" disabled />
                    @else
                        <x-textarea label="Lain-lain perkara (Jika ada) :" wire:model="others" />
                    @endif
                </div>
                <hr>
                <div class="my-8">
                    <div class="mb-2">
                        <label for="syorKeluar" class="block mb-2 font-medium @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id) text-gray-700 opacity-60 @else text-gray-900 @endif text-md dark:text-white @error('exitFlag') text-red-700 @enderror">Disyorkan keluar senarai?</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('exitFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                                    <input id="syorKeluarYa" type="radio" value="1" name="bordered-radio3" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('exitFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model.live="exitFlag" disabled>
                                    <label for="syorKeluarYa" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2  @error('exitFlag') text-red-700 @enderror">YA</label>
                                @else
                                    <input id="syorKeluarYa" type="radio" value="1" name="bordered-radio3" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('exitFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model.live="exitFlag">
                                    <label for="syorKeluarYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2  @error('exitFlag') text-red-700 @enderror">YA</label>
                                @endif
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('exitFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                                    <input id="syorKeluarTidak" type="radio" value="0" name="bordered-radio3" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('exitFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model.live="exitFlag" disabled>
                                    <label for="syorKeluarTidak" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2  @error('exitFlag') text-red-700 @enderror">TIDAK</label>
                                @else
                                    <input id="syorKeluarTidak" type="radio" value="0" name="bordered-radio3" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('exitFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model.live="exitFlag">
                                    <label for="syorKeluarTidak" class="w-full py-4 text-sm font-medium text-gray-900 ms-2  @error('exitFlag') text-red-700 @enderror">TIDAK</label>
                                @endif
                            </div>
                        </div>
                        @error('exitFlag')<p class="mt-2 text-sm text-negative-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="{{ $exitFlag == 1 ? 'block' : 'hidden'}}">
                        <div class="flex items-center w-full my-4">
                            @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                                <x-native-select label="Sila pilih jenis keluar senarai :" placeholder="Sila Pilih" :options="[
                                    ['name' => 'Tanpa Syarat',  'id' => 1],
                                    ['name' => 'Bersyarat', 'id' => 2],
                                    ['name' => 'Penangguhan',   'id' => 3],
                                ]" option-label="name" option-value="id" wire:model="exitTypeFlag" disabled />
                            @else
                                <x-native-select label="Sila pilih jenis keluar senarai :" placeholder="Sila Pilih" :options="[
                                    ['name' => 'Tanpa Syarat',  'id' => 1],
                                    ['name' => 'Bersyarat', 'id' => 2],
                                    ['name' => 'Penangguhan',   'id' => 3],
                                ]" option-label="name" option-value="id" wire:model="exitTypeFlag" />
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($perakuan && auth()->user()->userid != $sessionSetting->pmc_id)
                            <x-textarea label="Ulasan :" wire:model="comment" disabled />
                        @else
                            <x-textarea label="Ulasan :" wire:model="comment" />
                        @endif
                    </div>
                </div>

                @if($perakuan)
                <img class="mb-5 w-60" src="{{ asset('storage/' . $attachment) }}" alt="Logo">
                @endif

                @if(!$perakuan)
                    <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                        <!-- File Input -->
                        <label for="muatnaik" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Muat Naik Fail (Jika berkaitan) :</label>
                        <input class="block w-full mb-5 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700" id="default_size" type="file" wire:model="file">

                        <!-- Progress Bar -->
                        <div x-show="uploading">
                            <progress max="100" x-bind:value="progress"></progress>
                        </div>
                    </div>

                    @if($file)
                    <img class="mb-5 w-60" src="{{ $file->temporaryUrl() }}">
                    @endif

                    <div class="flex">
                        <button wire:click="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                            Hantar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
