<div>
    <div class="px-4 pt-6 2xl:px-0">
        {{-- ringkasan --}}
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="justify-between items-center lg:flex">
                <div class="mb-4 lg:mb-0">
                    @if (!$userId)
                        <h3 class="mb-2 text-xl font-bold text-gray-900">Ringkasan</h3>
                        <span class="text-base font-normal text-gray-500">Ini adalah ringkasan status PMG-i anda</span>
                    @endif
                    <div class="grid grid-cols-2 gap-y-0 gap-x-8 mt-4 text-xs text-gray-700 uppercase">
                        <h3>NAMA</h3>
                        <P>{{ $username }}</P>
                        <h3>NO PEKERJA</h3>
                        <P>{{ $staffno }}</P>
                        <h3>JAWATAN</h3>
                        <P>{{ $jawatan }}</P>
                        <h3>NEGERI</h3>
                        <P>{{ $stateName }}</P>
                        <h3>CAWANGAN</h3>
                        <P>{{ $branchName }}</P>
                        <h3>TARIKH LANTIKAN</h3>
                        <P>{{ $tarikhLantikan }}</P>
                        <h3>TEMPOH KHIDMAT DI CAWANGAN SEMASA</h3>
                        <P>{{ $tempohBerkhidmat }}</P>
                    </div>
                </div>

                @if ($data->bankOfficer->hr_mgr_flag == 'Y')
                    <div class="items-center">
                        <div class="overflow-hidden shadow-md sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-indigo-400">
                                    <tr>
                                        <th scope="col" class="p-4 text-xs font-medium tracking-wider text-center text-black uppercase">
                                            BIL. PP
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium tracking-wider text-center text-black uppercase">
                                            BIL. SEMASA
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium tracking-wider text-center text-black uppercase">
                                            BIL. NPF
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium tracking-wider text-center text-black uppercase">
                                            JUMLAH
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium tracking-wider text-center text-black uppercase">
                                            RATIO / PP
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr>
                                        <td class="p-4 text-sm font-normal text-center text-gray-900 whitespace-nowrap">
                                            2
                                        </td>
                                        <td class="p-4 text-sm font-normal text-center text-gray-900 whitespace-nowrap">
                                            293
                                        </td>
                                        <td class="p-4 text-sm font-normal text-center text-gray-900 whitespace-nowrap">
                                            327
                                        </td>
                                        <td class="p-4 text-sm font-normal text-center text-gray-900 whitespace-nowrap">
                                            620
                                        </td>
                                        <td class="p-4 text-sm font-normal text-center text-gray-900 whitespace-nowrap">
                                            310
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Table -->
            <div class="flex flex-col mt-6">
                @if($officerDatas->isNotEmpty())
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr class="bg-gray-200">
                                        <th class="bg-white" colspan="3"></th>
                                        <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            BILANGAN
                                        </th>
                                        <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            KUTIPAN TANPA KONTRAK-I
                                        </th>
                                        <th scope="col" colspan="2" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            KUTIPAN KONTRAK-I
                                        </th>
                                        <th scope="col" colspan="2" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            LAWATAN
                                        </th>
                                        @if ($data->bankOfficer->hrData->jawatan != 'PEMBANTU PEGAWAI')
                                            <th scope="col" colspan="2" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x">
                                                PRESTASI NPF
                                            </th>
                                        @endif
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                            BULAN
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                            STATUS<br>PMG-I
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                            KEPUTUSAN
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-l border-black border-dashed">
                                            BIL.<br>SELIAAN
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                            BIL<br>MEMBAYAR
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-r border-black border-dashed">
                                            %<br>MEMBAYAR
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                            PK (RM)
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                            DK (RM)
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-r border-black border-dashed">
                                            % DK
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                            BIL
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-r border-black border-dashed">
                                            RM
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                            BIL<br>LAWATAN
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-r border-black border-dashed">
                                            %<br>LAWAT
                                        </th>
                                        @if ($data->bankOfficer->hrData->jawatan != 'PEMBANTU PEGAWAI')
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase">
                                                OS - RM (% NPF)<br>≥ 2015
                                            </th>
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-r border-black border-dashed">
                                                BEZA SASARAN NPF<br>≥2015 @ ≤25%
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($officerDatas as $index => $officerData)
                                    <tr class="{{ $index % 2 != 0 ? 'bg-gray-50' : '' }}">
                                        <td class="p-2 text-xs font-normal text-center text-gray-900 uppercase whitespace-nowrap">
                                            {{ $officerData->report_date }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap">
                                            @if ($officerData->is_monitoring_period)
                                                TEMPOH<br>PEMANTAUAN
                                            @elseif (isset($pmgiLevels[$officerData->pmgi_level]))
                                                <p class="font-bold text-red-600">{{ $pmgiLevels[$officerData->pmgi_level] }}</p>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-2 text-xs font-semibold text-center text-gray-900 whitespace-nowrap">
                                            @isset($pmgiResults[$officerData->pmgi_level][$officerData->pmgi_result])
                                                <div class="{{ $pmgiResults[$officerData->pmgi_level][$officerData->pmgi_result]['class'] }} text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-yellow-100">
                                                    {!! $pmgiResults[$officerData->pmgi_level][$officerData->pmgi_result]['text'] !!}
                                                </div>
                                            @elseif(in_array($officerData->pmgi_level, ['PM1', 'PM2']))
                                                @if($officerData->pmgi_result == 'NCD')
                                                    <div class="px-2.5 py-0.5 mr-2 text-xs font-medium text-red-800 bg-red-100 rounded-md border-red-100">TIDAK<br>DILAKSANA</div>
                                                @else
                                                    <div class="px-2.5 py-0.5 mr-2 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-md border-yellow-100">SELESAI<br>DILAKSANA</div>
                                                @endif
                                            @endisset
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap border-l border-black border-dashed">
                                            {{ number_format($officerData->bil_selia) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($officerData->bil_dapat_kutip) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap border-r border-black border-dashed">
                                            {{ ($officerData->bil_selia) ? number_format(($officerData->bil_dapat_kutip / $officerData->bil_selia) * 100, 2) : 0 }}%
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($officerData->rm_patut_kutip, 2) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($officerData->rm_dapat_kutip, 2) ?? 0}}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap border-r border-black border-dashed">
                                            {{ number_format($officerData->rm_dapat_kutip_pts, 2) ?? 0 }}%
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($officerData->bil_dapat_kutip_nilai_pts) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap border-r border-black border-dashed">
                                            {{ number_format($officerData->rm_dapat_kutip_nilai_pts) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($officerData->bil_lawat) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap border-r border-black border-dashed">
                                            {{ number_format($officerData->bil_lawat_pts) ?? 0 }}%
                                        </td>
                                        @if ($data->bankOfficer->hrData->jawatan != 'PEMBANTU PEGAWAI')
                                            <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap">
                                                -
                                            </td>
                                            <td class="p-2 text-xs font-normal text-center text-gray-500 whitespace-nowrap border-r border-black border-dashed">
                                                -
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- chart --}}
        <div class="grid gap-4 xl:grid-cols-2 2xl:grid-cols-3">
            <!-- line chart -->
            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm 2xl:col-span-1 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex-shrink-0">
                        <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl">Bil. Seliaan vs Bil. Membayar</span>
                    </div>
                </div>
                @if($officerDatas->isNotEmpty())
                <div id="bil-bayar-chart"></div>
                @else
                <img src="{{ asset('image/illustrations/no-data.svg') }}" alt="astronaut image">
                @endif
            </div>
            <!-- Bar chart -->
            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
                <div class="justify-between items-center pb-4sm:flex">
                    <div class="mb-4 w-full sm:mb-0">
                        <span class="text-2xl font-bold leading-none text-gray-900 sm:text-2xl">Patut Kutip vs Dapat Kutip</span>
                    </div>
                </div>
                @if($officerDatas->isNotEmpty())
                <div class="w-full" id="pk-dk-chart"></div>
                @else
                <img src="{{ asset('image/illustrations/no-data.svg') }}" alt="astronaut image">
                @endif
            </div>
            <!-- pie chart -->
            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
                <div class="flex justify-between items-center pb-4 mb-4">
                    <div>
                        <span class="text-2xl font-bold leading-none text-gray-900 sm:text-2xl">Lawatan</span>
                    </div>
                </div>
                @if($officerDatas->isNotEmpty())
                <div id="lawatan-chart"></div>
                @else
                <img src="{{ asset('image/illustrations/no-data.svg') }}" alt="astronaut image">
                @endif
            </div>
        </div>

        {{-- table penjadualan semula --}}
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="justify-between items-center lg:flex">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Pelaksanaan Penjadualan Semula 2.0</h3>
                </div>
            </div>
            <!-- Table -->
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr class="bg-gray-100">
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            TERIMA
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            LULUS
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            TOLAK
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            BAKI
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            JANA
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            JUM. TERIMA (RM)
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            JUM. LULUS (RM)
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            JUM. TOLAK (RM)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($penjadualanSemula?->terima ?? 0) }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($penjadualanSemula?->lulus ?? 0) }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($penjadualanSemula?->tolak ?? 0) }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($penjadualanSemula?->baki ?? 0) }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($penjadualanSemula?->jana ?? 0) }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($penjadualanSemula?->jumterima ?? 0, 2) }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($penjadualanSemula?->jumlulus ?? 0, 2) }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($penjadualanSemula?->jumtolak ?? 0, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- table pelaksanaan mia --}}
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="justify-between items-center lg:flex">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Pelaksanaan MIA</h3>
                </div>
            </div>
            <!-- Table -->
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr class="bg-gray-100">
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            JUMLAH
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            LULUS
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            PROSES
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            TOLAK
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            DIKEMBALIKAN
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($mia?->jumlah) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($mia?->lulus) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($mia?->proses) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($mia?->tolak) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($mia?->dikembalikan) ?? 0 }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- wilma report --}}
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="justify-between items-center lg:flex">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900">WILMA Report</h3>
                </div>
            </div>
            <!-- Table -->
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr class="bg-gray-100">
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                            A1
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            A2
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            A3
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            B1
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            B2
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            C1
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            C2
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            D
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border-black border-dashed border-x">
                                            JUMLAH
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-white uppercase bg-red-500 border-white border-dotted border-x">
                                            BIL (NPF)
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-white uppercase bg-red-500 border-white border-dotted border-x">
                                            BIL (% NPF)
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-white uppercase bg-red-500 border-white border-dotted border-x">
                                            OS - RM (% NPF)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($wilma?->bila1) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->bila2) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->bila3) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->bilb1) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->bilb2) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->bilc1) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->bilc2) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->bild) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->jumlah) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->bilnpf) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap border-black border-dashed border-x">
                                            {{ number_format($wilma?->pctnpf, 2) ?? 0 }}
                                        </td>
                                        <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                            {{ number_format($wilma?->pctjumnpf, 2) ?? 0 }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- pembiayaan --}}
        <div class="p-4 my-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
            <!-- Card header -->
            <div class="justify-between items-center lg:flex">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900">Pembiayaan</h3>
                </div>
            </div>
            <!-- Table -->
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr class="bg-gray-100">
                                        <th class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">PRODUK</th>
                                        <th class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">BIL PEMINJAM</th>
                                        <th class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">BIL AKAUN</th>
                                        <th class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">AMAUN (RM)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($pembiayaan as $category)
                                        <tr>
                                            <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap bg-white">{{ $category->PRODUCT_DESC }}</td>
                                            <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap bg-white">{{ $category->bil_peminjam }}</td>
                                            <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap bg-white">{{ $category->bilakaun }}</td>
                                            <td class="p-2 text-sm font-normal text-center text-gray-500 whitespace-nowrap bg-white">{{ number_format($category->jumlah_pembiayaan, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.chartData = @json($officerDatasAsc);
    </script>
</div>
