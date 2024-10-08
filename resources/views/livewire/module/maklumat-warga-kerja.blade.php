<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Maklumat Warga Kerja</h3>
                    <span class="text-base font-normal text-gray-500 ">Mulakan Sesi PMG-i bersama Warga Kerja</span>
                    <div class="my-6">
                        <!-- Card header -->
                        <div class="items-center justify-between lg:flex">
                            <div class="mb-4">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="pr-2">
                                                <h3 class="font-bold text-gray-900 text-md ">Sesi PMG-i </h3>
                                            </td>
                                            <td class="p-2">
                                                <span class="px-4 py-2.5 text-sm font-medium  rounded me-2 border  @if($pmgiLevel == 'PM1') text-amber-800 bg-amber-100 border-amber-500 animate-pulse @else text-gray-800 bg-gray-100 border-gray-500 @endif">1</span>
                                            </td>
                                            <td class="p-2">
                                                <span class="px-4 py-2.5 text-sm font-medium  rounded me-2 border  @if($pmgiLevel == 'PM2') text-amber-800 bg-amber-100 border-amber-500 animate-pulse @else text-gray-800 bg-gray-100 border-gray-500 @endif">2</span>
                                            </td>
                                            <td class="p-2">
                                                <span class="px-4 py-2.5 text-sm font-medium  rounded me-2 border  @if($pmgiLevel == 'PM3') text-amber-800 bg-amber-100 border-amber-500 animate-pulse @else text-gray-800 bg-gray-100 border-gray-500 @endif">3</span>
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                <td class="px-2 py-4">
                                    <h3 class="font-bold text-gray-900 text-md ">Sesi Timbang Tara </h3>
                                </td>
                                <td class="px-2 py-4">
                                    <span class="px-4 py-2.5 text-sm font-medium  rounded me-2 border  @if($pmgiLevel == 'JT1') text-amber-800 bg-amber-100 border-amber-500 animate-pulse @else text-gray-800 bg-gray-100 border-gray-500 @endif">1</span>
                                </td>
                                <td class="px-2 py-4">
                                    <span class="px-4 py-2.5 text-sm font-medium  rounded me-2 border  @if($pmgiLevel == 'JT2') text-amber-800 bg-amber-100 border-amber-500 animate-pulse @else text-gray-800 bg-gray-100 border-gray-500 @endif">2</span>
                                </td>
                            </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="grid grid-cols-6 gap-y-10 gap-x-14">
                            {{-- maklumat warga kerja --}}
                            <div class="col-span-3">
                                <div class="mb-4">
                                    <div class="flex items-center justify-center border-4 border-gray-700 rounded-md bg-gradient-to-r from-emerald-500 to-emerald-900">
                                        <h3 class="font-bold text-white text-s">MAKLUMAT WARGA KERJA</h3>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="grid grid-cols-3 gap-4">
                                        <p class="flex items-center font-semibold">Nama</p>
                                        <div class="block w-full col-span-2">
                                            <x-input placeholder="Nama" wire:model="pydName" disabled />
                                        </div>
                                        <p class="flex items-center font-semibold">Jawatan</p>
                                        <div class="block w-full col-span-2">
                                            <x-input placeholder="Jawatan" wire:model="pydPosition" disabled />
                                        </div>
                                        <p class="flex items-center font-semibold">Nombor Pekerja</p>
                                        <div class="block w-full col-span-2">
                                            <x-input placeholder="Nombor Pekerja" wire:model="staffNo" disabled />
                                        </div>
                                        <p class="flex items-center font-semibold">Negeri / Cawangan</p>
                                        <div class="flex col-span-2">
                                            <div class="block w-full">
                                                <x-input placeholder="Negeri" wire:model="selectedStateDescription" disabled />
                                            </div>
                                            <span class="flex items-center mx-2 font-bold">/</span>
                                            <div class="block w-full">
                                                <x-input placeholder="Branch" wire:model="selectedBranchDescription" disabled />
                                            </div>
                                        </div>
                                        <p class="flex items-center font-semibold">Tarikh Lantikan</p>
                                        <div class="block w-full col-span-2">
                                            <x-input placeholder="Tarikh Lantikan" wire:model="pydDateJoined" disabled />
                                        </div>
                                        <p class="flex items-center font-semibold">Tempoh Berkhidmat di Cawangan Semasa</p>
                                        <div class="block w-full col-span-2">
                                            <x-input placeholder="Tempoh Berkhidmat" wire:model="pydCurrentService" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- maklumat peribadi --}}
                            <div class="col-span-3">
                                <div class="mb-4">
                                    <div class="flex items-center justify-center border-4 border-gray-700 rounded-md bg-gradient-to-r from-emerald-500 to-emerald-900">
                                        <h3 class="font-bold text-white text-s">MAKLUMAT PERIBADI</h3>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="grid grid-cols-3 gap-4">
                                        <p class="flex items-center font-semibold">No Kad Pengenalan</p>
                                        <div class="block w-full col-span-2">
                                            <x-input placeholder="No Kad Pengenalan" wire:model="pydIc" disabled />
                                        </div>
                                        <p class="flex items-center font-semibold">No Telefon</p>
                                        <div class="block w-full col-span-2">
                                            <x-input placeholder="No Telefon" wire:model="pydPhoneNo" disabled />
                                        </div>
                                        <p class="flex items-start font-semibold">Alamat Kediaman</p>
                                        <div class="block w-full col-span-2">
                                            <x-textarea wire:model="pydAddress" placeholder="Alamat" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 mt-8 gap-y-10 gap-x-14">
                            {{-- maklumat sesi penilaian --}}
                            <div class="col-span-2">
                                <div class="mb-4">
                                    <div class="flex items-center justify-center border-4 border-gray-700 rounded-md bg-gradient-to-r from-emerald-500 to-emerald-900">
                                        <h3 class="font-bold text-white text-s">MAKLUMAT SESI PENILAIAN</h3>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="grid grid-cols-3 gap-4">
                                        <p class="flex items-center font-semibold">Jenis</p>
                                        <div class="block w-full col-span-2">
                                            <x-select
                                                placeholder="Sila Pilih"
                                                :options="[
                                                    ['name' => 'Fizikal',  'id' => 1],
                                                    ['name' => 'Dalam Talian', 'id' => 2],
                                                ]"
                                                option-label="name"
                                                option-value="id"
                                                wire:model.live="meetingType"
                                            />
                                        </div>
                                        @if($meetingType == 1)
                                            <p class="flex items-center font-semibold">Tempat</p>
                                            <div class="block w-full col-span-2">
                                                <x-input placeholder="Tempat" wire:model="venue" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- dihadiri oleh --}}
                            <div class="col-span-5">
                                <div class="mb-4">
                                    <div class="flex items-center justify-center border-4 border-gray-700 rounded-md bg-gradient-to-r from-emerald-500 to-emerald-900">
                                        <h3 class="font-bold text-white text-s">DIHADIRI OLEH</h3>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="grid grid-cols-7 gap-4">
                                        <p class="flex items-center col-span-2 font-semibold">Nama Penilai (PYM)</p>
                                        <div class="block w-full col-span-3">
                                            <x-input placeholder="Nama Penilai" wire:model="pymName" disabled />
                                        </div>
                                        <p class="flex items-center font-semibold">No Pekerja</p>
                                        <div class="block w-full">
                                            <x-input placeholder="Staff No Penilai" wire:model="pymStaffNo" disabled />
                                        </div>

                                        @if($pmgiLevel == 'PM3')
                                        <p class="flex items-center col-span-2 font-semibold">Nama Pemudah Cara (PMC)</p>
                                        <div class="block w-full col-span-3">
                                            <x-input placeholder="Nama Pemudah Cara" wire:model="pmcName" disabled />
                                        </div>
                                        <p class="flex items-center font-semibold">No Pekerja</p>
                                        <div class="block w-full">
                                            <x-input placeholder="Staff No Pemudah Cara" wire:model="pmcStaffNo" disabled />
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center mt-4">
                            <button class="inline-flex items-center p-5 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 " wire:click="startSession">
                                MULA SESI
                                <x-icon name="arrow-circle-right" class="w-6 h-6 ms-2" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
