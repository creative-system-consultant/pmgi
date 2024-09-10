<main>
    <div class="px-4 pt-6 2xl:px-0" x-data="{ tab: 'user-access' }" x-cloak>
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    {{-- current jtt --}}
                    <div class="mt-6">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 ">Senarai Pegawai Bank</h3>
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
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Officer ID</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Nama</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Jawatan</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Email</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">No. K/P</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Staff No.</th>
                                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">Tarikh Berhenti Kerja</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white ">
                                                        @forelse ($wargaKerja as $wk)
                                                        <tr class="even:bg-gray-50">
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->officer_id }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->officer_name }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->officer_position }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->email }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->nokp }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $wk->staffno }}</td>
                                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">
                                                                {{ $wk->date_resign ? date('d/m/Y', strtotime($wk->date_resign)) : 'Masih Bekerja' }}
                                                            </td>   
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
