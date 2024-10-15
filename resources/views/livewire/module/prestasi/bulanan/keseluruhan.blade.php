<div class="h-full ">
    @if ($groupedData->count() > 0)
        <div class="table-container">
            <table>
                <thead class="sticky top-0 z-10 bg-gray-50">
                    <tr class="bg-gray-400 ">
                        <th class="bg-white headcol"></th>
                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 1</th>
                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 2</th>
                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 3</th>
                        <th scope="col" colspan="5" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 4</th>
                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 5</th>
                        <th scope="col" rowspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">STATUS</th>
                    </tr>
                    <tr class="bg-gray-300">
                        <th class="bg-white headcol"></th>
                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PATUT KUTIP (RM)<br>VS<br>DAPAT KUTIP (RM)</th>
                        {{-- <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->rm_dapat_kutip_nilai_pts }}%</th> --}}
                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PATUT KUTIP (BIL)<br>VS<br>DAPAT KUTIP (BIL)</th>
                        {{-- <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->bil_dapat_kutip_nilai_pts }}%</th> --}}
                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">LAWATAN SELIAAN</th>
                        {{-- <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->bil_lawat_nilai_pts }}%</th> --}}
                        <th scope="col" colspan="5" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PRESTASI NPF (KAWALAN)</th>
                        {{-- <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->bil_kawal_npf_nilai_pts }}%</th> --}}
                        <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PRESTASI NPF (PEMULIHAN)</th>
                        {{-- <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->bil_pulih_npf_nilai_pts }}%</th> --}}
                    </tr>
                    <tr class="bg-gray-200">
                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">NAMA PEGAWAI<br>GELARAN</th>
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
                    @foreach ($groupedData as $stateData)
                        @foreach ($stateData as $branchData)
                            @foreach ($branchData as $officerId => $records)
                                @foreach($records as $record)
                                    <tr class="@if($record->incl_pmgi_flag == 'W') bg-gray-800 @elseif($record->incl_pmgi_flag == 'S') bg-gray-500 @elseif($record->incl_pmgi_flag == 'N') bg-gray-200 @endif">
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 @if($record->incl_pmgi_flag == 'W') bg-gray-800 @elseif($record->incl_pmgi_flag == 'S') bg-gray-500 @elseif($record->incl_pmgi_flag == 'N') bg-gray-200 @else bg-white @endif whitespace-nowrap">
                                            @if ($record->incl_pmgi_flag == 'W')
                                            <p class="font-semibold text-white ">KESELURUHAN TOTAL</p>
                                            @elseif ($record->incl_pmgi_flag == 'S')
                                            <p class="font-semibold text-white ">{{ $record->negeri }} TOTAL</p>
                                            @elseif ($record->incl_pmgi_flag == 'N')
                                            <p class="font-semibold text-gray-500">{{ $record->branch->branch_name }} TOTAL</p>
                                            @else
                                            {{ $record->officer_name }}<br>
                                            @if ($record->incl_pmgi_flag == 'J')
                                            <p class="text-xs text-red-600">BERHENTI PADA {{ \Carbon\Carbon::parse($record->officer_resign_date)->format('d/m/Y') }}</p>
                                            @elseif ($record->incl_pmgi_flag == 'G')
                                            <p class="text-xs text-red-600">PINDAH KE {{ $record->officerBranch->branch_name }}</p>
                                            @else
                                            <p class="text-xs text-gray-600">{{ $record->officer_position }}</p>
                                            @endif
                                            @endif
                                        </th>

                                        {{-- kriteria 1 --}}
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->rm_patut_kutip ? number_format($record->rm_patut_kutip, 2) : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->rm_dapat_kutip ? number_format($record->rm_dapat_kutip, 2) : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->rm_dapat_kutip_pts ? number_format($record->rm_dapat_kutip_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->rm_dapat_kutip_capai_flag ? $record->rm_dapat_kutip_capai_flag : '-') : '-' }}
                                        </th>
                                        {{-- kriteria 2 --}}
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_patut_kutip ? $record->bil_patut_kutip : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_dapat_kutip ? $record->bil_dapat_kutip : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_dapat_kutip_pts ? number_format($record->bil_dapat_kutip_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_dapat_kutip_capai_flag ? $record->bil_dapat_kutip_capai_flag : '-') : '-' }}
                                        </th>
                                        {{-- kriteria 3 --}}
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_selia ? $record->bil_selia : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_lawat ? $record->bil_lawat : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_lawat_pts ? number_format($record->bil_lawat_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_lawat_capai_flag ? $record->bil_lawat_capai_flag : '-') : '-' }}
                                        </th>
                                        {{-- kriteria 4 --}}
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_kawal_npf_sblm ? $record->bil_kawal_npf_sblm : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_kawal_npf_tukar ? $record->bil_kawal_npf_tukar : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_kawal_npf_kekal ? $record->bil_kawal_npf_kekal : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_kawal_npf_pts ? number_format($record->bil_kawal_npf_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_kawal_npf_capai_flag ? $record->bil_kawal_npf_capai_flag : '-') : '-' }}
                                        </th>
                                        {{-- kriteria 5 --}}
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_pulih_npf_sblm ? $record->bil_pulih_npf_sblm : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_pulih_npf_tukar ? $record->bil_pulih_npf_tukar : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_pulih_npf_pts ? number_format($record->bil_pulih_npf_pts, 2) . '%' : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center @if($record && ($record->incl_pmgi_flag == 'W' || $record->incl_pmgi_flag == 'S')) text-white @else text-gray-800 @endif border-black border-dashed border-x whitespace-nowrap">
                                            {{ $record ? ($record->bil_pulih_npf_capai_flag ? $record->bil_pulih_npf_capai_flag : '-') : '-' }}
                                        </th>
                                        <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">
                                            @if($record)
                                                @if($record->pmgi_capai_flag)
                                                    @if($record->pmgi_capai_flag == 'Y')
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                                    @else
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-red-100 ">TAK CAPAI</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </th>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
