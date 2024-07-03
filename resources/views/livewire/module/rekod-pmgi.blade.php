<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Rekod PMGi</h3>
                    <span class="text-base font-normal text-gray-500 ">Rekod PMGi Individu</span>
                    <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between">
                            <div class="grid w-[70%] grid-cols-3 gap-x-4 gap-y-2">
                                <div>
                                    <label for="no_pekerja" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">No Pekerja</label>
                                    <input type="text" id="no_pekerja" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label for="nama_pegawai" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama Pegawai</label>
                                    <input type="text" id="nama_pegawai" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </div>
                            <button class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 ">
                                Cari
                                <x-icon name="search" class="w-4 h-4 ms-2" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ tab: 'PMGi1' }" class="mt-6">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                    <li class="me-2">
                        <div @click="tab = 'PMGi1'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi1', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi1' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                            PMGi 1
                        </div>
                    </li>
                    <li class="me-2">
                        <div @click="tab = 'PMGi2'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi2', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi2' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                            PMGi 2
                        </div>
                    </li>
                    <li class="me-2">
                        <div @click="tab = 'PMGi3'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi3', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi3' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                            PMGi 3
                        </div>
                    </li>
                    <li class="me-2">
                        <div @click="tab = 'JTT1'" :class="{ 'bg-primary-600 text-white': tab === 'JTT1', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'JTT1' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                            Timbang Tara 1
                        </div>
                    </li>
                    <li class="me-2">
                        <div @click="tab = 'JTT2'" :class="{ 'bg-primary-600 text-white': tab === 'JTT2', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'JTT2' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                            Timbang Tara 2
                        </div>
                    </li>
                </ul>
                <div x-show="tab === 'PMGi1'" x-transition>
                    <!-- Table -->
                    <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 ">
                                        <thead class=" bg-gray-50">
                                            <tr class="bg-gray-100">
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    BIL
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    TARIKH
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    KEPUTUSAN
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white ">
                                            <tr>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    1
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    OGOS 2023
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    <div class="inline-block bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</div>
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    <x-badge rounded primary label="Lihat" class="cursor-pointer" wire:click="toggleDetail" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if($showDetail)
                            <div x-data="{ tab: 'PYD' }" class="mt-6">
                                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                                    <li class="me-2">
                                        <div @click="tab = 'PYD'" :class="{ 'bg-primary-600 text-white': tab === 'PYD', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PYD' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                                            Pegawai Yang Dinilai
                                        </div>
                                    </li>
                                    <li class="me-2">
                                        <div @click="tab = 'PYM'" :class="{ 'bg-primary-600 text-white': tab === 'PYM', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PYM' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                            Pegawai Yang Menilai
                                        </div>
                                    </li>
                                    <li class="me-2">
                                        <div @click="tab = 'PMC'" :class="{ 'bg-primary-600 text-white': tab === 'PMC', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMC' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                            Pegawai Pemudah Cara
                                        </div>
                                    </li>
                                </ul>

                                <div x-show="tab === 'PYD'" x-transition>
                                    {{-- PYD --}}
                                    <livewire:module.pegawai-dinilai :search=false :edit=false />
                                </div>
                                <div x-show="tab === 'PYM'" x-transition>
                                    {{-- PYM --}}
                                    <livewire:module.pegawai-menilai :search=false :edit=false />
                                </div>
                                <div x-show="tab === 'PMC'" x-transition>
                                    {{-- PMC --}}
                                    <livewire:module.pegawai-pemudah-cara :search=false :edit=false />
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div x-show="tab === 'PMGi2'" x-transition>
                </div>
                <div x-show="tab === 'PMGi3'" x-transition>
                </div>
            </div>

        </div>
    </div>
</main>