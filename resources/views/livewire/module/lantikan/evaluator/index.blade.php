<div>
    <div>
        <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
            <h3 class="mb-2 text-xl font-bold text-gray-900">Lantikan PYM & PMC</h3>
            <span class="text-base font-normal text-gray-500">Lantikan PYM & PMC bagi Negeri {{ ucwords(strtolower(auth()->user()->stateName())) . ' ' . $current->translatedFormat('F') . ' ' . $current->format('Y') }}</span>
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
                <livewire:module.lantikan.evaluator.pmgi1 :currentDate=$current />
            </div>

            <div x-show="tab === 'PMGi2'" x-transition>
                <livewire:module.lantikan.evaluator.pmgi2 :currentDate=$current />
            </div>

            <div x-show="tab === 'PMGi3'" x-transition>
                <livewire:module.lantikan.evaluator.pmgi3 :currentDate=$current />
            </div>
        </div>
    </div>
</div>
