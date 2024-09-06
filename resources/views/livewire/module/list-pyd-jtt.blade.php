<div>
    <div class="px-4 pt-6 2xl:px-0">
        {{-- ringkasan --}}
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center lg:flex">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-xl font-bold text-gray-900 ">Senarai PYD</h3>
                    <span class="text-base font-normal text-gray-500 ">Senarai PYD untuk dinilai JTT</span>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 ">
                                <thead class="bg-gray-50 ">
                                    <tr class="bg-gray-200">
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase border-black border-dashed border-x ">
                                            NAMA
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x ">
                                            CAWANGAN
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x ">
                                            JTT
                                        </th>
                                        <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-black border-dashed border-x ">
                                            TINDAKAN
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white ">
                                    @forelse ($datas as $index => $data)
                                    <tr class="{{ $index % 2 != 0 ? 'bg-gray-50' : '' }}">
                                        <td class="p-2 text-xs font-normal text-left text-gray-900 uppercase border-black border-dashed border-x whitespace-nowrap">
                                            {{ $data->user->username }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-900 uppercase border-black border-dashed border-x whitespace-nowrap">
                                            {{ $data->user->branchName() }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-900 uppercase border-black border-dashed border-x whitespace-nowrap">
                                            {{ substr($data->pmgi_level, -1) }}
                                        </td>
                                        <td class="p-2 text-xs font-normal text-center text-gray-900 uppercase border-black border-dashed border-x whitespace-nowrap">
                                            <x-badge rounded primary label="Mulakan sesi" class="cursor-pointer" wire:click="startSession('{{ $data->user->userid }}', '{{ $data->report_date }}')" />
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="p-2 text-xs font-normal text-center text-gray-900 uppercase border border-black border-dashed whitespace-nowrap">
                                            NO DATA
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
