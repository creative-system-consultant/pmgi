<main>
    <div class="px-4 pt-6 2xl:px-0" x-data="{ tab: 'pyd' }" x-cloak>
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Soalan Lazim</h3>
                    <span class="text-base font-normal text-gray-500 ">Soalan Lazim untuk PYD, PYM & PMC</span>
                    <div class="mt-6">
                        <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                            <li class="me-2">
                                <div @click="tab = 'pyd'" :class="{ 'bg-primary-600 text-white': tab === 'pyd', 'hover:text-gray-900 hover:bg-gray-200 bg-gray-100': tab !== 'pyd' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                                    PYD
                                </div>
                            </li>
                            <li class="me-2">
                                <div @click="tab = 'pym'" :class="{ 'bg-primary-600 text-white': tab === 'pym', 'hover:text-gray-900 hover:bg-gray-200 bg-gray-100': tab !== 'pym' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                    PYM
                                </div>
                            </li>
                            <li class="me-2">
                                <div @click="tab = 'pmc'" :class="{ 'bg-primary-600 text-white': tab === 'pmc', 'hover:text-gray-900 hover:bg-gray-200 bg-gray-100': tab !== 'pmc' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                    PMC
                                </div>
                            </li>
                            <li class="me-2">
                                <div @click="tab = 'jkpi'" :class="{ 'bg-primary-600 text-white': tab === 'jkpi', 'hover:text-gray-900 hover:bg-gray-200 bg-gray-100': tab !== 'jkpi' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                    JKPI
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- result --}}
        <div x-show="tab === 'pyd'" x-transition>
            <livewire:admin.maintenance.pydfaq/>
        </div>

        <div x-show="tab === 'pym'" x-transition>
            <livewire:admin.maintenance.pymfaq/>
        </div>

        <div x-show="tab === 'pmc'" x-transition>
            <livewire:admin.maintenance.pmcfaq />
        </div>

        <div x-show="tab === 'jkpi'" x-transition>
            <livewire:admin.maintenance.jkpifaq />
        </div>
</main>
