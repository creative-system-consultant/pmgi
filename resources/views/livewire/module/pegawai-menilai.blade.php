<main x-data="{ showPrestasiKumulatif: @entangle('showPrestasiKumulatif') }">
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center mb-2 ">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 ">Ulasan Pegawai Yang Menilai (PYM)</h3>
                        @if($perakuan && auth()->user()->userid == $sessionSetting->pym_id)
                            <button class="inline-flex items-center px-4 py-2 ml-4 font-medium text-center text-white bg-indigo-700 rounded-lg cursor-pointer focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 hover:bg-indigo-800" wire:click="updates">
                                Kemaskini
                            </button>
                        @endif
                    </div>

                    @if(!$perakuan)
                        <span class="text-base font-normal text-gray-500 ">Pelan Tindakan Yang Dipersetujui Bersama</span>
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
                <div class="my-4">
                    @if($perakuan && auth()->user()->userid != $sessionSetting->pym_id)
                        <x-textarea label="Ulasan Pegawai Yang Menilai (PYM) :" placeholder="Tuliskan ulasan anda" wire:model="comment" disabled />
                    @else
                        <x-textarea label="Ulasan Pegawai Yang Menilai (PYM) :" placeholder="Tuliskan ulasan anda" wire:model="comment" />
                    @endif
                </div>
                <div class="mb-4">
                    <div class="flex justify-between">
                        <label for="pelan" class="block mb-2 font-medium text-gray-900 text-md">Pelan tindakan untuk meningkatkan prestasi :</label>
                        <x-icon name="information-circle" class="w-6 h-6 cursor-pointer text-primary-600" wire:click="openInfo" />
                    </div>

                    @if($perakuan && auth()->user()->userid != $sessionSetting->pym_id)
                        <x-textarea placeholder="Tuliskan pelan tindakan anda" wire:model="actionPlan" disabled />
                    @else
                        <x-textarea placeholder="Tuliskan pelan tindakan anda" wire:model="actionPlan" />
                    @endif
                </div>

                @if($perakuan)
                    @if($attachment)
                        @php
                            $fileExtension = pathinfo($attachment, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img class="mb-5 w-60" src="{{ asset('storage/' . $attachment) }}" alt="Attachment Preview">
                        @elseif($fileExtension === 'pdf')
                            <button type="button" class="text-blue-500 hover:underline" wire:click="toggleDetail">
                                {{ basename($attachment) }}
                            </button>
                        @elseif($fileExtension === 'docx')
                            <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="text-blue-500 hover:underline">
                                {{ basename($attachment) }}
                            </a>
                        @endif
                    @endif
                @endif

                @if(!$perakuan)
                    <div
                        x-data="{ uploading: false, progress: 0 }"
                        x-on:livewire-upload-start="uploading = true"
                        x-on:livewire-upload-finish="uploading = false"
                        x-on:livewire-upload-cancel="uploading = false"
                        x-on:livewire-upload-error="uploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                        class="mb-4"
                    >
                        <!-- File Input -->
                        <label for="muatnaik" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Muat Naik Fail (Jika berkaitan) :</label>
                        <input class="block w-full mb-5 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700" id="default_size" type="file" wire:model="file">

                        <!-- Progress Bar -->
                        <div x-show="uploading">
                            <progress max="100" x-bind:value="progress"></progress>
                        </div>
                    </div>

                    @if($file)
                        @php
                            $fileExtension = $file->getClientOriginalExtension();
                        @endphp

                        @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img class="mb-5 w-60" src="{{ $file->temporaryUrl() }}" alt="Attachment Preview">
                        @elseif($fileExtension === 'pdf')
                            <button type="button" class="text-blue-500 hover:underline" wire:click="toggleDetail">
                                {{ $file->getClientOriginalName() }}
                            </button>
                        @else
                            <a href="{{ $file->temporaryUrl() }}" target="_blank" class="text-blue-500 hover:underline">
                                {{ $file->getClientOriginalName() }}
                            </a>
                        @endif
                    @endif

                    <div class="flex mt-4">
                        <button wire:click="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                            Hantar
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-modal wire:model="infoModal" blur align="center" max-width="6xl">
        <x-card title="Info Pegawai Yang Menilai">
            <div class="flex items-center justify-center">
                <img class="w-90% h-90%" src="{{ asset('storage/' . $savedFile->filename) }}" alt="Tiada Fail">
            </div>
        </x-card>
    </x-modal>

    {{-- attachment modal --}}
    <x-modal.card blur align="center" max-width="7xl" hide-close=false wire:model="attachmentModal">
        @if($attachmentUrl)
        <iframe src="{{ $attachmentUrl }}" frameborder="0" width="100%" height="700px"></iframe>
        @endif
    </x-modal.card>

</main>
