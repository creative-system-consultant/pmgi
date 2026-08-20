<div class="h-full">
    @if ($rows->count() > 0)
        <div class="table-container">
            <table>
                <thead class="sticky top-0 z-10 bg-gray-50">
                    <tr class="bg-gray-400">
                        <th class="bg-white headcol"></th>
                        @foreach ($months as $month)
                        <th scope="col" colspan="6" class="p-2 text-xs font-medium tracking-tight text-center text-gray-800 uppercase border-black border-dashed border-x">{{ $month }}</th>
                        @endforeach
                    </tr>
                    <tr class="z-40 bg-gray-200">
                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">NAMA PEGAWAI<br>GELARAN</th>
                        @foreach ($months as $month)
                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase whitespace-nowrap border-black border-dashed border-x">1<br>(PK / DK)</th>
                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase whitespace-nowrap border-black border-dashed border-x">2<br>(BILANGAN)</th>
                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase whitespace-nowrap border-black border-dashed border-x">3<br>(BIL LAWATAN)</th>
                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase whitespace-nowrap border-black border-dashed border-x">4<br>(BIL NPF KAWALAN)</th>
                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase whitespace-nowrap border-black border-dashed border-x">5<br>(BIL NPF PEMULIHAN)</th>
                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase whitespace-nowrap border-black border-dashed border-x">STATUS</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white">
                    {{-- One entry per pegawai, already ordered negeri > cawangan by
                         PrestasiBulananRingkasanService. --}}
                    @foreach ($rows as $records)
                                <tr class="@if($records->first()->incl_pmgi_flag == 'W') bg-gray-800 @elseif($records->first()->incl_pmgi_flag == 'S') bg-gray-500 @elseif($records->first()->incl_pmgi_flag == 'N') bg-gray-200 @endif">
                                    <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 @if($records->first()->incl_pmgi_flag == 'W') bg-gray-800 @elseif($records->first()->incl_pmgi_flag == 'S') bg-gray-500 @elseif($records->first()->incl_pmgi_flag == 'N') bg-gray-200 @else bg-white @endif headcol whitespace-nowrap">
                                        @if ($records->first()->incl_pmgi_flag == 'W')
                                            <p class="font-semibold text-white">KESELURUHAN TOTAL</p>
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            <p class="font-semibold text-white">{{ $records->first()->negeri }} TOTAL</p>
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            <p class="font-semibold text-gray-500">{{ $records->first()->branch->branch_name }} TOTAL</p>
                                        @else
                                            {{ $records->first()->officer_name }}<br>
                                            @if ($records->first()->incl_pmgi_flag == 'J')
                                                <p class="text-xs text-red-600">BERHENTI PADA {{ \Carbon\Carbon::parse($records->first()->officer_resign_date)->format('d/m/Y') }}</p>
                                            @elseif ($records->first()->incl_pmgi_flag == 'G')
                                                <p class="text-xs text-red-600">PINDAH KE {{ $records->first()->officerBranch->branch_name }}</p>
                                            @else
                                            <p class="text-xs text-gray-600">{{ filled($records->first()->fmsBankOfficers?->hr_officer_position) ? $records->first()->fmsBankOfficers?->hr_officer_position : $records->first()->officer_position }}</p>
                                            @endif
                                        @endif
                                    </th>
                                    @foreach ($months as $month)
                                        @php
                                        $record = $records->firstWhere('report_date', $month);
                                        @endphp
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->rm_dapat_kutip_pts ? number_format($record->rm_dapat_kutip_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_dapat_kutip_pts ? number_format($record->bil_dapat_kutip_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_lawat_pts ? number_format($record->bil_lawat_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_kawal_npf_pts ? number_format($record->bil_kawal_npf_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_pulih_npf_pts ? number_format($record->bil_pulih_npf_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            @if($record && $record->pmgi_capai_flag == 'Y')
                                                <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-green-800 bg-green-100 rounded-md border-green-100">CAPAI</span>
                                            @elseif($record)
                                                <span class="px-2.5 py-0.5 mr-2 text-xs font-medium text-red-800 bg-red-100 rounded-md border-red-100">TIDAK CAPAI</span>
                                            @else
                                                -
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
