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
                            @foreach ($months as $month)
                            <th valign="middle" align="center" colspan="6">{{ $month }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th valign="middle" align="center">NAMA PEGAWAI<br>GELARAN</th>
                            @foreach ($months as $month)
                            <th valign="middle" align="center">1<br>(PK / DK)</th>
                            <th valign="middle" align="center">2<br>(BILANGAN)</th>
                            <th valign="middle" align="center">3<br>(LAWATAN)</th>
                            <th valign="middle" align="center">4<br>(NPF KAWALAN)</th>
                            <th valign="middle" align="center">5<br>(NPF PEMULIHAN)</th>
                            <th valign="middle" align="center">STATUS</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedData as $stateData)
                            @foreach ($stateData as $branchData)
                                @foreach ($branchData as $officerId => $records)
                                <tr>
                                    <th valign="middle"
                                        @if ($records->first()->incl_pmgi_flag == 'W')
                                            style="background-color: #1F2937; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            style="background-color: #6B7280; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            style="background-color: #E5E7EB; font-weight: bold;"
                                        @endif
                                    >
                                        @if ($records->first()->incl_pmgi_flag == 'W')
                                            <span>KESELURUHAN TOTAL</span>
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            <span>{{ $records->first()->negeri }} TOTAL</span>
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            <span>{{ $records->first()->branch->branch_name }} TOTAL</span>
                                        @else
                                            <span>{{ $records->first()->officer_name }}</span><br>
                                            @if ($records->first()->incl_pmgi_flag == 'J')
                                                <span>
                                                    BERHENTI PADA {{ \Carbon\Carbon::parse($records->first()->officer_resign_date)->format('d/m/Y') }}
                                                </span>
                                            @elseif ($records->first()->incl_pmgi_flag == 'G')
                                                <span>
                                                    PINDAH KE {{ $records->first()->officerBranch->branch_name }}
                                                </span>
                                            @else
                                                <span>
                                                    {{ $records->first()->officer_position }}
                                                </span>
                                            @endif
                                        @endif
                                    </th>
                                    @foreach ($months as $month)
                                    @php
                                    $record = $records->firstWhere('report_date', $month);
                                    @endphp
                                    <th valign="middle" align="center"
                                        @if ($records->first()->incl_pmgi_flag == 'W')
                                            style="background-color: #1F2937; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            style="background-color: #6B7280; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            style="background-color: #E5E7EB; font-weight: bold;"
                                        @endif
                                    >
                                        {{ $record ? ($record->rm_dapat_kutip_pts ? number_format($record->rm_dapat_kutip_pts, 2) . '%' : '-') : '-' }}
                                    </th>
                                    <th valign="middle" align="center"
                                        @if ($records->first()->incl_pmgi_flag == 'W')
                                            style="background-color: #1F2937; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            style="background-color: #6B7280; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            style="background-color: #E5E7EB; font-weight: bold;"
                                        @endif
                                    >
                                        {{ $record ? ($record->bil_dapat_kutip_pts ? number_format($record->bil_dapat_kutip_pts, 2) . '%' : '-') : '-' }}
                                    </th>
                                    <th valign="middle" align="center"
                                        @if ($records->first()->incl_pmgi_flag == 'W')
                                            style="background-color: #1F2937; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            style="background-color: #6B7280; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            style="background-color: #E5E7EB; font-weight: bold;"
                                        @endif
                                    >
                                        {{ $record ? ($record->bil_lawat_pts ? number_format($record->bil_lawat_pts, 2) . '%' : '-') : '-' }}
                                    </th>
                                    <th valign="middle" align="center"
                                        @if ($records->first()->incl_pmgi_flag == 'W')
                                            style="background-color: #1F2937; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            style="background-color: #6B7280; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            style="background-color: #E5E7EB; font-weight: bold;"
                                        @endif
                                    >
                                        {{ $record ? ($record->bil_kawal_npf_pts ? number_format($record->bil_kawal_npf_pts, 2) . '%' : '-') : '-' }}
                                    </th>
                                    <th valign="middle" align="center"
                                        @if ($records->first()->incl_pmgi_flag == 'W')
                                            style="background-color: #1F2937; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            style="background-color: #6B7280; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            style="background-color: #E5E7EB; font-weight: bold;"
                                        @endif
                                    >
                                        {{ $record ? ($record->bil_pulih_npf_pts ? number_format($record->bil_pulih_npf_pts, 2) . '%' : '-') : '-' }}
                                    </th>
                                    <th valign="middle" align="center"
                                        @if ($record && $record->pmgi_capai_flag == 'Y')
                                            style="background-color: #047857; color: white; font-weight: bold;"
                                        @elseif ($record && $record->pmgi_capai_flag == 'N')
                                            style="background-color: #B91C1C; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'W')
                                            style="background-color: #1F2937; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'S')
                                            style="background-color: #6B7280; color: white; font-weight: bold;"
                                        @elseif ($records->first()->incl_pmgi_flag == 'N')
                                            style="background-color: #E5E7EB; font-weight: bold;"
                                        @endif
                                    >
                                        @if($record && $record->pmgi_capai_flag == 'Y')
                                            <span>CAPAI</span>
                                        @elseif($record && $record->pmgi_capai_flag == 'N')
                                            <span>TIDAK CAPAI</span>
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
            </td>
        </tr>
    </table>
</body>
</html>
