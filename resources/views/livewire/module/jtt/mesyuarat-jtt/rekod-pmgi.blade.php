<div x-data="{
        tab: 'PM1',
        pmgiData: {{ json_encode($pmgiData) }},
        activePmgi: {},
        pmgiSessionIds: { PM1: null, PM2: null, PM3: null },

        // Function to update the activePmgi when the tab changes
        setActivePmgi(lvl) {
            this.tab = lvl;
            this.activePmgi = this.pmgiData.find(pmgi => pmgi.lvl === lvl) || {};
            this.pmgiSessionIds[lvl] = this.activePmgi.session_id;
        },

        // Initialize the first tab's data when the component is mounted
        init() {
            this.pmgiData.forEach(pmgi => {
                this.pmgiSessionIds[pmgi.lvl] = pmgi.session_id;
            });
            this.setActivePmgi('PM1'); // Default to PM1 on mount
        }
    }" x-init="init()">

    <div  class="mt-4">
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
            <div class="flex justify-center mt-4">
                <x-badge rounded primary label="Lihat Laporan" class="px-3 py-2 cursor-pointer" @click="$wire.toggleDetail(activePmgi.session_id)" />
            </div>
        </div>
    </div>

    <div class="items-center">
        <div class="mb-4 lg:mb-0">
            <div class="w-1/2 mx-auto mt-8">
                <h3 class="text-lg font-medium text-center text-gray-900">Keputusan :</h3>
                <x-select
                    placeholder="Sila Pilih"
                    :options="($pmgiLevel == 'JT1')
                        ? [
                            ['name' => 'Keluar Senarai', 'id' => 'Keluar Senarai'],
                            ['name' => 'Diberi Tempoh', 'id' => 'Diberi Tempoh'],
                            ['name' => 'Domestic Inquiry (DI)', 'id' => 'Domestic Inquiry (DI)']
                        ]
                        : [
                            ['name' => 'Keluar Senarai', 'id' => 'Keluar Senarai'],
                            ['name' => 'Domestic Inquiry (DI)', 'id' => 'Domestic Inquiry (DI)']
                        ]"
                    option-label="name"
                    option-value="id"
                    wire:model.live="result"
                />

                @if($result == 'Diberi Tempoh')
                    <div class="flex items-center justify-center mt-2">
                        <x-input class="block p-1 text-center " wire:model="mthDelay" />
                        <label for="negeri" class="block ml-4 font-medium text-gray-900 dark:text-white">Bulan</label>
                    </div>
                @endif

                <h3 class="mt-6 text-lg font-medium text-center text-gray-900">Ulasan :</h3>
                <x-textarea wire:model="comment" />
            </div>
        </div>
    </div>

    <div class="flex justify-center mt-4">
        <button wire:click="submit(pmgiSessionIds)" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
            Simpan
        </button>
    </div>

    {{-- details modal --}}
    <x-modal.card blur align="center" max-width="7xl" hide-close=false wire:model="detailsModal">
        @if($pmgiSessionId)
        <iframe src="{{ route('stream.rekodPmgi', ['sessionId' => $pmgiSessionId]) }}" frameborder="0" width="100%" height="700px"></iframe>
        @endif
    </x-modal.card>

</div>
