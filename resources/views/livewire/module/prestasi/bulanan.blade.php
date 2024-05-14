@push('style')
<style>
    .table-container {
        height: 300px;
        overflow: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }


    .headcol {
        position: sticky;
        top: 0;
    }

    .headcol-clear {
        position: sticky;
        top: 0;
    }

    .headcol-pengurus {
        position: sticky;
        top: 0;
    }

    thead th:nth-child(1) {
        left: 0;
        z-index: 999;
    }

    tbody th:nth-child(1) {
        left: 0;
        z-index: 999;
    }

    thead th:nth-child(2) {
        left: 236px;
        z-index: 999;
    }

    tbody th:nth-child(2) {
        left: 236px;
        z-index: 999;
    }

    thead th:nth-child(3) {
        left: 409px;
        z-index: 999;
    }

    tbody th:nth-child(3) {
        left: 409px;
        z-index: 999;
    }

</style>
@endpush

<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Prestasi Bulanan</h3>
                    <span class="text-base font-normal text-gray-500 ">Ringkasan Prestasi Bulanan Pegawai </span>
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
                                    <label for="bulan" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Bulan</label>
                                    <input type="text" id="bulan" class="block w-full p-2 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </div>
                            <div class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                Cari
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table -->
            <div class="flex flex-col mt-6">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="table-scroll-container">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 ">
                                    <tr class="bg-gray-400 ">
                                        <th colspan="3" class="bg-white headcol-clear"></th>
                                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 1</th>
                                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 2</th>
                                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 3</th>
                                        <th scope="col" colspan="5" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 4</th>
                                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 5</th>
                                        <th scope="col" rowspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">STATUS</th>
                                    </tr>
                                    <tr class="bg-gray-300">
                                        <th colspan="3" class="bg-white headcol-clear"></th>
                                        <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PATUT KUTIP (RM)<br>VS<br>DAPAT KUTIP (RM)</th>
                                        <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">80%</th>
                                        <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PATUT KUTIP (BIL)<br>VS<br>DAPAT KUTIP (BIL)</th>
                                        <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">70%</th>
                                        <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">LAWATAN SELIAAN</th>
                                        <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">80%</th>
                                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PRESTASI NPF (KAWALAN)</th>
                                        <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">50%</th>
                                        <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PRESTASI NPF (PEMULIHAN)</th>
                                        <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">5%</th>
                                    </tr>
                                    <tr class="bg-gray-200">
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">NAMA PEGAWAI</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">GELARAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 whitespace-nowrap headcol">TARAF JAWATAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">PATUT KUTIP<br>(RM)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">DAPAT KUTIP<br>(RM)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">%</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">PENILAIAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">PATUT KUTIP<br>(BIL)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">DAPAT KUTIP<br>(BIL)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">%</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">PENILAIAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">SELIAAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">LAWATAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">%</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">PENILAIAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">A3 (5.01 - 6.0)<br>(BLN SBLM)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">BERTUKAR B1<br>(BLN SEMASA)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">BILANGAN KEKAL</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">KAWALAN (%)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">PENILAIAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">NPF<br>(BLN SEBELUM)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">BERTUKAR SEMASA<br>(BLN SEMASA)</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">% PEMULIHAN</th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">PENILAIAN</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white ">
                                    <tr>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 bg-lime-300 headcol-pengurus whitespace-nowrap">NUR AMIRAH BINTI ABU BAKAR</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 bg-lime-300 headcol-pengurus whitespace-nowrap ">PENGURUS CAWANGAN</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 bg-lime-300 headcol-pengurus whitespace-nowrap">TETAP</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">0</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">0</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap"></th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">
                                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 bg-white headcol-clear whitespace-nowrap">ABDUL RASYID BIN ABDUL GHANI</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 bg-white headcol-clear whitespace-nowrap">PEMBANTU PEGAWAI</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 bg-white headcol-clear whitespace-nowrap">TETAP</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">75,042.95</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">63,132.60</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">84.13%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">257</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">184</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">71.60%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">257</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">253</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">98.44%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">3</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">1</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">2</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">66.67%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">64</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">2</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">3.13%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">Y</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">
                                            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-red-100 ">TAK CAPAI</span>
                                        </th>
                                    </tr>
                                    <tr class="bg-gray-200 border-black border-y">
                                        <th scope="col" colspan="3" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 bg-gray-200 headcol whitespace-nowrap">BENTONG TOTAL</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">75,042.95</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">63,132.60</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">84.13%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">257</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">184</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">71.60%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">257</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">253</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">98.44%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">3</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">1</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">2</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">66.67%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">64</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">2</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">3.13%</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">Y</th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">TAK CAPAI</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
