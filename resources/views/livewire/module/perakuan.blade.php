<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-center text-gray-900">Perakuan Pegawai Yang Dinilai (PYD)</h3>
                </div>
            </div>

            <div class="w-1/2 mx-auto mt-8">
                <p class="mb-8 text-xl text-gray-600" style="text-indent: 4rem;text-align: justify;">
                    Saya <span class="font-semibold underline">WAN MOHAMAD RIDZUAN BIN WAN JAAPAR</span> No. Kad Pengenalan <span class="font-semibold underline">910506-11-5087</span> , Nombor Pekerja <span class="font-semibold underline">4227</span> daripada Cawangan telah ditetapkan dan dipersetujui oleh saya di dalam Borang Prestasi (PMG-i) ini dalam masa dua (2) bulan mulai - <span class="font-semibold underline">AUG 2024</span> sehingga <span class="font-semibold underline">SEP 2024</span> . Jika saya masih gagal meningkatkan prestasi dan / atau mencapai sasaran seperti yang dinyatakan di dalam Borang Pemantauan Prestasi (PMG-i) ini, maka saya bersetuju bahawa saya boleh dikenakan tindakan tatatertib mengikut Manual Prosedur Pentadbiran dan Sumber Manusia, TEKUN Nasional (Etika Kerja - Kod Tatakelakuan termasuk dibuang kerja).
                </p>
            </div>

            <div x-data="{ tab: 'PYD' }" class="mt-4">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                    <li class="me-2">
                        <div
                            @click="tab = 'PYD'"
                            :class="{ 'bg-primary-600 text-white': tab === 'PYD', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PYD' }"
                            class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page"
                        >
                            Pegawai Yang Dinilai
                        </div>
                    </li>
                    <li class="me-2">
                        <div
                            @click="tab = 'PYM'"
                            :class="{ 'bg-primary-600 text-white': tab === 'PYM', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PYM' }"
                            class="inline-block px-4 py-3 rounded-lg cursor-pointer"
                        >
                            Pegawai Yang Menilai
                        </div>
                    </li>
                    <li class="me-2">
                        <div
                            @click="tab = 'PMC'"
                            :class="{ 'bg-primary-600 text-white': tab === 'PMC', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMC' }"
                            class="inline-block px-4 py-3 rounded-lg cursor-pointer"
                        >
                            Pegawai Pemudah Cara
                        </div>
                    </li>
                </ul>
                <div x-show="tab === 'PYD'" x-transition>
                    {{-- PYD --}}
                    <livewire:module.pegawai-dinilai :search=false :edit=true />
                </div>
                <div x-show="tab === 'PYM'" x-transition>
                    {{-- PYM --}}
                    <livewire:module.pegawai-menilai :search=false :edit=true />
                </div>
                <div x-show="tab === 'PMC'" x-transition>
                    {{-- PMC --}}
                    <livewire:module.pegawai-pemudah-cara :search=false :edit=true />
                </div>
            </div>

            <div class="flex justify-center">
                <button wire:click="verify" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                    Hantar
                </button>
            </div>
        </div>
    </div>

    {{-- verify modal --}}
    <x-modal.card title="Verifikasi Perakuan" blur align="center" max-width="md" hide-close=true wire:model="verifyModal">
        <div class="grid grid-rows-2 gap-1 text-sm font-semibold ">
            <p>NAMA: <span class="text-indigo-500">{{ auth()->user()->username }}</span></p>
            <p>NO. KAD PENGENALAN: <span class="text-indigo-500">{{ auth()->user()->icNo() }}</span></p>
        </div>

        <p class="mt-4 text-sm text-gray-600">
            Sila masukkan ID dan kata laluan anda sekiranya maklumat yang dipaparkan adalah tepat:
        </p>

        <div class="grid grid-cols-1 gap-4 mt-4">
            <x-input label="ID Pengguna" placeholder="ID Pengguna Anda" />
            <x-input label="Password" placeholder="Password Anda" />
        </div>

        <x-slot name="footer">
            <div class="flex justify-center">
                <x-button primary label="Hantar" wire:click="save" />
            </div>
        </x-slot>
    </x-modal.card>

</main>
