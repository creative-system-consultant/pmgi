<div class="flex flex-col mt-6">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="table-scroll-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 ">
                        <tr class="bg-gray-400 ">
                            <th class="bg-white headcol-clear"></th>
                            <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 1</th>
                            <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 2</th>
                            <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 3</th>
                            <th scope="col" colspan="5" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 4</th>
                            <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">KRITERIA 5</th>
                            <th scope="col" rowspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">STATUS</th>
                        </tr>
                        <tr class="bg-gray-300">
                            <th class="bg-white headcol-clear"></th>
                            <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PATUT KUTIP (RM)<br>VS<br>DAPAT KUTIP (RM)</th>
                            <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->rm_dapat_kutip_nilai_pts }}%</th>
                            <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PATUT KUTIP (BIL)<br>VS<br>DAPAT KUTIP (BIL)</th>
                            <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->bil_dapat_kutip_nilai_pts }}%</th>
                            <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">LAWATAN SELIAAN</th>
                            <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->bil_lawat_nilai_pts }}%</th>
                            <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PRESTASI NPF (KAWALAN)</th>
                            <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->bil_kawal_npf_nilai_pts }}%</th>
                            <th scope="col" colspan="3" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">PRESTASI NPF (PEMULIHAN)</th>
                            <th scope="col" class="p-2 text-lg font-semibold tracking-tight text-center text-gray-500 uppercase border border-black border-dashed">{{ $titleData->bil_pulih_npf_nilai_pts }}%</th>
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
                        @foreach ($officerData as $index => $data)
                            <tr>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 bg-white headcol-clear whitespace-nowrap">{{ $data->officer_name }}<br>
                                    <p class="text-xs text-gray-600 ">{{ $data->officer_position }}</p></th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->rm_patut_kutip, 2) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->rm_dapat_kutip, 2) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->rm_dapat_kutip_pts }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->rm_dapat_kutip_capai_flag }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_patut_kutip) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_dapat_kutip) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_dapat_kutip_pts }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_dapat_kutip_capai_flag }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_selia) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_lawat) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_lawat_pts }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_lawat_capai_flag }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_kawal_npf_sblm) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_kawal_npf_tukar) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_kawal_npf_kekal) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_kawal_npf_pts }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_kawal_npf_capai_flag }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_pulih_npf_sblm) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ number_format($data->bil_pulih_npf_tukar) }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_pulih_npf_pts }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_pulih_npf_capai_flag }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">
                                    @if($data->pmgi_capai_flag == 'Y')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-red-100 ">TAK CAPAI</span>
                                    @endif
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
