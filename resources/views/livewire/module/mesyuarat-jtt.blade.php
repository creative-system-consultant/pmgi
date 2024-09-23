<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Mesyuarat Jawatankuasa Timbang Tara</h3>
                    <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between">
                            <div class="grid w-full grid-cols-6 gap-x-4 gap-y-2">
                                <x-input label="No Pekerja" wire:model="staffNo" wire:keydown.enter="search" disabled />
                                {{-- <div class="col-span-2"> --}}
                                    <x-input label="Negeri" wire:model="state" wire:keydown.enter="search" disabled />
                                {{-- </div> --}}
                                <div class="col-span-2">
                                    <x-input label="Cawangan" wire:model="branch" wire:keydown.enter="search" disabled />
                                </div>
                                <div class="col-span-2">
                                    <x-input label="Nama Pekerja" wire:model="staffName" wire:keydown.enter="search" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="flex flex-col mt-8">
                <h3 class="mb-2 text-xl font-bold text-gray-900 ">Rekod PMGi </h3>
                <div x-data="{
                    tab: 'PM1',
                    pmgiData: {{ json_encode($pmgiData) }},
                    activePmgi: {},

                    // Function to update the activePmgi when the tab changes
                    setActivePmgi(lvl) {
                        this.tab = lvl;
                        this.activePmgi = this.pmgiData.find(pmgi => pmgi.lvl === lvl) || {};
                    },

                    // Initialize the first tab's data when the component is mounted
                    init() {
                        this.setActivePmgi('PM1'); // Default to PM1 on mount
                    }
                }" x-init="init()" class="mt-4">
                    <!-- Tab List -->
                    <ul class="flex flex-wrap mb-4 text-sm font-medium text-center text-gray-500">
                        @foreach ($pmgiData as $pmgi)
                        <li class="me-2">
                            <div @click="setActivePmgi('{{ $pmgi['lvl'] }}')" :class="{ 'bg-primary-600 text-white': tab === '{{ $pmgi['lvl'] }}', 'hover:text-gray-900 hover:bg-gray-100': tab !== '{{ $pmgi['lvl'] }}' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                                {{ 'PMGi ' . substr($pmgi['lvl'], -1) }}
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Form to display mapped data -->
                    <div class="p-6 bg-gray-100 border border-gray-200 rounded-lg shadow">
                        <div class="grid w-1/2 grid-cols-2 gap-2 mx-auto">
                            <!-- Tarikh -->
                            <p class="flex items-center font-semibold">Tarikh</p>
                            <input type="text" id="small-input" x-model="activePmgi.date_session" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " disabled>

                            <!-- Pegawai Yang Menilai (Pym) -->
                            <p class="flex items-center font-semibold">Pegawai Yang Menilai</p>
                            <input type="text" id="small-input" x-model="activePmgi.pym" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " disabled>

                            <!-- Pegawai Mudah Cara (only for PM3) -->
                            <p x-show="tab === 'PM3'" class="flex items-center font-semibold">Pegawai Mudah Cara</p>
                            <input x-show="tab === 'PM3'" type="text" id="small-input" x-model="activePmgi.pmc" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " disabled>

                            <!-- Keputusan (Result) -->
                            <p class="flex items-center font-semibold">Keputusan</p>
                            <input type="text" id="small-input" x-model="activePmgi.result" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " disabled>
                        </div>
                    </div>
                </div>

                <div class="items-center">
                    <div class="mb-4 lg:mb-0">
                        <div class="w-1/2 mx-auto mt-8">
                            <h3 class="text-lg font-medium text-center text-gray-900">Keputusan :</h3>
                            <select id="small" wire:model.live="result" class="block w-1/2 p-2 mx-auto text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                <option selected>Sila Pilih</option>
                                @if($pmgiLevel == 'JT1')
                                    <option value="Diberi Tempoh">Diberi Tempoh</option>
                                @else
                                    <option value="Keluar Senarai">Keluar Senarai</option>
                                @endif
                                <option value="Domestic Inquiry (DI)">Domestic Inquiry (DI)</option>
                            </select>

                            @if($result == 'Diberi Tempoh')
                                <div class="flex items-center justify-center mt-2">
                                    <input type="text" id="negeri" class="block p-1 text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500" style=" width: 10%;" wire:model="mthDelay">
                                    <label for="negeri" class="block ml-4 font-medium text-gray-900 dark:text-white">Bulan</label>
                                </div>
                            @endif

                            <h3 class="mt-6 text-lg font-medium text-center text-gray-900">Ulasan :</h3>
                            <textarea id="pelan" rows="3" wire:model="comment" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-4">
                <button wire:click="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                    Simpan
                </button>
            </div>

        </div>
    </div>
</main>
