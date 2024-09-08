<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Keputusan DI oleh Pegawai Sumber Manusia</h3>
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
                <div class="items-center">
                    <div class="mb-4 lg:mb-0">
                        <div class="w-1/2 mx-auto mt-4">
                            <h3 class="text-lg font-medium text-center text-gray-900">Tarikh Kuatkuasa :</h3>
                            <div class="w-1/2 mx-auto">
                                <x-datetime-picker  placeholder="Tarikh Kuatkuasa" display-format="DD-MM-YYYY" wire:model="effectiveDate" without-time />
                            </div>

                            <h3 class="mt-4 text-lg font-medium text-center text-gray-900">Keputusan :</h3>
                            <div class="w-1/2 mx-auto">
                                <x-select
                                    placeholder="Sila Pilih"
                                    :options="[
                                        ['name' => 'Diberhenti',  'id' => 1],
                                        ['name' => 'Tidak Diberhenti', 'id' => 0],
                                    ]"
                                    option-label="name"
                                    option-value="id"
                                    wire:model.live="result"
                                />
                            </div>

                            @if($result === 0)
                            <h3 class="mt-4 text-lg font-medium text-center text-gray-900">Tarikh Sehingga :</h3>
                            <div class="w-1/2 mx-auto">
                                <x-datetime-picker placeholder="Tarikh Sehingga" display-format="DD-MM-YYYY" wire:model="dateUntil" without-time />
                            </div>
                            @endif

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
