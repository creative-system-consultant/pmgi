@php
    $nonPydView = $perakuan && auth()->user()->USERID != $sessionSetting->pyd_id;
@endphp

<main x-data="{ showPrestasiKumulatif: @entangle('showPrestasiKumulatif'), showRekodPmgi: @entangle('showRekodPmgi') }">
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center mb-2">
                        <h3 class="mb-2 text-xl font-bold text-gray-900">Ulasan Pegawai Yang Dinilai (PYD)</h3>
                        @if($perakuan && auth()->user()->USERID == $sessionSetting->pyd_id)
                            <button class="inline-flex items-center px-4 py-2 ml-4 font-medium text-center text-white bg-indigo-700 rounded-lg cursor-pointer focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-900 hover:bg-indigo-800" wire:click="updates">
                                Kemaskini
                            </button>
                        @endif
                    </div>

                    <!-- FORM INFO PYD -->
                    @if(!$perakuan)
                        <span class="text-base font-normal text-gray-500">Ulasan Prestasi Semasa Dan Keperluan Penambahbaikan Oleh Pegawai Yang Dinilai (PYD)</span>
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
                <div class="flex mt-8">
                    <button wire:click="togglePrestasiKumulatif" class="inline-flex items-center px-4 py-2.5 font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 focus:ring-teal-200 dark:focus:ring-teal-900 hover:bg-teal-800">
                        {{ $showPrestasiKumulatif ? 'Tutup' : 'Lihat' }} Prestasi Kumulatif
                    </button>
                    <button wire:click="toggleRekodPmgi" class="inline-flex items-center px-4 py-2.5 ml-4 font-medium text-center text-white bg-teal-700 rounded-lg focus:ring-4 focus:ring-teal-200 dark:focus:ring-teal-900 hover:bg-teal-800">
                        {{ $showRekodPmgi ? 'Tutup' : 'Lihat' }} Rekod PMGi
                    </button>
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
                <!-- ===================== MASALAH YG DIHADAPI ===================== -->
                <div class="flex items-center bg-lime-300 p-3 rounded-lg">
                    <h3 class="mr-4 font-medium">Masalah yang dihadapi :</h3>

                    @if($perakuan && auth()->user()->USERID != $sessionSetting->pyd_id)
                        <x-select class="flex-1"
                            :options="$problemSelection"
                            option-label="description"
                            option-value="id"
                            wire:model="problem"
                            disabled />
                    @else
                        <x-select class="flex-1"
                            :options="$problemSelection"
                            option-label="description"
                            option-value="id"
                            wire:model="problem" />
                    @endif

                    <x-icon solid name="information-circle" class="ml-2 w-6 h-6 cursor-pointer" wire:click="openInfo" />
                </div>


                <!-- ===================== PUNCA MASALAH ===================== -->
                <div class="my-4">
                    <x-textarea label="Nyatakan punca bagi masalah tersebut :"
                                wire:model="reason"
                                :disabled="$perakuan && auth()->user()->USERID != $sessionSetting->pyd_id" />
                </div>


                <!-- ===================== PELAN TINDAKAN ===================== -->
                <div class="mb-4">
                    <x-textarea label="Pelan tindakan untuk meningkatkan prestasi :"
                                wire:model="actionPlan"
                                :disabled="$perakuan && auth()->user()->USERID != $sessionSetting->pyd_id" />
                </div>


                <!-- ===================== ULASAN ===================== -->
                <div class="mb-6">
                    <x-textarea label="Ulasan (Jika ada) :"
                                wire:model="comment"
                                :disabled="$perakuan && auth()->user()->USERID != $sessionSetting->pyd_id" />
                </div>


                <!-- ============================================================
                                LAMPIRAN 1, 2, 3
                ============================================================ -->
                @if(!$perakuan)
                    <!-- ******** Lampiran 1 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 1 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPydView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file1" {{ $nonPydView ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <!-- ******** Lampiran 2 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 2 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPydView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file2" {{ $nonPydView ? 'disabled' : '' }}>
                        </div>
                    </div>


                    <!-- ******** Lampiran 3 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 3 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPydView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file3" {{ $nonPydView ? 'disabled' : '' }}>
                        </div>
                    </div>
                    
                    <!-- SUBMIT BUTTON -->
                    <button class="px-4 py-2 bg-primary-700 text-white rounded-lg"
                            wire:click="submit">
                        Hantar
                    </button>
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

                    <!-- Lampiran 2 -->
                    @if($attachment2)
                        @php
                            $fileExtension = pathinfo($attachment2, PATHINFO_EXTENSION);
                        @endphp

                        <div class="mb-4">
                            <label class="font-semibold">Lampiran 2 :</label>
                            @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'pdf']))
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
                @endif
            </div>

            <x-modal wire:model="infoModal" blur align="center" max-width="6xl">
                <x-card title="Info Pegawai Yang Dinilai">
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
        </div>
    </div>   

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
