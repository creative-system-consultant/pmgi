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
                                    <label for="jenis" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Jenis</label>
                                    <select wire:model.live="type" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                        <option selected>Sila Pilih</option>
                                        <option value="1">Keseluruhan</option>
                                        <option value="2">Negeri</option>
                                    </select>
                                </div>
                                @if($type == 2)
                                <div>
                                    <label for="negeri" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Negeri</label>
                                    <select wire:model="negeri" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                        <option selected>Sila Pilih</option>
                                        @foreach($stateSelection as $state)
                                            <option value="{{ $state->code }}">{{ $state->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div>
                                    <x-datetime-picker 
                                        label="Tarikh Kuatkuasa" 
                                        placeholder="Pilih tarikh" 
                                        display-format="MM-YYYY" 
                                        wire:model="effective_date" 
                                        without-time 
                                        view-mode="months"
                                        :min="Carbon\Carbon::now()->addMonth()->startOfMonth()"
                                    />
                                </div>
                            </div>
                            <div class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" wire:click="generate">
                                Cari
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Result -->
            @if($result)
                <div class="mt-6">
                    <div x-data="{
                        sliderValues: @entangle('evaluation_criteria_percentage')
                    }" x-init="$watch('sliderValues', value => console.log('sliderValues changed:', value))">
                        @php
                            $titles = [
                                1 => 'Patut Kutip (RM) vs Dapat Kutip (RM)',
                                2 => 'Patut Kutip (BIL) vs Dapat Kutip (BIL)',
                                3 => 'Lawatan Seliaan',
                                4 => 'Prestasi NPF (KAWALAN)',
                                5 => 'Prestasi NPF (Pemulihan)'
                            ];
                        @endphp

                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="space-y-4">
                                @foreach(range(1, 5) as $i)
                                    <div class="flex items-center">
                                        <h4 class="w-1/3 text-sm font-semibold text-gray-900">{{ $i }}. {{ $titles[$i] }}</h4>
                                        <div class="w-2/3 flex items-center">
                                            <input 
                                                type="range" 
                                                x-model="sliderValues[{{ $i }}]"
                                                wire:model="evaluation_criteria_percentage.{{ $i }}" 
                                                min="0" 
                                                max="100" 
                                                step="5"
                                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                            >
                                            <span class="ml-3 text-sm font-medium text-gray-900 w-12 text-right" x-text="sliderValues[{{ $i }}] + '%'"></span>
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
