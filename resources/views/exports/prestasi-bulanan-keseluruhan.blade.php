<!DOCTYPE html>
<html>
<head>
    <title>Muat Turun Ringkasan Bulanan</title>
</head>
<body>
    <table>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="7" style="font-weight: bold; font-size: 14pt;">RINGKASAN PRESTASI BULANAN PEGAWAI</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>NEGERI: </td>
            <td>{{ $selectedState }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>CAWANGAN: </td>
            <td> {{ $selectedBranch }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>TARIKH: </td>
            <td> {{ $reportDate }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="4">KRITERIA 1</th>
                            <th colspan="4">KRITERIA 2</th>
                            <th colspan="4">KRITERIA 3</th>
                            <th colspan="5">KRITERIA 4</th>
                            <th colspan="4">KRITERIA 5</th>
                            <th rowspan="3">STATUS</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th colspan="4">PATUT KUTIP (RM)<br>VS<br>DAPAT KUTIP (RM)</th>
                            {{-- <th>{{ $titleData->rm_dapat_kutip_nilai_pts }}%</th> --}}
                            <th colspan="4">PATUT KUTIP (BIL)<br>VS<br>DAPAT KUTIP (BIL)</th>
                            {{-- <th>{{ $titleData->bil_dapat_kutip_nilai_pts }}%</th> --}}
                            <th colspan="4">LAWATAN SELIAAN</th>
                            {{-- <th>{{ $titleData->bil_lawat_nilai_pts }}%</th> --}}
                            <th colspan="5">PRESTASI NPF (KAWALAN)</th>
                            {{-- <th>{{ $titleData->bil_kawal_npf_nilai_pts }}%</th> --}}
                            <th colspan="4">PRESTASI NPF (PEMULIHAN)</th>
                            {{-- <th>{{ $titleData->bil_pulih_npf_nilai_pts }}%</th> --}}
                        </tr>
                        <tr>
                            <th>NAMA PEGAWAI<br>GELARAN</th>
                            <th>PATUT KUTIP<br>(RM)</th>
                            <th>DAPAT KUTIP<br>(RM)</th>
                            <th>%</th>
                            <th>PENILAIAN</th>
                            <th>PATUT KUTIP<br>(BIL)</th>
                            <th>DAPAT KUTIP<br>(BIL)</th>
                            <th>%</th>
                            <th>PENILAIAN</th>
                            <th>SELIAAN</th>
                            <th>LAWATAN</th>
                            <th>%</th>
                            <th>PENILAIAN</th>
                            <th>A3 (5.01 - 6.0)<br>(BLN SBLM)</th>
                            <th>BERTUKAR B1<br>(BLN SEMASA)</th>
                            <th>BILANGAN KEKAL</th>
                            <th>KAWALAN (%)</th>
                            <th>PENILAIAN</th>
                            <th>NPF<br>(BLN SEBELUM)</th>
                            <th>BERTUKAR SEMASA<br>(BLN SEMASA)</th>
                            <th>% PEMULIHAN</th>
                            <th>PENILAIAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedData as $stateData)
                            @foreach ($stateData as $branchData)
                                @foreach ($branchData as $officerId => $records)
                                    @foreach ($records as $record)
                                        <tr>
                                            <th valign="middle"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    <span>KESELURUHAN TOTAL</span>
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    <span>{{ $record->negeri }} TOTAL</span>
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    <span>{{ $record->branch->branch_name }} TOTAL</span>
                                                @else
                                                    <span>{{ $record->officer_name }}</span><br>
                                                    @if ($record->incl_pmgi_flag == 'J')
                                                        <span>
                                                            BERHENTI PADA {{ \Carbon\Carbon::parse($record->officer_resign_date)->format('d/m/Y') }}
                                                        </span>
                                                    @elseif ($record->incl_pmgi_flag == 'G')
                                                        <span>
                                                            PINDAH KE {{ $record->officerBranch->branch_name }}
                                                        </span>
                                                    @else
                                                        <span>
                                                            {{ $record->officer_position }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </th>

                                            {{-- kriteria 1 --}}
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->rm_patut_kutip ? number_format($record->rm_patut_kutip, 2) : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->rm_dapat_kutip ? number_format($record->rm_dapat_kutip, 2) : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->rm_dapat_kutip_pts ? number_format($record->rm_dapat_kutip_pts, 2) . '%' : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->rm_dapat_kutip_capai_flag ? $record->rm_dapat_kutip_capai_flag : '-') : '-' }}
                                            </th>

                                            {{-- kriteria 2 --}}
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_patut_kutip ? $record->bil_patut_kutip : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_dapat_kutip ? $record->bil_dapat_kutip : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_dapat_kutip_pts ? number_format($record->bil_dapat_kutip_pts, 2) . '%' : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_dapat_kutip_capai_flag ? $record->bil_dapat_kutip_capai_flag : '-') : '-' }}
                                            </th>

                                            {{-- kriteria 3 --}}
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_selia ? $record->bil_selia : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_lawat ? $record->bil_lawat : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_lawat_pts ? number_format($record->bil_lawat_pts, 2) . '%' : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_lawat_capai_flag ? $record->bil_lawat_capai_flag : '-') : '-' }}
                                            </th>

                                            {{-- kriteria 4 --}}
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_kawal_npf_sblm ? $record->bil_kawal_npf_sblm : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_kawal_npf_tukar ? $record->bil_kawal_npf_tukar : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_kawal_npf_kekal ? $record->bil_kawal_npf_kekal : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_kawal_npf_pts ? number_format($record->bil_kawal_npf_pts, 2) . '%' : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_kawal_npf_capai_flag ? $record->bil_kawal_npf_capai_flag : '-') : '-' }}
                                            </th>

                                            {{-- kriteria 5 --}}
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_pulih_npf_sblm ? $record->bil_pulih_npf_sblm : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_pulih_npf_tukar ? $record->bil_pulih_npf_tukar : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_pulih_npf_pts ? number_format($record->bil_pulih_npf_pts, 2) . '%' : '-') : '-' }}
                                            </th>
                                            <th valign="middle" align="center"
                                                @if ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                {{ $record ? ($record->bil_pulih_npf_capai_flag ? $record->bil_pulih_npf_capai_flag : '-') : '-' }}
                                            </th>

                                            <th valign="middle" align="center"
                                                @if ($record && $record->pmgi_capai_flag == 'Y')
                                                    style="background-color: #047857; color: white; font-weight: bold;"
                                                @elseif ($record && $record->pmgi_capai_flag == 'N')
                                                    style="background-color: #B91C1C; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'W')
                                                    style="background-color: #1F2937; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'S')
                                                    style="background-color: #6B7280; color: white; font-weight: bold;"
                                                @elseif ($record->incl_pmgi_flag == 'N')
                                                    style="background-color: #E5E7EB; font-weight: bold;"
                                                @endif
                                            >
                                                @if($record)
                                                    @if($record->pmgi_capai_flag)
                                                        @if($record->pmgi_capai_flag == 'Y')
                                                            <span>CAPAI</span>
                                                        @else
                                                            <span>TAK CAPAI</span>
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
            </td>
        </tr>
    </table>
</body>
</html>
