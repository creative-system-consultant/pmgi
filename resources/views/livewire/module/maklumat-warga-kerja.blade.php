<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Maklumat Warga Kerja</h3>
                    <span class="text-base font-normal text-gray-500 ">Mulakan Sesi PMG-i bersama Warga Kerja</span>
                    <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between">
                            <div class="grid w-[70%] grid-cols-3 gap-x-4 gap-y-2">
                                <div>
                                    <label for="negeri" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Negeri</label>
                                    <input type="text" id="negeri" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label for="cawangan" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Cawangan</label>
                                    <input type="text" id="cawangan" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label for="bulan" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">No Pekerja</label>
                                    <input type="text" id="bulan" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
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
        </div>

        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center justify-between lg:flex">
                <div class="mb-4">
                    <table>
                        <tbody>
                            <tr>
                                <td class="p-2">
                                    <h3 class="font-bold text-gray-900 text-md ">Sesi PMG-i </h3>
                                </td>
                                <td class="p-2">
                                    <span class="px-4 py-2.5 text-sm font-medium text-amber-800 bg-amber-100 rounded me-2 border border-amber-500 animate-pulse">1</span>
                                </td>
                                <td class="p-2">
                                    <span class="px-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-100 rounded me-2 border border-gray-500">2</span>
                                </td>
                                <td class="p-2">
                                    <span class="px-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-100 rounded me-2 border border-gray-500">3</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-4">
                                    <h3 class="font-bold text-gray-900 text-md ">Sesi Timbang Tara </h3>
                                </td>
                                <td class="px-2 py-4">
                                    <span class="px-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-100 rounded me-2 border border-gray-500">1</span>
                                </td>
                                <td class="px-2 py-4">
                                    <span class="px-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-100 rounded me-2 border border-gray-500">2</span>
                                </td>
                            </tr>
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
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Jawatan</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Nombor Pekerja</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Negeri / Cawangan</p>
                            <div class="flex col-span-2">
                                <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                                <span class="flex items-center mx-2 font-bold">/</span>
                                <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            </div>
                            <p class="flex items-center font-semibold">Tarikh Lantikan</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Tempoh Berkhidmat di Cawangan Semasa</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
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
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">No Telefon</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Alamat Kediaman</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <div></div> {{-- dummy--}}
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <div></div> {{-- dummy--}}
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <div></div> {{-- dummy--}}
                            <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
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
                            <p class="flex items-center font-semibold">Tarikh</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Masa</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">Tempat</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                        </div>
                    </div>
                </div>
                {{-- dihadiri oleh --}}
                <div  class="col-span-5">
                    <div class="mb-4">
                        <div class="flex items-center justify-center border-4 border-gray-700 rounded-md bg-gradient-to-r from-emerald-500 to-emerald-900">
                            <h3 class="font-bold text-white text-s">DIHADIRI OLEH</h3>
                        </div>
                    </div>
                    <div class="">
                        <div class="grid grid-cols-7 gap-4">
                            <p class="flex items-center col-span-2 font-semibold">Nama Penilai (PYM)</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">No Pekerja</p>
                            <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <button class="inline-flex items-center justify-center px-3 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 ">
                                Cari
                                <x-icon name="search" class="w-4 h-4 ms-2"/>
                            </button>
                            <p class="flex items-center col-span-2 font-semibold">Nama Pemudah Cara (PMC)</p>
                            <input type="text" id="small-input" class="block w-full col-span-2 p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                            <p class="flex items-center font-semibold">No Pekerja</p>
                            <input type="text" id="small-input" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 ">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-4">
                <button class="inline-flex items-center p-5 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 ">
                    MULA SESI
                    <x-icon name="arrow-circle-right" class="w-6 h-6 ms-2" />
                </button>
            </div>
        </div>
    </div>
</main>
