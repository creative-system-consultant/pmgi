<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Prestasi Kumulatif</h3>
                    <span class="text-base font-normal text-gray-500 ">Prestasi Warga Kerja (Kumulatif) Mengikut Bulan</span>
                        @if(!$pmgiSession && !hasRoles('PYD'))
                        <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                            <div class="grid grid-cols-8 gap-x-4 gap-y-2">
                                <div class="col-span-2 ">
                                    <x-select label="Negeri" placeholder="Sila Pilih" :options="$stateSelection" option-label="description" option-value="code" wire:model.live="state" />
                                </div>
                                <div class="col-span-2 ">
                                    <x-select label="Cawangan" placeholder="Sila Pilih" :options="$branchSelection" option-label="branch_name" option-value="branch_code" wire:model="branch" />
                                </div>
                                <div class="col-span-2 ">
                                    <x-input label="Nama Pegawai @ No Pekerja" placeholder="Sila Taip Nama @ No Pekerja pegawai" wire:model="searchTerm" />
                                </div>
                                <div>
                                    <x-datetime-picker label="Dari" placeholder="Dari" display-format="DD-MM-YYYY" wire:model="from" without-time />
                                </div>
                                <div>
                                    <x-datetime-picker label="Hingga" placeholder="Hingga" display-format="DD-MM-YYYY" wire:model="to" without-time />
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <div wire:click="search" class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    Cari
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Table -->
            @if($datas && $datas->count() > 0)
                @if(!$pmgiSession && !hasRoles('PYD'))
                    <div class="grid max-w-xl grid-cols-2 mt-10 gap-x-6">
                        <span class="text-base font-normal text-gray-500 ">NAMA PEGAWAI</span>
                        <span class="text-base font-normal text-gray-500 ">: {{ $data->first()->officer_name ?? '' }}</span>
                        <span class="text-base font-normal text-gray-500 ">NEGERI / CAWANGAN</span>
                        <span class="text-base font-normal text-gray-500 ">: {{ $data->first()->negeri ?? '' }} / {{ $data->first()->cawangan ?? '' }}</span>
                        <span class="text-base font-normal text-gray-500 ">DARI - HINGGA</span>
                        <span class="text-base font-normal text-gray-500 ">: {{ strtoupper(\Carbon\Carbon::parse($fromReportDate)->translatedFormat('F Y')) }} - {{ strtoupper(\Carbon\Carbon::parse($toReportDate)->translatedFormat('F Y')) }}</span>
                    </div>
                @endif

                <div class="flex flex-col mt-6">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="pl-1 overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 ">
                                    <thead class="bg-gray-50 ">
                                        <tr class="bg-gray-200">
                                            <th class="bg-white"></th>
                                            @foreach($datas as $data)
                                                <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x ">
                                                    {{ $data->month_name }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white ">
                                        {{-- kriteria 1 --}}
                                        <tr class="bg-gray-100">
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed border-y">
                                                KRITERIA 1
                                            </th>
                                            @foreach($datas as $data)
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PERKARA
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PRESTASI
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    %
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                                    STATUS
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 border-l border-black border-dashed border-y whitespace-nowrap">
                                                KUTIPAN TANPA KONTRAK I (Minimum 80%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    PK (RM) P + C
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->rm_patut_kutip, 2) }}
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ $data->rm_dapat_kutip_pts }}%
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-l border-black border-dashed whitespace-nowrap">
                                                    @if($data->rm_dapat_kutip_capai_flag == 'Y')
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                                    @else
                                                        <span class="bg-pink-100 text-pink-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-pink-100 ">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    DK (RM)
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed whitespace-nowrap border-y">
                                                    {{ number_format($data->rm_dapat_kutip, 2) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                        {{-- kriteria 2 --}}
                                        <tr class="bg-gray-100">
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed border-y">
                                                KRITERIA 2
                                            </th>
                                            @foreach($datas as $data)
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PERKARA
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PRESTASI
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    %
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                                    STATUS
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 border-l border-black border-dashed border-y whitespace-nowrap">
                                                BILANGAN MEMBAYAR (Minimum 80%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    BILANGAN SELIAAN
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->bil_patut_kutip) }}
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->bil_dapat_kutip_pts, 2) }}%
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-l border-black border-dashed whitespace-nowrap">
                                                    @if($data->bil_dapat_kutip_capai_flag == 'Y')
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                                    @else
                                                        <span class="bg-pink-100 text-pink-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-pink-100 ">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    BILANGAN MEMBAYAR
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed whitespace-nowrap border-y">
                                                    {{ number_format($data->bil_dapat_kutip) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                        {{-- kriteria 3 --}}
                                        <tr class="bg-gray-100">
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed border-y">
                                                KRITERIA 3
                                            </th>
                                            @foreach($datas as $data)
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PERKARA
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PRESTASI
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    %
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                                    STATUS
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 border-l border-black border-dashed border-y whitespace-nowrap">
                                                LAWATAN SELIAAN (Minimum 80%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    BILANGAN SELIAAN
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->bil_selia) }}
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->bil_lawat_pts, 2) }}%
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-l border-black border-dashed whitespace-nowrap">
                                                    @if($data->bil_lawat_capai_flag == 'Y')
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                                    @else
                                                        <span class="bg-pink-100 text-pink-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-pink-100 ">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    JUMLAH LAWATAN
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed whitespace-nowrap border-y">
                                                    {{ number_format($data->bil_lawat) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                        {{-- kriteria 4 --}}
                                        <tr class="bg-gray-100">
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed border-y">
                                                KRITERIA 4
                                            </th>
                                            @foreach($datas as $data)
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PERKARA
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PRESTASI
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    %
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                                    STATUS
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-900 border-l border-black border-dashed border-y whitespace-nowrap">
                                                PRESTASI NPF (KAWALAN) (Minimum 50%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    BILANGAN AKAUN A3 (5.01-6)
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->bil_kawal_npf_sblm) }}
                                                </td>
                                                <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->bil_kawal_npf_pts, 2) }}%
                                                </td>
                                                <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-500 border border-l border-black border-dashed whitespace-nowrap">
                                                    @if($data->bil_kawal_npf_capai_flag == 'Y')
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                                    @else
                                                        <span class="bg-pink-100 text-pink-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-pink-100 ">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    BERTUKAR B1
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed whitespace-nowrap border-y">
                                                    {{ number_format($data->bil_kawal_npf_tukar) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    BILANGAN KEKAL
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed whitespace-nowrap border-y">
                                                    {{ number_format($data->bil_kawal_npf_kekal) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                        {{-- kriteria 5 --}}
                                        <tr class="bg-gray-100">
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed border-y">
                                                KRITERIA 5
                                            </th>
                                            @foreach($datas as $data)
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PERKARA
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    PRESTASI
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                                    %
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                                    STATUS
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 border-l border-black border-dashed border-y whitespace-nowrap">
                                                PRESTASI NPF PEMULIHAN (Minimum 5%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    BILANGAN AKAUN NPF
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->bil_pulih_npf_sblm) }}
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    {{ number_format($data->bil_pulih_npf_pts) }}%
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-l border-black border-dashed whitespace-nowrap">
                                                    @if($data->bil_pulih_npf_capai_flag == 'Y')
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                                    @else
                                                        <span class="bg-pink-100 text-pink-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-pink-100 ">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed border-y whitespace-nowrap">
                                                    BERTUKAR SEMASA
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border-l border-black border-dashed whitespace-nowrap border-y">
                                                    {{ number_format($data->bil_pulih_npf_tukar) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="flex p-4 mt-4 text-xs text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                                    <x-icon name="information-circle" class="w-5 h-5 mr-4 text-blue-800" />
                                    NOTA : Bagi Kriteria 4, % purata adalah Total Bilangan Akaun Kekal A3 untuk 2 Bulan Penilaian / Total Bilangan Akaun A3 untuk 2 Bulan Penilaian
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
