<div>
    <div x-data="{
                selectedYear: @entangle('selectedYear'),
                scrollToSelectedYear() {
                    this.$nextTick(() => {
                        let selectedYear = document.querySelector('.selected-year');
                        if (selectedYear) {
                            let container = selectedYear.closest('.scroll-container');
                            let containerWidth = container.offsetWidth;
                            let badgeWidth = selectedYear.offsetWidth;
                            let badgePosition = selectedYear.offsetLeft;
                            let scrollPosition = badgePosition - (containerWidth / 2) + (badgeWidth / 2);
                            container.scrollTo({
                                left: scrollPosition,
                                behavior: 'smooth'
                            });
                        }
                    });
                }
            }" x-init="scrollToSelectedYear()" x-effect="() => selectedYear && scrollToSelectedYear()">
        <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
            <h3 class="mb-2 text-xl font-bold text-gray-900">Lantikan PYM & PMC</h3>
            <span class="text-base font-normal text-gray-500">Lantikan PYM & PMC bagi Negeri {{ ucwords(strtolower(auth()->user()->stateName())) . ' ' . $selectedMonth . ' ' . $selectedYear }}</span>
            <div class="p-4 mt-6 overflow-x-auto bg-gray-100 border border-gray-300 rounded-lg scroll-container">
                <div class="grid grid-flow-col gap-4">
                    @foreach ($years as $year)
                        <div>
                            @if($year == $selectedYear)
                                <x-badge lg primary label="{{ $year }}" class="selected-year" wire:click="changeYear('{{ $year }}')">
                                    <x-slot name="prepend" class="relative flex items-center w-2 h-2">
                                        <span class="absolute inline-flex w-full h-full rounded-full opacity-75 bg-cyan-600 animate-ping"></span>
                                        <span class="relative inline-flex w-2 h-2 rounded-full bg-cyan-300"></span>
                                    </x-slot>
                                </x-badge>
                            @else
                                <x-badge lg secondary label="{{ $year }}" class="cursor-pointer" wire:click="changeYear('{{ $year }}')" />
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="p-4 mt-4 bg-gray-100 border border-gray-300 rounded-lg">
                <div class="grid grid-flow-col gap-4">
                    @foreach ($months as $month)
                        @if($month['value'] == $selectedMonth)
                            <x-badge lg primary label="{{ $month['name'] }}" wire:click="changeMonth('{{ $month['value'] }}')">
                                <x-slot name="prepend" class="relative flex items-center w-2 h-2">
                                    <span class="absolute inline-flex w-full h-full rounded-full opacity-75 bg-cyan-600 animate-ping"></span>
                                    <span class="relative inline-flex w-2 h-2 rounded-full bg-cyan-300"></span>
                                </x-slot>
                            </x-badge>
                        @else
                            <x-badge lg secondary label="{{ $month['name'] }}" class="cursor-pointer" wire:click="changeMonth('{{ $month['value'] }}')" />
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div x-data="{ tab: 'PMGi1' }" class="mt-6">
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                <li class="me-2">
                    <div @click="tab = 'PMGi1'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi1', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi1' }" class="inline-block px-4 py-3 bg-gray-200 rounded-lg cursor-pointer" aria-current="page">
                        PMGi 1
                    </div>
                </li>
                <li class="me-2">
                    <div @click="tab = 'PMGi2'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi2', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi2' }" class="inline-block px-4 py-3 bg-gray-200 rounded-lg cursor-pointer">
                        PMGi 2
                    </div>
                </li>
                <li class="me-2">
                    <div @click="tab = 'PMGi3'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi3', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi3' }" class="inline-block px-4 py-3 bg-gray-200 rounded-lg cursor-pointer">

                        PMGi 3
                    </div>
                </li>
            </ul>

            <div x-show="tab === 'PMGi1'" x-transition>
                <livewire:module.lantikan.evaluator.pmgi1 :month=$selectedMonth :year=$selectedYear />
            </div>

            <div x-show="tab === 'PMGi2'" x-transition>
                <livewire:module.lantikan.evaluator.pmgi2 :month=$selectedMonth :year=$selectedYear />
            </div>

            <div x-show="tab === 'PMGi3'" x-transition>
                <livewire:module.lantikan.evaluator.pmgi3 :month=$selectedMonth :year=$selectedYear />
            </div>
        </div>
    </div>

    <style>
        .scroll-container {
            display: flex;
            overflow-x: auto;
            padding: 1rem;
            border-radius: 0.5rem;
            background-color: #f0f4f8;
            border: 1px solid #d1d5db;
        }

        .scroll-container::-webkit-scrollbar {
            display: none;
        }

    </style>
</div>
