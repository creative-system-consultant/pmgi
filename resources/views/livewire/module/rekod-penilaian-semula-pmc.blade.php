<div x-data="{
        tab: 'PM1',
        pmgiData: {{ json_encode($previousRecords) }},
        activePmgi: {},
        pmgiSessionIds: { PM1: null, PM2: null, PM3: null },

        // Function to update the activePmgi when the tab changes
        setActivePmgi(seq) {
            this.tab = seq;
            this.activePmgi = this.pmgiData.find(pmgi => pmgi.seq === seq) || {};
            this.pmgiSessionIds[seq] = this.activePmgi.session_id;
        },

        // Initialize the first tab's data when the component is mounted
        init() {
            this.pmgiData.forEach(pmgi => {
                this.pmgiSessionIds[pmgi.seq] = pmgi.session_id;
            });
            // Default to the first PMGi on mount
            if (this.pmgiData.length > 0) {
                this.setActivePmgi(this.pmgiData[0].seq);
            }
        }
    }" x-init="init()">

    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex items-center mb-2">
                        <h3 class="text-xl font-bold text-gray-900">Rekod Ulasan Pegawai Pemudah Cara (PMC)</h3>
                    </div>

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
                </div>
            </div>

            @if($previousRecords && $previousRecords->isNotEmpty())
                <!-- Tab List -->
                <div class="flex mt-8">
                    <ul class="flex flex-wrap mb-4 text-sm font-medium text-center text-gray-500">
                        @foreach ($previousRecords as $pmgi)
                        <li class="me-2">
                            <div @click="setActivePmgi('{{ $pmgi['seq'] }}')" :class="{ 'bg-primary-600 text-white': tab === '{{ $pmgi['seq'] }}', 'hover:text-gray-900 hover:bg-gray-100': tab !== '{{ $pmgi['seq'] }}' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                                {{ 'PMGi 3 (' . $pmgi['date_session'] . ')' }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- PMC Feedback Content -->
                <div class="mt-4">
                    <div class="mt-4 mb-8">
                        <div class="mb-2">
                            <label class="block mb-2 font-medium text-gray-900 text-md">Adakah sesi ini telah dilaksanakan dengan adil dan saksama bagi kedua-dua belah pihak?</label>
                            <div class="flex">
                                <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                    <input type="radio" value="1" x-model="activePmgi.fair_flag" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600" disabled>
                                    <label class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2">YA</label>
                                </div>
                                <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                    <input type="radio" value="2" x-model="activePmgi.fair_flag" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600" disabled>
                                    <label class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2">TIDAK</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-textarea label="Ulasan anda :" x-model="activePmgi.fair_comments" disabled />
                        </div>
                    </div>
                    <hr>
                    
                    <div class="my-8">
                        <div class="mb-2">
                            <label class="block mb-2 font-medium text-gray-900 text-md">PYD memahami dengan jelas prestasi semasa dan bersetuju dengan pelan tindakan yang perlu dilaksanakan?</label>
                            <div class="flex">
                                <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                    <input type="radio" value="1" x-model="activePmgi.undrstd_flag" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600" disabled>
                                    <label class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2">YA</label>
                                </div>
                                <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                    <input type="radio" value="2" x-model="activePmgi.undrstd_flag" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600" disabled>
                                    <label class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2">TIDAK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="my-8">
                        <label class="block mb-2 font-medium text-gray-900 text-md">Lain-lain perkara (jika ada) :</label>
                        <x-textarea x-model="activePmgi.others" disabled />
                    </div>
                    <hr>

                    <div class="my-8">
                        <div class="mb-2">
                            <label class="block mb-2 font-medium text-gray-900 text-md">Disyorkan penilaian semula?</label>
                            <div class="flex">
                                <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                    <input type="radio" value="1" x-model="activePmgi.exit_flag" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600" disabled>
                                    <label class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2">YA</label>
                                </div>
                                <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                    <input type="radio" value="0" x-model="activePmgi.exit_flag" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600" disabled>
                                    <label class="w-full py-4 text-sm font-medium text-gray-700 opacity-60 ms-2">TIDAK</label>
                                </div>
                            </div>
                        </div>
                        <div class="my-4" x-show="activePmgi.fair_flag == 1">
                            <x-native-select 
                                label="Pilihan jenis penilaian semula :" 
                                :options="[
                                    ['name' => 'Kembali ke PMG-i (1)', 'id' => 1],
                                    ['name' => 'Kembali ke PMG-i (2)', 'id' => 2],
                                    ['name' => 'Kembali ke PMG-i (3)', 'id' => 3],
                                ]" 
                                option-label="name" 
                                option-value="id"
                                x-model="activePmgi.exit_type_flag"
                                disabled 
                            />
                        </div>
                        <div>
                            <x-textarea label="Ulasan :" x-model="activePmgi.comments" disabled />
                        </div>
                    </div>

                    @if($pmgi['attachment'])
                        @php
                            $fileExtension = pathinfo($pmgi['attachment'], PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img class="mb-5 w-60" src="{{ asset('storage/' . $pmgi['attachment']) }}" alt="Attachment Preview">
                        @elseif($fileExtension === 'pdf')
                            <button type="button" class="cursor-pointer bg-blue-500 p-4 rounded-md text-white" wire:click="toggleModal('{{ $pmgi['attachment'] }}')">
                                Lihat lampiran
                            </button>
                        @elseif($fileExtension === 'docx')
                            <a href="{{ asset('storage/' . $pmgi['attachment']) }}" target="_blank" class="cursor-pointer text-blue-500 hover:underline">
                                {{ basename($pmgi['attachment']) }}
                            </a>
                        @endif
                    @endif
                </div>
            @else
                <div class="mt-8 p-4 text-center text-gray-500 bg-gray-50 rounded-lg">
                    Tiada rekod penilaian semula sebelum ini.
                </div>
            @endif
        </div>
    </div>

    {{-- attachment modal --}}
    <x-modal.card blur align="center" max-width="7xl" hide-close=false wire:model="attachmentModal">
        @if($attachmentUrl)
            <iframe src="{{ $attachmentUrl }}" frameborder="0" width="100%" height="700px"></iframe>
        @endif
    </x-modal.card>
</div>

