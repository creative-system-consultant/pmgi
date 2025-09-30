<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Prestasi Kumulatif</h3>
                    <span class="text-base font-normal text-gray-500">Prestasi Warga Kerja (Kumulatif) Mengikut Bulan</span>
                    @if(!$pmgiSession)
                        <div class="p-6 mt-4 rounded-lg border shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                            @if(!hasRoles('PYD'))
                                <div class="grid grid-cols-8 gap-y-2 gap-x-4">
                                    <div class="col-span-2">
                                        <x-select label="Negeri" placeholder="Sila Pilih" :options="$stateSelection" option-label="description" option-value="code" wire:model.live="state" />
                                    </div>
                                    <div class="col-span-2">
                                        <x-select label="Cawangan" placeholder="Sila Pilih" :options="$branchSelection" option-label="branch_name" option-value="branch_code" wire:model.live="branch" />
                                    </div>
                                    <div class="col-span-2">
                                        <x-select
                                            label="Nama Pegawai"
                                            wire:model="searchTerm"
                                            placeholder="Sila Taip Nama"
                                            :async-data="route('staff-name-search-by-branch', ['branch_code' => $branch])"
                                            option-label="officer_name"
                                            option-value="officer_name"
                                        />
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
                                        <svg class="w-3.5 h-3.5 rtl:rotate-180 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-8 gap-y-2 gap-x-4">
                                    <div class="col-span-2">
                                        <x-datetime-picker label="Dari" placeholder="Dari" display-format="DD-MM-YYYY" wire:model="from" without-time />
                                    </div>
                                    <div class="col-span-2">
                                        <x-datetime-picker label="Hingga" placeholder="Hingga" display-format="DD-MM-YYYY" wire:model="to" without-time />
                                    </div>
                                    <div class="flex col-span-4 justify-end">
                                        <div wire:click="search" class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                            Cari
                                            <svg class="w-3.5 h-3.5 rtl:rotate-180 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Table -->
            @if($datas && $datas->count() > 0)
                @if(!$pmgiSession && !hasRoles('PYD'))
                    <div class="grid grid-cols-2 gap-x-6 mt-10 max-w-xl">
                        <span class="text-base font-normal text-gray-500">NAMA PEGAWAI</span>
                        <span class="text-base font-normal text-gray-500">: {{ $data->first()->officer_name ?? '' }}</span>
                        <span class="text-base font-normal text-gray-500">NEGERI / CAWANGAN</span>
                        <span class="text-base font-normal text-gray-500">: {{ $data->first()->negeri ?? '' }} / {{ $data->first()->cawangan ?? '' }}</span>
                        <span class="text-base font-normal text-gray-500">DARI - HINGGA</span>
                        <span class="text-base font-normal text-gray-500">: {{ strtoupper(\Carbon\Carbon::parse($fromReportDate)->translatedFormat('F Y')) }} - {{ strtoupper(\Carbon\Carbon::parse($toReportDate)->translatedFormat('F Y')) }}</span>
                    </div>
                @endif

                <div class="flex flex-col mt-6">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden pl-1">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr class="bg-gray-200">
                                            <th class="bg-white"></th>
                                            @foreach($datas as $data)
                                                <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                                    {{ $data->month_name }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
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
                                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 whitespace-nowrap border-l border-black border-dashed border-y">
                                                KUTIPAN TANPA KONTRAK I (Minimum {{ $this->percentage->get(0)->evaluation_percentage }}%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    PK (RM) P + C
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->rm_patut_kutip, 2) }}
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ $data->rm_dapat_kutip_pts }}%
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border border-l border-black border-dashed">
                                                    @if($data->rm_dapat_kutip_capai_flag == 'Y')
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-green-800 bg-green-100 rounded-md border-green-100">CAPAI</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-pink-800 bg-pink-100 rounded-md border-pink-100">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    DK (RM)
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
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
                                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 whitespace-nowrap border-l border-black border-dashed border-y">
                                                BILANGAN MEMBAYAR (Minimum {{ $this->percentage->get(1)->evaluation_percentage }}%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    BILANGAN SELIAAN
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_patut_kutip) }}
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_dapat_kutip_pts, 2) }}%
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border border-l border-black border-dashed">
                                                    @if($data->bil_dapat_kutip_capai_flag == 'Y')
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-green-800 bg-green-100 rounded-md border-green-100">CAPAI</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-pink-800 bg-pink-100 rounded-md border-pink-100">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    BILANGAN MEMBAYAR
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
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
                                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 whitespace-nowrap border-l border-black border-dashed border-y">
                                                LAWATAN SELIAAN (Minimum {{ $this->percentage->get(2)->evaluation_percentage }}%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    BILANGAN SELIAAN
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_selia) }}
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_lawat_pts, 2) }}%
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border border-l border-black border-dashed">
                                                    @if($data->bil_lawat_capai_flag == 'Y')
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-green-800 bg-green-100 rounded-md border-green-100">CAPAI</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-pink-800 bg-pink-100 rounded-md border-pink-100">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    JUMLAH LAWATAN
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
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
                                            <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-900 whitespace-nowrap border-l border-black border-dashed border-y">
                                                PRESTASI NPF (KAWALAN) (Minimum {{ $this->percentage->get(3)->evaluation_percentage }}%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    BILANGAN AKAUN A3 (5.01-6)
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_kawal_npf_sblm) }}
                                                </td>
                                                <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_kawal_npf_pts, 2) }}%
                                                </td>
                                                <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border border-l border-black border-dashed">
                                                    @if($data->bil_kawal_npf_capai_flag == 'Y')
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-green-800 bg-green-100 rounded-md border-green-100">CAPAI</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-pink-800 bg-pink-100 rounded-md border-pink-100">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    BERTUKAR B1
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_kawal_npf_tukar) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    BILANGAN KEKAL
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
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
                                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 whitespace-nowrap border-l border-black border-dashed border-y">
                                                PRESTASI NPF PEMULIHAN (Minimum {{ $this->percentage->get(4)->evaluation_percentage }}%)
                                            </td>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    BILANGAN AKAUN NPF
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_pulih_npf_sblm) }}
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_pulih_npf_pts, 2) }}%
                                                </td>
                                                <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border border-l border-black border-dashed">
                                                    @if($data->bil_pulih_npf_capai_flag == 'Y')
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-green-800 bg-green-100 rounded-md border-green-100">CAPAI</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-pink-800 bg-pink-100 rounded-md border-pink-100">TAK CAPAI</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($datas as $data)
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    BERTUKAR SEMASA
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed border-y">
                                                    {{ number_format($data->bil_pulih_npf_tukar) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
