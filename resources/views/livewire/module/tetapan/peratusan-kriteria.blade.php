    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Tetapan Peratusan Kriteria</h3>
                    <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between">
                            <div class="grid w-[70%] grid-cols-4 gap-x-4 gap-y-2">
                                <div>
                                    <x-select label="Jenis" placeholder="Sila Pilih" :options="[
                                            ['name' => 'KESELURUHAN',  'id' => 1],
                                            ['name' => 'NEGERI', 'id' => 2],
                                        ]" option-label="name" option-value="id" wire:model.live="type"
                                    />
                                </div>
                                @if($type == 2)
                                    <div>
                                        <x-select label="Negeri" placeholder="Sila Pilih" :options="$stateSelection" option-label="description" option-value="code" wire:model="negeri" />
                                    </div>
                                @endif
                            </div>
                            <div class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" wire:click="kemaskini">
                                Kemaskini
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Mount-->
            @if($resultMount)
            <div class="mt-6">
                <div class="p-6 overflow-x-auto bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                        Peratusan Kriteria Tarikh Kuatkuasa : {{ \Carbon\Carbon::parse($effective_date)->format('d/m/Y') }}
                        <span class="ml-4 "><x-badge flat lime label="AKTIF" /></span>
                    </h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach($tableData[0] as $header)
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    {{ $header }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                            $lastRowIndex = count($tableData) - 1;
                            $defaultValues = $tableData[$lastRowIndex];
                            @endphp
                            @foreach($tableData as $index => $row)
                            @if($index > 0) {{-- Skip the header row --}}
                            <tr>
                                @foreach($row as $key => $cell)
                                <td class="px-6 py-4 whitespace-nowrap text-sm
                                                    @if($index != $lastRowIndex && $key != 'No.' && $key != 'Negeri' && $cell != $defaultValues[$key])
                                                        text-red-600 font-semibold
                                                    @else
                                                        text-gray-500
                                                    @endif">
                                    {{ $cell }}
                                </td>
                                @endforeach
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Next Effective Date Mount-->
            @if($nextResultMount)
            <div class="mt-6">
                <div class="p-6 overflow-x-auto bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                        Peratusan Kriteria Tarikh Kuatkuasa : {{ \Carbon\Carbon::parse($next_effective_date)->format('d/m/Y') }}
                        <span class="ml-4 "><x-badge flat warning label="BELUM AKTIF" /></span>
                    </h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach($nextTableData[0] as $header)
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    {{ $header }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                            $lastRowIndex = count($nextTableData) - 1;
                            $defaultValues = $nextTableData[$lastRowIndex];
                            @endphp
                            @foreach($nextTableData as $index => $row)
                            @if($index > 0) {{-- Skip the header row --}}
                            <tr>
                                @foreach($row as $key => $cell)
                                <td class="px-6 py-4 whitespace-nowrap text-sm
                                                    @if($index != $lastRowIndex && $key != 'No.' && $key != 'Negeri' && $cell != $defaultValues[$key])
                                                        text-red-600 font-semibold
                                                    @else
                                                        text-gray-500
                                                    @endif">
                                    {{ $cell }}
                                </td>
                                @endforeach
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Update -->
            @if($result)
                <div class="mt-6">
                    <div x-data="{
                        sliderValues: @entangle('evaluation_criteria_percentage')
                    }" x-init="$watch('sliderValues', value => console.log('sliderValues changed:', value))">
                        <div class="p-6 bg-white rounded-lg shadow">
                            <div class="space-y-4">
                                @foreach(range(1, 5) as $i)
                                    <div class="flex items-center">
                                        <h4 class="w-1/3 text-sm font-semibold text-gray-900">{{ $i }}. {{ $titles[$i] }}</h4>
                                        <div class="flex items-center w-2/3">
                                            <input
                                                type="range"
                                                x-model="sliderValues[{{ $i }}]"
                                                wire:model="evaluation_criteria_percentage.{{ $i }}"
                                                min="0"
                                                max="100"
                                                step="5"
                                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                            >
                                            <span class="w-12 ml-3 text-sm font-medium text-right text-gray-900" x-text="sliderValues[{{ $i }}] + '%'"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <x-button
                        primary
                        label="Simpan Peratusan"
                        wire:click="saveEvaluationCriteriaPercentage"
                    />
                </div>
            @endif
        </div>
    </div>
