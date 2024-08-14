<!DOCTYPE html>
<html>
<head>
    <title>Pyd Info Export</title>
</head>
<body>
    <table>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="7" style="font-weight: bold; font-size: 14pt;">Senarai PYD bagi sesi PMGi {{ $pmgi }} bulan {{ $date }}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>PYM :</td>
            <td colspan="6">{{ $pym }}</td>
        </tr>

        @if($pmc)
        <tr>
            <td></td>
            <td>PMC :</td>
            <td colspan="6">{{ $pmc }}</td>
        </tr>
        @endif
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
                            <th>Bil</th>
                            <th>Name</th>
                            <th>Branch</th>
                            <th>Staff No</th>
                            <th>Ic No</th>
                            <th>PMGi Level</th>
                            <th>Report Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->officer_name }}</td>
                            <td>{{ $data->branch_name }}</td>
                            <td>{{ $data->staffno }}</td>
                            <td>{{ $data->nokp }}</td>
                            <td>{{ substr($data->pmgi_level, -1) }}</td>
                            <td>{{ $data->report_date }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
