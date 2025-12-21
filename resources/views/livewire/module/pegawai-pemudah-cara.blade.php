@php
    $nonPmcView = $perakuan && auth()->user()->USERID != $sessionSetting->pmc_id;
@endphp
<main x-data="{ showPrestasiKumulatif: @entangle('showPrestasiKumulatif'), showRekodPmgi: @entangle('showRekodPmgi') }">
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center mb-2">
                        <h3 class="text-xl font-bold text-gray-900">Ulasan Pegawai Pemudah Cara (PMC)</h3>
                    </div>

                    @if(!$perakuan)
                        <div class="p-6 mt-4 rounded-lg border shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex">
                                <div class="grid w-[60%] grid-cols-3 gap-4">
                                    <p class="flex items-center font-semibold">Nama Pegawai Yang Dinilai</p>
                                    <div class="block col-span-2 w-full">
                                        <x-input placeholder="Nama" wire:model="pydName" disabled />
                                    </div>
                                    <p class="flex items-center font-semibold">Jawatan</p>
                                    <div class="block col-span-2 w-full">
                                        <x-input placeholder="Jawatan" wire:model="pydPosition" disabled />
                                    </div>
                                    <p class="flex items-center font-semibold">Nombor Pekerja</p>
                                    <div class="block col-span-2 w-full">
                                        <x-input placeholder="Staff No" wire:model="pydStaffNo" disabled />
                                    </div>
                                    <p class="flex items-center font-semibold">Negeri/Cawangan</p>
                                    <div class="block col-span-2 w-full">
                                        <x-input placeholder="Negeri/Cawangan" wire:model="stateBranch" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if(!$perakuan)
                <div class="flex justify-between">
                    <div class="flex mt-8">
                        <button wire:click="togglePrestasiKumulatif" class="inline-flex items-center px-4 py-2.5 font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 focus:ring-teal-200 dark:focus:ring-teal-900 hover:bg-teal-800">
                            {{ $showPrestasiKumulatif ? 'Tutup' : 'Lihat' }} Prestasi Kumulatif
                        </button>
                        <button wire:click="toggleRekodPmgi" class="inline-flex items-center px-4 py-2.5 ml-4 font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 focus:ring-teal-200 dark:focus:ring-teal-900 hover:bg-teal-800">
                            {{ $showRekodPmgi ? 'Tutup' : 'Lihat' }} Rekod PMGi
                        </button>
                        @if ($buttonRekodPS)
                            <a href="{{ route('rekod-penilaian-semula', ['session_id' => $sessionId]) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 ml-4 font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 focus:ring-teal-200 dark:focus:ring-teal-900 hover:bg-teal-800">
                                Lihat Rekod Penilaian Semula
                            </a>
                        @endif
                    </div>
                    <div class="mt-8">
                        <button wire:click="cancelSessionConfirm" class="inline-flex items-center px-4 py-2.5 ml-4 font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800">
                            Batal Sesi PMGi
                        </button>
                    </div>
                </div>

                <!-- Prestasi Kumulatif -->
                <div x-show="showPrestasiKumulatif" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                    @if($showPrestasiKumulatif)
                        <livewire:module.prestasi.kumulatif :pmgiSession="true" :pmgiSessionId=$sessionId >
                    @endif
                </div>
                <!-- end prestasi kumulatif -->

                <!-- Rekod PMGi -->
                <div x-show="showRekodPmgi" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                    @if($showRekodPmgi)
                        <livewire:module.rekod-pmgi :pmgiSession="true" :pydIdOrigin=$pydId >
                    @endif
                </div>
                <!-- end Rekod PMGi -->
            @endif

            <div class="{{ $perakuan ? 'mt-4' : 'mt-4 w-[70%]' }} ">
                <div class="mt-4 mb-8">
                    <div class="mb-2">
                        <label for="punca" class="block mb-2 font-medium {{ $nonPmcView ? 'text-gray-700 opacity-60' : 'text-gray-900' }} text-md dark:text-white @error('fairFlag') text-red-700 @enderror">Adakah sesi ini telah dilaksanakan dengan adil dan saksama bagi kedua-dua belah pihak?</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('fairFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($nonPmcView)
                                    <input id="adilYa" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('fairFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="fairFlag" disabled>
                                    <label for="adilYa" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2 @error('fairFlag') text-red-700 @enderror">YA</label>
                                @else
                                    <input id="adilYa" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('fairFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="fairFlag">
                                    <label for="adilYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 @error('fairFlag') text-red-700 @enderror">YA</label>
                                @endif
                                <!-- <input id="adilYa" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('fairFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="fairFlag" {{ $nonPmcView ? 'disabled' : '' }}>
                                <label for="adilYa" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2 @error('fairFlag') text-red-700 @enderror">YA</label> -->
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('fairFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
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
                        @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
                            <x-textarea label="Sila tuliskan ulasan anda :" placeholder="Tuliskan ulasan anda" wire:model="fairComment" disabled />
                        @else
                            <x-textarea label="Sila tuliskan ulasan anda :" placeholder="Tuliskan ulasan anda" wire:model="fairComment" />
                        @endif
                    </div>
                </div>
                <hr>
                <div class="my-8">
                    <div class="mb-2">
                        <label for="pyd" class="block mb-2 font-medium @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id) text-gray-700 opacity-60 @else text-gray-900 @endif text-md dark:text-white @error('undrstdFlag') text-red-700 @enderror">PYD memahami dengan jelas prestasi semasa dan bersetuju dengan pelan tindakan yang perlu dilaksanakan?</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('undrstdFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
                                    <input id="pydYa" type="radio" value="1" name="bordered-radio2" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('undrstdFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="undrstdFlag" disabled>
                                    <label for="pydYa" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2 @error('undrstdFlag') text-red-700 @enderror">YA</label>
                                @else
                                    <input id="pydYa" type="radio" value="1" name="bordered-radio2" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('undrstdFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model="undrstdFlag">
                                    <label for="pydYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 @error('undrstdFlag') text-red-700 @enderror">YA</label>
                                @endif
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('undrstdFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
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
                    <div class="flex justify-between">
                        <label for="lain-lain" class="block mb-2 font-medium text-gray-900 text-md">Lain-lain perkara (jika ada) :</label>
                        <x-icon name="information-circle" class="w-6 h-6 cursor-pointer text-primary-600" wire:click="openInfo" />
                    </div>
                    @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
                        <x-textarea placeholder="Tuliskan pandangan anda" wire:model="others" disabled />
                    @else
                        <x-textarea placeholder="Tuliskan pandangan anda" wire:model="others" />
                    @endif
                </div>
                <hr>

                @if ($perakuan)
                    <div class="my-8">
                        <div class="mb-2">
                            <label for="syorKeluar" class="block mb-2 font-medium @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id) text-gray-700 opacity-60 @else text-gray-900 @endif text-md dark:text-white @error('exitFlag') text-red-700 @enderror">Disyorkan penilaian semula?</label>
                            <div class="flex">
                                <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('exitFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                    @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
                                        <input id="syorKeluarYa" type="radio" value="1" name="bordered-radio3" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('exitFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model.live="exitFlag" disabled>
                                        <label for="syorKeluarYa" class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2  @error('exitFlag') text-red-700 @enderror">YA</label>
                                    @else
                                        <input id="syorKeluarYa" type="radio" value="1" name="bordered-radio3" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500 @error('exitFlag') border-red-300 text-red-600 focus:ring-red-500 @enderror" wire:model.live="exitFlag">
                                        <label for="syorKeluarYa" class="w-full py-4 text-sm font-medium text-gray-900 ms-2  @error('exitFlag') text-red-700 @enderror">YA</label>
                                    @endif
                                </div>
                                <div class="flex items-center mr-4 border border-gray-200 rounded ps-4 @error('exitFlag') border-red-200 @enderror" style="padding-left: 2rem;padding-right: 2rem;">
                                    @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
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
                            <div class="flex items-center my-4 w-full">
                                @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
                                    <x-native-select label="Sila pilih jenis penangguhan :" placeholder="Sila Pilih Keputusan" :options="[
                                        ['name' => 'Kembali ke PMG-i (1)',  'id' => 1],
                                        ['name' => 'Kembali ke PMG-i (2)',  'id' => 2],
                                        ['name' => 'Kembali ke PMG-i (3)',  'id' => 3],
                                    ]" option-label="name" option-value="id" wire:model="exitTypeFlag" disabled />
                                @else
                                    <x-native-select label="Sila pilih jenis penangguhan :" placeholder="Sila Pilih Keputusan" :options="[
                                        ['name' => 'Kembali ke PMG-i (1)',  'id' => 1],
                                        ['name' => 'Kembali ke PMG-i (2)',  'id' => 2],
                                        ['name' => 'Kembali ke PMG-i (3)',  'id' => 3],
                                    ]" option-label="name" option-value="id" wire:model="exitTypeFlag" />
                                @endif
                            </div>
                            {{-- @error('exitTypeFlag')<p class="mt-2 text-sm text-negative-600">{{ $message }}</p>@enderror --}}
                        </div>
                        <div>
                            @if($perakuan && auth()->user()->USERID != $sessionSetting->pmc_id)
                                <x-textarea label="Ulasan :" wire:model="comment" disabled />
                            @else
                                <x-textarea label="Ulasan :" wire:model="comment" />
                            @endif
                            @error('comment')<p class="mt-2 text-sm text-negative-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                @endif

                <!-- ============================================================
                                LAMPIRAN 1, 2, 3 (PMC)
                ============================================================ -->
                @if(!$perakuan)
                    <!-- ******** Lampiran 1 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 1 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPmcView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file1" {{ $nonPmcView ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <!-- ******** Lampiran 2 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 2 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPmcView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file2" {{ $nonPmcView ? 'disabled' : '' }}>
                        </div>
                    </div>


                    <!-- ******** Lampiran 3 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 3 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPmcView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file3" {{ $nonPmcView ? 'disabled' : '' }}>
                        </div>
                    </div>
                    
                    <!-- SUBMIT BUTTON -->
                    <div class="flex justify-center">
                        <button class="px-4 py-2 bg-primary-700 text-white rounded-lg"
                                wire:click="submit">
                            Hantar
                        </button>
                    </div>
                @else
                    <!-- Lampiran 1 -->
                    @if($attachment)
                        @php
                            $fileExtension = pathinfo($attachment, PATHINFO_EXTENSION);
                        @endphp

                        <div class="mb-4">
                            <label class="font-semibold">Lampiran 1 :</label>
                            @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img class="mb-5 w-60" src="{{ $attachmentUrl }}" alt="Attachment Preview">
                            @elseif($fileExtension === 'pdf')
                                <button type="button" class="cursor-pointer text-blue-500 hover:underline" wire:click="toggleDetail('{{ $attachment }}')">
                                    {{ basename($attachment) }}
                                </button>
                            @else
                                <a href="{{ $attachmentUrl }}" target="_blank" class="cursor-pointer text-blue-500 hover:underline">
                                    {{ basename($attachment) }}
                                </a>
                            @endif
                        </div>
                    @endif
                    <div class="mt-2">
                        <label class="font-semibold">{{ $attachment ? 'Ganti' : 'Muat naik' }} Lampiran 1 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPmcView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file1" @disabled($nonPmcView ? true : false)>
                        </div>
                    </div>

                    <!-- Lampiran 2 -->
                    @if($attachment2)
                        @php
                            $fileExtension = pathinfo($attachment2, PATHINFO_EXTENSION);
                        @endphp

                        <div class="mb-4">
                            <label class="font-semibold">Lampiran 2 :</label>
                            @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img class="mb-5 w-60" src="{{ asset('storage/' . $attachment2) }}" alt="Attachment Preview">
                            @elseif($fileExtension === 'pdf')
                                <button type="button" class="cursor-pointer text-blue-500 hover:underline" wire:click="toggleDetail('{{ $attachment2 }}')">
                                    {{ basename($attachment2) }}
                                </button>
                            @else
                                <a href="{{ $attachmentUrl }}" target="_blank" class="cursor-pointer text-blue-500 hover:underline">
                                    {{ basename($attachment2) }}
                                </a>
                            @endif
                        </div>
                    @endif
                    <div class="mt-2">
                        <label class="font-semibold">{{ $attachment2 ? 'Ganti' : 'Muat naik' }} Lampiran 2 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPmcView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file2" @disabled($nonPmcView ? true : false)>
                        </div>
                    </div>

                    <!-- Lampiran 3 -->
                    @if($attachment3)
                        @php
                            $fileExtension = pathinfo($attachment3, PATHINFO_EXTENSION);
                        @endphp

                        <div class="mb-4">
                            <label class="font-semibold">Lampiran 3 :</label>
                            @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img class="mb-5 w-60" src="{{ asset('storage/' . $attachment) }}" alt="Attachment Preview">
                            @elseif($fileExtension === 'pdf')
                                <button type="button" class="cursor-pointer text-blue-500 hover:underline" wire:click="toggleDetail('{{ $attachment3 }}')">
                                    {{ basename($attachment3) }}
                                </button>
                            @else
                                <a href="{{ $attachmentUrl }}" target="_blank" class="cursor-pointer text-blue-500 hover:underline">
                                    {{ basename($attachment3) }}
                                </a>
                            @endif
                        </div>
                    @endif
                    <div class="mt-2">
                        <label class="font-semibold">{{ $attachment3 ? 'Ganti' : 'Muat naik' }} Lampiran 3 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPmcView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file3" @disabled($nonPmcView ? true : false)>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @if($perakuan && auth()->user()->USERID == $sessionSetting->pmc_id)
            <div class="space-y-2">
                <div>
                    <h2 class="text-sm px-2 font-italic text-gray-900">Sila klik Kemaskini terlebih dahulu sebelum menghantar keputusan anda.</h2>
                </div>
                <div>
                    <button class="inline-flex items-center px-4 py-2 ml-4 font-medium text-center text-white bg-indigo-700 rounded-lg cursor-pointer focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 hover:bg-indigo-800" wire:click="updates">
                        Kemaskini
                    </button>
                </div>
            </div>
        @endif
    </div>

    <x-modal wire:model="infoModal" blur align="center" max-width="6xl">
        <x-card title="Info Pegawai Mudah Cara">
            <div class="flex justify-center items-center">
                <img class="w-90% h-90%" src="{{ asset('storage/' . $savedFile->filename) }}" alt="Tiada Fail">
            </div>
        </x-card>
    </x-modal>
    
    <!-- attachment modal -->
    <x-modal.card blur align="center" max-width="7xl" hide-close=false wire:model="attachmentModal">
        @if($attachmentUrl)
        <iframe src="{{ $attachmentUrl }}" frameborder="0" width="100%" height="700px"></iframe>
        @endif
    </x-modal.card>

    <!-- cancel PMGI session modal -->
    <x-modal wire:model="cancelSessionModal" blur align="center" max-width="4xl">
        <x-card title="Batal Sesi PMGI">
            <div class="grid gap-y-4">
                <label class="block text-gray-600">Pilih sebab pembatalan:</label>
                <select id="small" class="flex-1 block w-full p-1 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" wire:model="reasonCancel">
                    <option value="" disabled>Sila Pilih</option>
                    @foreach ($reasonList as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col sm:flex-row justify-center gap-3 pt-2">
                <button type="button" wire:click="close"
                        class="w-full sm:w-auto py-2 px-4 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 text-sm sm:text-base">
                    Cancel
                </button>
                <button type="button" wire:click="confirmCancel"
                        class="w-full sm:w-auto py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm sm:text-base">
                    Simpan
                </button>
            </div>
        </x-card>
    </x-modal>

    @script
        <script>
            let format_sessionId = '{{ $sessionId }}'.replaceAll('/', '-');

            window.Echo.private(`pmgi.session.${format_sessionId}`)
                .listen('.pmgi.session.updated', (e) => {
                // Ask Livewire to refresh or set flags
                Livewire.dispatch('pmgi-session-updated', { role: e.role, payload: e.payload });
            });
        </script>
    @endscript
    
</main>
