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
                <div x-data="{ tab: 'pmgi1' }" class="mt-4">
                    <ul class="flex flex-wrap mb-4 text-sm font-medium text-center text-gray-500">
                        <li class="me-2">
                            <div @click="tab = 'pmgi1'" :class="{ 'bg-primary-600 text-white': tab === 'pmgi1', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'pmgi1' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                                PMGi 1
                            </div>
                        </li>
                        <li class="me-2">
                            <div @click="tab = 'pmgi2'" :class="{ 'bg-primary-600 text-white': tab === 'pmgi2', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'pmgi2' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                PMGi 2
                            </div>
                        </li>
                        <li class="me-2">
                            <div @click="tab = 'pmgi3'" :class="{ 'bg-primary-600 text-white': tab === 'pmgi3', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'pmgi3' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                PMGi 3
                            </div>
                        </li>
                        @if ($pmgiLevel == 'JT2')
                        <li class="me-2">
                                <div @click="tab = 'jtt1'" :class="{ 'bg-primary-600 text-white': tab === 'jtt1', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'jtt1' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                                    JTT
                                </div>
                            </li>
                        @endif
                    </ul>

                    <div class="p-6 bg-gray-100 border border-gray-200 rounded-lg shadow">
                        <div class="grid w-1/2 grid-cols-2 gap-2 mx-auto">
                            <p class="flex items-center font-semibold">Tarikh</p>
                            <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Pegawai Yang Menilai</p>
                            <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p x-show="tab==='pmgi3'" class="flex items-center font-semibold ">Pegawai Mudah Cara</p>
                            <input x-show="tab==='pmgi3'" type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Status</p>
                            <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Keputusan</p>
                            <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
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
