<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Rekod PMGi</h3>
                    <span class="text-base font-normal text-gray-500 ">Rekod PMGi Individu</span>
                    @if($isAdmin)
                        <div class="p-6 mt-4 border rounded-lg shadow bg-primary-100 border-primary-200 dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex justify-between">
                                <div class="grid w-[70%] grid-cols-3 gap-x-4 gap-y-2">
                                    <div class="col-span-2">
                                        <x-input label="Nama Pegawai @ No Pekerja" placeholder="Sila Taip Nama @ No Pekerja pegawai" wire:model="searchTerm" wire:keydown.enter="search" />
                                    </div>
                                </div>
                                <button wire:click="search" class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 ">
                                    Cari
                                    <x-icon name="search" class="w-4 h-4 ms-2" />
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($allSession->count() > 0)
            <div x-data="{ tab: 'PMGi1' }" class="mt-6">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
                    <li class="me-2">
                        <div @click="tab = 'PMGi1'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi1', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi1' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer" aria-current="page">
                            PMGi 1
                        </div>
                    </li>
                    <li class="me-2">
                        <div @click="tab = 'PMGi2'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi2', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi2' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                            PMGi 2
                        </div>
                    </li>
                    <li class="me-2">
                        <div @click="tab = 'PMGi3'" :class="{ 'bg-primary-600 text-white': tab === 'PMGi3', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'PMGi3' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                            PMGi 3
                        </div>
                    </li>
                    <li class="me-2">
                        <div @click="tab = 'JTT1'" :class="{ 'bg-primary-600 text-white': tab === 'JTT1', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'JTT1' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                            Timbang Tara 1
                        </div>
                    </li>
                    <li class="me-2">
                        <div @click="tab = 'JTT2'" :class="{ 'bg-primary-600 text-white': tab === 'JTT2', 'hover:text-gray-900 hover:bg-gray-100': tab !== 'JTT2' }" class="inline-block px-4 py-3 rounded-lg cursor-pointer">
                            Timbang Tara 2
                        </div>
                    </li>
                </ul>

                <div x-show="tab === 'PMGi1'" x-transition>
                    <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 ">
                                        <thead class=" bg-gray-50">
                                            <tr class="bg-gray-100">
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    BIL
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    TARIKH
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    KEPUTUSAN
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white ">
                                            @forelse ($allSession->where('pmgi_level', 'PM1') as $data)
                                            <tr>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ strtoupper($data->created_at->translatedFormat('F Y')) }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    @if($data->mntrSession->pmgi_result == 'CP1')
                                                    <div class="inline-block bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">SELESAI SESI</div>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    <x-badge rounded primary label="Lihat" class="cursor-pointer" wire:click="toggleDetail('{{ $data->session_id }}')" />
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">TIADA DATA</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'PMGi2'" x-transition>
                    <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 ">
                                        <thead class=" bg-gray-50">
                                            <tr class="bg-gray-100">
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    BIL
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    TARIKH
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    KEPUTUSAN
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white ">
                                            @forelse ($allSession->where('pmgi_level', 'PM2') as $data)
                                            <tr>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ strtoupper($data->created_at->translatedFormat('F Y')) }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    @if($data->mntrSession->pmgi_result == 'CP2')
                                                    <div class="inline-block bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">SELESAI SESI</div>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    <x-badge rounded primary label="Lihat" class="cursor-pointer" wire:click="toggleDetail" />
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">TIADA DATA</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'PMGi3'" x-transition>
                    <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 ">
                                        <thead class=" bg-gray-50">
                                            <tr class="bg-gray-100">
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    BIL
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    TARIKH
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    KEPUTUSAN
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white ">
                                            @forelse ($allSession->where('pmgi_level', 'PM3') as $data)
                                            <tr>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ strtoupper($data->created_at->translatedFormat('F Y')) }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    @if($data->mntrSession->pmgi_result == 'PEX')
                                                    <div class="inline-block bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">KELUAR SENARAI<br>TANPA SYARAT</div>
                                                    @elseif($data->mntrSession->pmgi_result == 'EXC')
                                                    <div class="inline-block bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-yellow-100 ">KELUAR SENARAI<br>DENGAN SYARAT</div>
                                                    @elseif($data->mntrSession->pmgi_result == 'EXP')
                                                    <div class="inline-block bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-yellow-100 ">PENANGGUHAN</div>
                                                    @else
                                                    <div class="inline-block bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-red-100 ">DIBAWA KE JTT1</div>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    <x-badge rounded primary label="Lihat" class="cursor-pointer" wire:click="toggleDetail" />
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">TIADA DATA</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'JTT1'" x-transition>
                    <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 ">
                                        <thead class=" bg-gray-50">
                                            <tr class="bg-gray-100">
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    BIL
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    TARIKH
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    KEPUTUSAN
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white ">
                                            @forelse ($allSession->where('pmgi_level', 'JT1') as $data)
                                            <tr>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ strtoupper($data->created_at->translatedFormat('F Y')) }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    @if($data->mntrSession->pmgi_result == 'PDQ')
                                                    <div class="inline-block bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-yellow-100 ">DIBERI TEMPOH</div>
                                                    @else
                                                    <div class="inline-block bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-red-100 ">TINDAKAN TATA TERTIB</div>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    <x-badge rounded primary label="Lihat" class="cursor-pointer" wire:click="toggleDetail" />
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">TIADA DATA</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'JTT2'" x-transition>
                    <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 ">
                                        <thead class=" bg-gray-50">
                                            <tr class="bg-gray-100">
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    BIL
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    TARIKH
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                                    KEPUTUSAN
                                                </th>
                                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white ">
                                            @forelse ($allSession->where('pmgi_level', 'JT2') as $data)
                                            <tr>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    {{ strtoupper($data->created_at->translatedFormat('F Y')) }}
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    @if($data->mntrSession->pmgi_result == 'EXL')
                                                    <div class="inline-block bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-green-100 ">KELUAR SENARAI</div>
                                                    @else
                                                    <div class="inline-block bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border-red-100 ">TINDAKAN TATA TERTIB</div>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                                    <x-badge rounded primary label="Lihat" class="cursor-pointer" wire:click="toggleDetail" />
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="p-2 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">TIADA DATA</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- details modal --}}
                <x-modal.card blur align="center" max-width="7xl" hide-close=false wire:model="detailsModal">
                    @if($sessionId)
                        <iframe src="{{ route('stream.rekodPmgi', ['sessionId' => $sessionId]) }}" frameborder="0" width="100%" height="700px"></iframe>
                    @endif
                </x-modal.card>
            </div>
            @endif
        </div>
    </div>
</main>
