<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Prestasi Kumulatif</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            color: #4A5568;
            /* Tailwind's text-gray-900 */
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        .inline-block {
            display: inline-block;
        }

        .min-w-full {
            min-width: 100%;
        }

        .align-middle {
            vertical-align: middle;
        }

        .pl-1 {
            padding-left: 0.25rem;
            /* Tailwind's pl-1 */
        }

        .bg-gray-50 {
            background-color: #F9FAFB;
            /* Tailwind's bg-gray-50 */
        }

        .bg-gray-100 {
            background-color: #F3F4F6;
            /* Tailwind's bg-gray-100 */
        }

        .bg-gray-200 {
            background-color: #E5E7EB;
            /* Tailwind's bg-gray-200 */
        }

        .bg-white {
            background-color: #FFFFFF;
            /* Tailwind's bg-white */
        }

        .text-xs {
            font-size: 12px;
            /* Tailwind's text-xs */
        }

        .text-sm {
            font-size: 14px;
            /* Tailwind's text-sm */
        }

        .text-gray-500 {
            color: #6B7280;
            /* Tailwind's text-gray-500 */
        }

        .text-gray-900 {
            color: #1F2937;
            /* Tailwind's text-gray-900 */
        }

        .text-center {
            text-align: center;
        }

        .tracking-tight {
            letter-spacing: -0.015em;
            /* Tailwind's tracking-tight */
        }

        .uppercase {
            text-transform: uppercase;
        }

        .border {
            border-width: 1px;
            border-color: #000000;
        }

        .border-black {
            border-color: #000000;
        }

        .border-dashed {
            border-style: dashed;
        }

        .border {
            border-left-width: 1px;
            border-right-width: 1px;
        }

        .border-y {
            border-top-width: 1px;
            border-bottom-width: 1px;
        }

        .border-l {
            border-left-width: 1px;
        }

        .rounded-md {
            border-radius: 0.375rem;
            /* Tailwind's rounded-md */
        }

        .bg-green-100 {
            background-color: #D1FAE5;
            /* Tailwind's bg-green-100 */
        }

        .text-green-800 {
            color: #065F46;
            /* Tailwind's text-green-800 */
        }

        .bg-pink-100 {
            background-color: #FCE7F3;
            /* Tailwind's bg-pink-100 */
        }

        .text-pink-800 {
            color: #9D174D;
            /* Tailwind's text-pink-800 */
        }

        .bg-blue-50 {
            background-color: #EFF6FF;
            /* Tailwind's bg-blue-50 */
        }

        .text-blue-800 {
            color: #1E40AF;
            /* Tailwind's text-blue-800 */
        }

        .dark\:bg-gray-800 {
            background-color: #1F2937;
            /* Tailwind's dark:bg-gray-800 */
        }

        .dark\:text-blue-400 {
            color: #60A5FA;
            /* Tailwind's dark:text-blue-400 */
        }

        .mr-2 {
            margin-right: 0.5rem;
            /* Tailwind's mr-2 */
        }

        .px-2.5 {
            padding-left: 0.625rem;
            /* Tailwind's px-2.5 */
            padding-right: 0.625rem;
        }

        .py-0.5 {
            padding-top: 0.125rem;
            /* Tailwind's py-0.5 */
            padding-bottom: 0.125rem;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .p-4 {
            padding: 1rem;
        }

        .text-blue-400 {
            color: #60A5FA;
        }

    </style>

</head>

<body>
    <main>
        <div class="bg-white">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-gray-200 ">
                    <thead class="bg-gray-50 ">
                        <tr class="bg-gray-200">
                            <th class="bg-white"></th>
                            @foreach($datas as $data)
                            <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed ">
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
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PERKARA
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PRESTASI
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                %
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                STATUS
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 border border-black border-dashed border-y whitespace-nowrap">
                                KUTIPAN TANPA KONTRAK I (Minimum 80%)
                            </td>
                            @foreach($datas as $data)
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                PK (RM) P + C
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->rm_patut_kutip, 2) }}
                            </td>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ $data->rm_dapat_kutip_pts }}%
                            </td>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap">
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
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                DK (RM)
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap border-y">
                                {{ number_format($data->rm_dapat_kutip, 2) }}
                            </td>
                            @endforeach
                        </tr>
                        {{-- kriteria 2 --}}
                        <tr class="bg-gray-100">
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed border-y">
                                KRITERIA 2
                            </th>
                            @foreach($datas as $data)
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PERKARA
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PRESTASI
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                %
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                STATUS
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 border border-black border-dashed border-y whitespace-nowrap">
                                BILANGAN MEMBAYAR (Minimum 80%)
                            </td>
                            @foreach($datas as $data)
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                BILANGAN SELIAAN
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->bil_patut_kutip) }}
                            </td>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->bil_dapat_kutip_pts, 2) }}%
                            </td>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap">
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
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                BILANGAN MEMBAYAR
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap border-y">
                                {{ number_format($data->bil_dapat_kutip) }}
                            </td>
                            @endforeach
                        </tr>
                        {{-- kriteria 3 --}}
                        <tr class="bg-gray-100">
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed border-y">
                                KRITERIA 3
                            </th>
                            @foreach($datas as $data)
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PERKARA
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PRESTASI
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                %
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                STATUS
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 border border-black border-dashed border-y whitespace-nowrap">
                                LAWATAN SELIAAN (Minimum 80%)
                            </td>
                            @foreach($datas as $data)
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                BILANGAN SELIAAN
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->bil_selia) }}
                            </td>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->bil_lawat_pts, 2) }}%
                            </td>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap">
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
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                JUMLAH LAWATAN
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap border-y">
                                {{ number_format($data->bil_lawat) }}
                            </td>
                            @endforeach
                        </tr>
                        {{-- kriteria 4 --}}
                        <tr class="bg-gray-100">
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed border-y">
                                KRITERIA 4
                            </th>
                            @foreach($datas as $data)
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PERKARA
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PRESTASI
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                %
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                STATUS
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-900 border border-black border-dashed border-y whitespace-nowrap">
                                PRESTASI NPF (KAWALAN) (Minimum 50%)
                            </td>
                            @foreach($datas as $data)
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                BILANGAN AKAUN A3 (5.01-6)
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->bil_kawal_npf_sblm) }}
                            </td>
                            <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->bil_kawal_npf_pts, 2) }}%
                            </td>
                            <td rowspan="3" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap">
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
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                BERTUKAR B1
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap border-y">
                                {{ number_format($data->bil_kawal_npf_tukar) }}
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($datas as $data)
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                BILANGAN KEKAL
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap border-y">
                                {{ number_format($data->bil_kawal_npf_kekal) }}
                            </td>
                            @endforeach
                        </tr>
                        {{-- kriteria 5 --}}
                        <tr class="bg-gray-100">
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed border-y">
                                KRITERIA 5
                            </th>
                            @foreach($datas as $data)
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PERKARA
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                PRESTASI
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                %
                            </th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">
                                STATUS
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-900 border border-black border-dashed border-y whitespace-nowrap">
                                PRESTASI NPF PEMULIHAN (Minimum 5%)
                            </td>
                            @foreach($datas as $data)
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                BILANGAN AKAUN NPF
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->bil_pulih_npf_sblm) }}
                            </td>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                {{ number_format($data->bil_pulih_npf_pts) }}%
                            </td>
                            <td rowspan="2" class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap">
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
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed border-y whitespace-nowrap">
                                BERTUKAR SEMASA
                            </td>
                            <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black border-dashed whitespace-nowrap border-y">
                                {{ number_format($data->bil_pulih_npf_tukar) }}
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                <div class="bg-white">
                    <div class="flex p-4 mt-4 text-xs text-blue-800 rounded-lg bg-blue-50" role="alert">
                        <x-icon name="information-circle" class="w-5 h-5 mr-4 text-blue-800" />
                        NOTA : Bagi Kriteria 4, % purata adalah Total Bilangan Akaun Kekal A3 untuk 2 Bulan Penilaian / Total Bilangan Akaun A3 untuk 2 Bulan Penilaian
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
