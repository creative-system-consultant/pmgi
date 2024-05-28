<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-center text-gray-900">Perakuan Pegawai Yang Dinilai (PYD)</h3>
                </div>
            </div>

            <div class="w-1/2 mx-auto mt-8">
                <p class="mb-8 text-xl text-gray-600" style="text-indent: 4rem;text-align: justify;">
                    Saya <span class="font-semibold underline">WAN MOHAMAD RIDZUAN BIN WAN JAAPAR</span> No. Kad Pengenalan <span class="font-semibold underline">910506-11-5087</span> , Nombor Pekerja <span class="font-semibold underline">4227</span> daripada Cawangan telah ditetapkan dan dipersetujui oleh saya di dalam Borang Prestasi (PMG-i) ini dalam masa dua (2) bulan mulai - <span class="font-semibold underline">AUG 2024</span> sehingga <span class="font-semibold underline">SEP 2024</span> . Jika saya masih gagal meningkatkan prestasi dan / atau mencapai sasaran seperti yang dinyatakan di dalam Borang Pemantauan Prestasi (PMG-i) ini, maka saya bersetuju bahawa saya boleh dikenakan tindakan tatatertib mengikut Manual Prosedur Pentadbiran dan Sumber Manusia, TEKUN Nasional (Etika Kerja - Kod Tatakelakuan termasuk dibuang kerja).
                </p>
                {{-- PYD --}}
                {{-- <div class="p-6 my-4 bg-green-100 border border-green-400 rounded-lg shadow">
                    <div class="flex">
                        <div class="mx-auto">
                            <p class="mb-4 font-semibold text-center">Sila masukkan nombor Kad Pengenalan<br>tuan / puan untuk pengesahan :</p>
                            <input type="text" id="small-input" class="block w-full p-2 font-semibold text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 ">
                        </div>
                    </div>
                </div> --}}
                {{-- PYM --}}
                {{-- <div class="p-6 my-4 border rounded-lg shadow bg-amber-100" style="--tw-border-opacity: 1;border-color: rgb(251 191 36 / var(--tw-border-opacity));">
                    <div class="flex">
                        <div class="mx-auto">
                            <p class="mb-4 font-semibold text-center">Sila masukkan nombor Kad Pengenalan tuan / puan<br>untuk pengesahan penilaian bagi PYD tersebut:</p>
                            <input type="text" id="small-input" class="block w-full p-2 font-semibold text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 ">
                        </div>
                    </div>
                </div> --}}
                {{-- PMC --}}
                <div class="mt-4 mb-8">
                    <div class="mb-2">
                        <label for="punca" class="block mb-2 font-semibold text-gray-900 text-md dark:text-white">Sila pilih keputusan penilaian PMC :</label>
                        <div class="flex">
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input id="pmc1" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pmc1" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">DIBERI TEMPOH</label>
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input id="pmc1" type="radio" value="1" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pmc1" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">KELUAR SENARAI</label>
                            </div>
                            <div class="flex items-center mr-4 border border-gray-200 rounded ps-4" style="padding-left: 2rem;padding-right: 2rem;">
                                <input checked id="pmc2" type="radio" value="2" name="bordered-radio" class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="pmc2" class="w-full py-4 text-sm font-medium text-gray-900 ms-2 ">DOMESTIC INQUIRY</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="punca" class="block mb-2 font-medium text-gray-900 text-md dark:text-white">Sila tuliskan ulasan (Jika Perlu) :</label>
                        <textarea id="punca" rows="3" class="block p-2.5 w-full text-md text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                    </div>
                </div>
                <div class="p-6 my-4 bg-gray-200 border border-gray-400 rounded-lg shadow ">
                    <div class="flex">
                        <div class="mx-auto">
                            <p class="mb-4 font-semibold text-center">Sila masukkan nombor Kad Pengenalan tuan / puan<br>untuk pengesahan penilaian bagi PYD tersebut:</p>
                            <input type="text" id="small-input" class="block w-full p-2 font-semibold text-center text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 ">
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                        Hantar
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
