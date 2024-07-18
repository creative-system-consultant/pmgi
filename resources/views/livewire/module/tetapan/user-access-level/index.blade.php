<main>
    <div class="px-4 pt-6 2xl:px-0" x-data="{ tab: 'user-access' }" x-cloak>
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Tetapan Akses Pengguna</h3>
                    <span class="text-base font-normal text-gray-500 ">Tetapan capaian akses pengguna</span>
                    <div class="mt-6">
                        <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                            <li class="me-2">
                                <div @click="tab = 'user-access'" :class="{ 'bg-primary-600 text-white': tab === 'user-access', 'hover:text-gray-900 hover:bg-gray-200 bg-gray-100': tab !== 'user-access' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                                    Akses Pengguna
                                </div>
                            </li>
                            <li class="me-2">
                                <div @click="tab = 'role'" :class="{ 'bg-primary-600 text-white': tab === 'role', 'hover:text-gray-900 hover:bg-gray-200 bg-gray-100': tab !== 'role' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                    Kumpulan
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- result --}}
        <div x-show="tab === 'user-access'" x-transition>
            <livewire:module.tetapan.user-access-level.user-access />
        </div>

        <div x-show="tab === 'role'" x-transition>
            <livewire:module.tetapan.user-access-level.role />
        </div>
</main>