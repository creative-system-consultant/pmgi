<main>
    <div class="px-4 pt-6 2xl:px-0" x-data="{ tab: 'user-access' }" x-cloak>
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    {{-- current jtt --}}
                    <div class="mt-6">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 ">Master List Warga Kerja</h3>
                        <div>
                            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
                                <!-- Result -->
                                <div class="flex flex-col">
                                    <div class="overflow-x-auto">
                                        <div class="inline-block min-w-full align-middle">
                                            <div class="table-scroll-container">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50 ">
                                                        <tr class="bg-gray-200">
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Negeri</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Cawangan</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">No. Pekerja</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Nama</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">No. K/P</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Jawatan</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Gelaran</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Jawatan Bulan Sebelum</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tempoh Penempatan Semasa</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tempoh Khidmat</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Cawangan Sebelum</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tarikh Kuatkuasa</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Alamat</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Status</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tarikh Berhenti Kerja</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">No. Tel</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Jantina</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tarikh Cuti Dari</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tarikh Cuti HIngga</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Kod Cuti</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Gred</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tarikh Lantikan</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Taraf Jawatan</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tarikh Disiplin</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Deskripsi Disiplin</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Punca Disiplin</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white ">
                                                        @forelse ($wargaKerja as $wk)
                                                        <tr class="even:bg-gray-50">
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->negeri }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->cawangan }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->no_pekerja }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->nama }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->no_kp }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->jawatan }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->gelaran }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->jawatan_bulan_sebelum }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->tempoh_penempatan_semasa }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->tempoh_khidmat }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->cawangan_sebelum }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->tarikh_kuatkuasa }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->alamat }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->status }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->resign_date?->format('d/m/Y') }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->notel }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->jantina }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->tarikh_cuti_dari }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->tarikh_cuti_hingga }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->kod_cuti }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->gred }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->tarikh_lantikan }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->taraf_jawatan }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->date_disiplin }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->description_disiplin }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->disiplin_reason }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="8" class="p-2 text-sm font-semibold tracking-tight text-center text-gray-800 whitespace-nowrap">No Data</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pagination -->
                                <div class="mt-4">
                                    {{ $wargaKerja->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</main>
