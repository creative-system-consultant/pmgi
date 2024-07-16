<div class="flex flex-col mt-6">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="table-scroll-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 ">
                        <tr class="bg-gray-400 ">
                            <th colspan="2" class="bg-white headcol-clear"></th>
                            @foreach ($groupedData as $officerId => $records)
                                @foreach ($records as $record)
                                    <th scope="col" colspan="6" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">{{ $record->report_date }}</th>
                                @endforeach
                            @endforeach
                            <th scope="col" rowspan="2" class="p-2 text-xs font-medium tracking-tight text-center text-white uppercase border-black border-dashed border-x">PMGi</th>
                        </tr>
                        <tr class="bg-gray-200">
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">NAMA PEGAWAI</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">GELARAN</th>
                            {{-- <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 whitespace-nowrap headcol">TARAF JAWATAN</th> --}}
                            @foreach ($groupedData as $officerId => $records)
                                @foreach ($records as $record)
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">1<br>(PK / DK)</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">2<br>(BILANGAN)</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">3<br>(LAWATAN)</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">4<br>(NPF KAWALAN)</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">5<br>(NPF PEMULIHAN)</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-black border-dashed whitespace-nowrap">STATUS</th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white ">
                        @foreach ($groupedData as $index => $datas)
                            <tr>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 bg-white headcol-clear whitespace-nowrap">{{ $datas[0]->officer_name }}</th>
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 bg-white headcol-clear whitespace-nowrap">{{ $datas[0]->officer_position }}</th>
                                {{-- <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 bg-white headcol-clear whitespace-nowrap">TETAP</th> --}}
                                @foreach ($datas as $data)
                                    <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->rm_dapat_kutip_pts }}%</th>
                                    <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_dapat_kutip_pts }}%</th>
                                    <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_lawat_pts }}%</th>
                                    <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_kawal_npf_pts }}%</th>
                                    <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">{{ $data->bil_pulih_npf_pts }}%</th>
                                    <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">
                                        @if($data->pmgi_capai_flag == 'Y')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">CAPAI</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-red-100 ">TIDAK CAPAI</span>
                                        @endif
                                    </th>
                                @endforeach
                                <th scope="col" class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 border-black border-dashed border-x whitespace-nowrap">N</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
