@php
    $nonPymView = $perakuan && auth()->user()->USERID != $sessionSetting->pym_id;
@endphp

<main x-data="{ showPrestasiKumulatif: @entangle('showPrestasiKumulatif') }">
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center mb-2">
                        <h3 class="mb-2 text-xl font-bold text-gray-900">Ulasan Pegawai Yang Menilai (PYM)</h3>
                    </div>

                    @if(!$perakuan)
                        <span class="text-base font-normal text-gray-500">Pelan Tindakan Yang Dipersetujui Bersama</span>
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
                    </div>
                    @if ($pmgiLevel !== 'PM3')
                        <div class="mt-8">
                            <button wire:click="cancelSessionConfirm" class="inline-flex items-center px-4 py-2.5 ml-4 font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800">
                                Batal Sesi PMGi
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Prestasi Kumulatif --}}
                <div x-show="showPrestasiKumulatif" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                    @if($showPrestasiKumulatif)
                        <livewire:module.prestasi.kumulatif :pmgiSession="true" :pmgiSessionId=$sessionId >
                    @endif
                </div>
                {{-- end prestasi kumulatif --}}

                {{-- Rekod PMGi --}}
                <div x-show="showRekodPmgi" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                    @if($showRekodPmgi)
                        <livewire:module.rekod-pmgi :pmgiSession="true" :pydIdOrigin=$pydId >
                    @endif
                </div>
                {{-- end Rekod PMGi --}}
            @endif

            <div class="{{ $perakuan ? 'mt-4' : 'mt-4 w-[70%]' }} ">
                <div class="my-4">
                    @if($perakuan && auth()->user()->USERID != $sessionSetting->pym_id)
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

                    @if($perakuan && auth()->user()->USERID != $sessionSetting->pym_id)
                        <x-textarea placeholder="Tuliskan pelan tindakan anda" wire:model="actionPlan" disabled />
                    @else
                        <x-textarea placeholder="Tuliskan pelan tindakan anda" wire:model="actionPlan" />
                    @endif
                </div>

                {{-- ===================== LAMPIRAN 1, 2, 3 (PYM) ===================== --}}
                @if(!$perakuan)
                    <!-- ******** Lampiran 1 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 1 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPymView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file1" {{ $nonPymView ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <!-- ******** Lampiran 2 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 2 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPymView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file2" {{ $nonPymView ? 'disabled' : '' }}>
                        </div>
                    </div>


                    <!-- ******** Lampiran 3 ******** -->
                    <div class="mb-4">
                        <label class="font-semibold">Lampiran 3 (Jika berkaitan) :</label>
                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="mb-4">
                            <!-- File Input -->
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPymView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file3" {{ $nonPymView ? 'disabled' : '' }}>
                        </div>
                    </div>
                    
                    <!-- SUBMIT BUTTON -->
                    <button class="px-4 py-2 bg-primary-700 text-white rounded-lg"
                            wire:click="submit">
                        Hantar
                    </button>
                @else
                    <!-- ============================================================
                                LAMPIRAN 1, 2, 3 (PYM)
                    ============================================================ -->
                    <!-- Lampiran 1 -->
                    @if($attachment)
                        @php
                            $fileExtension = pathinfo($attachment, PATHINFO_EXTENSION);
                        @endphp

                        <div class="mb-4">
                            <label class="font-semibold">Lampiran 1 :</label>
                            @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                <button type="button" class="cursor-pointer text-blue-500 hover:underline" wire:click="toggleDetail('{{ $attachment }}')">
                                    <img class="mb-5 w-60" src="{{ asset('storage/' . $attachment) }}" alt="Attachment Preview">
                                </button>
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
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPymView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file1" @disabled($nonPymView ? true : false)>
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
                                <button type="button" class="cursor-pointer text-blue-500 hover:underline" wire:click="toggleDetail('{{ $attachment2 }}')">
                                    <img class="mb-5 w-60" src="{{ asset('storage/' . $attachment2) }}" alt="Attachment Preview">
                                </button>
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
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPymView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file2" @disabled($nonPymView ? true : false)>
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
                                <button type="button" class="cursor-pointer text-blue-500 hover:underline" wire:click="toggleDetail('{{ $attachment3 }}')">
                                    <img class="mb-5 w-60" src="{{ asset('storage/' . $attachment3) }}" alt="Attachment Preview">
                                </button>
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
                            <input class="block mb-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none dark:bg-gray-700 {{ $nonPymView ? 'cursor-not-allowed' : ''}}" id="default_size" type="file" wire:model="file3" @disabled($nonPymView ? true : false)>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @if($perakuan && auth()->user()->USERID == $sessionSetting->pym_id)
            <div class="space-y-2">
                <div>
                    <h2 class="text-sm px-2 font-italic text-gray-900">Sekiranya anda melakukan sebarang perubahan, sila klik Kemaskini terlebih dahulu sebelum menghantar keputusan anda.</h2>
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
        <x-card title="Info Pegawai Yang Menilai">
            <div class="flex justify-center items-center">
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

    {{-- cancel PMGI session modal --}}
    <x-modal wire:model="cancelSessionModal" blur align="center" max-width="4xl">
        <x-card title="Batal Sesi PMGI">
            <div class="grid gap-y-4">
                <label class="block text-gray-600">Pilih sebab pembatalan:</label>
                <select id="small" class="flex-1 block w-full p-1 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" wire:model="reasonCancel">
                    <option value="" disabled>Sila Pilih</option>
                    @foreach ($reasonList as $id => $name)
                        <option value="{{ $id }}">{{ $id }} {{ $name }}</option>
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