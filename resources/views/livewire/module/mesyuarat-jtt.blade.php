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
                                <x-input label="Negeri" wire:model="state" wire:keydown.enter="search" disabled />
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
                <div x-data="{ tab: 'rekodPmgi' }" class="mt-4">
                    <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                        <li class="me-2">
                            <div @click="tab = 'rekodPmgi'" :class="{ 'bg-primary-600 text-white': tab === 'rekodPmgi', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'rekodPmgi' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                                <h3 class="text-xl font-bold ">Rekod PMGi </h3>
                            </div>
                        </li>
                        <li class="me-2">
                            <div @click="tab = 'rekodPrestasi'" :class="{ 'bg-primary-600 text-white': tab === 'rekodPrestasi', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'rekodPrestasi' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                <h3 class="text-xl font-bold">Rekod Prestasi Kumulatif</h3>
                            </div>
                        </li>
                    </ul>
                    <div x-show="tab === 'rekodPmgi'" x-transition>
                        <livewire:module.jtt.mesyuarat-jtt.rekod-pmgi :sessionId=$sessionId :userId=$userId :reportDate=$reportDate />
                    </div>
                    <div x-show="tab === 'rekodPrestasi'" x-transition>
                        <livewire:home.pyd :userId=$userId />
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
