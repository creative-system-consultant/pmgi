<div class="flex flex-col h-full">
    <div class="flex-grow overflow-x-auto overflow-y-hidden">
        <div class="inline-block h-full min-w-full align-middle">
            <div class="h-full overflow-auto">
                <table class="min-w-full divide-y divide-gray-200 table-auto">
                    <thead class="sticky top-0 z-10 bg-gray-50">
                        <tr class="bg-gray-400">
                            <th class="bg-white headcol"></th>
                            @foreach ($months as $month)
                            <th scope="col" colspan="6" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">{{ $month }}</th>
                            @endforeach
                        </tr>
                        <tr class="z-40 bg-gray-200">
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">NAMA PEGAWAI<br>GELARAN</th>
                            @foreach ($months as $month)
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x whitespace-nowrap">1<br>(PK / DK)</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x whitespace-nowrap">2<br>(BILANGAN)</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x whitespace-nowrap">3<br>(LAWATAN)</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x whitespace-nowrap">4<br>(NPF KAWALAN)</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x whitespace-nowrap">5<br>(NPF PEMULIHAN)</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x whitespace-nowrap">STATUS</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($groupedData as $stateData)
                            @foreach ($stateData as $branchData)
                                @foreach ($branchData as $officerId => $records)
                                    <tr class="@if($records->first()->incl_pmgi_flag == 'W') bg-gray-800 @elseif($records->first()->incl_pmgi_flag == 'S') bg-gray-500 @elseif($records->first()->incl_pmgi_flag == 'N') bg-gray-200 @endif">
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 headcol whitespace-nowrap">
                                            @if ($records->first()->incl_pmgi_flag == 'W')
                                                <p class="font-semibold text-white ">KESELURUHAN TOTAL</p>
                                            @elseif ($records->first()->incl_pmgi_flag == 'S')
                                                <p class="font-semibold text-white ">{{ $records->first()->negeri }} TOTAL</p>
                                            @elseif ($records->first()->incl_pmgi_flag == 'N')
                                                <p class="font-semibold text-gray-500">{{ $records->first()->branch->branch_name }} TOTAL</p>
                                            @else
                                                {{ $records->first()->officer_name }}<br>
                                                @if ($records->first()->incl_pmgi_flag == 'J')
                                                    <p class="text-xs text-red-600">BERHENTI PADA {{ \Carbon\Carbon::parse($records->first()->officer_resign_date)->format('d/m/Y') }}</p>
                                                @elseif ($records->first()->incl_pmgi_flag == 'G')
                                                    <p class="text-xs text-red-600">PINDAH KE {{ $records->first()->officerBranch->branch_name }}</p>
                                                @else
                                                    <p class="text-xs text-gray-600">{{ $records->first()->officer_position }}</p>
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
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100">CAPAI</span>
                                                @elseif($record)
                                                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-red-100">TIDAK CAPAI</span>
                                                @else
                                                    -
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
