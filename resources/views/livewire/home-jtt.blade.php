<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center">
                <div class="mb-4 lg:mb-0">
                    <div class="flex justify-between">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 ">Dashboard Penimbang Tara</h3>
                        <h3 class="mb-2 text-xl font-bold text-gray-900 ">{{ now()->format('d/m/Y') }}</h3>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <x-select label="Tempat" placeholder="Sila Pilih" :options="$rooms" option-label="room_name" option-value="id" wire:model="room" />
            </div>
            @if ($dataCount == 0)
                <div class="flex justify-center">
                    <img class="h-96" src="{{ asset('image/animation/no_data.gif') }}" alt="No Data">
                </div>
            @else
                <!-- Table -->
                <div class="flex flex-col mt-6">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="pb-1 pl-1 overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 ">
                                    <thead class="bg-gray-50 ">
                                        <tr class="bg-gray-200">
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase ">
                                                NAMA
                                            </th>
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase ">
                                                JAWATAN
                                            </th>
                                            <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase ">
                                                AHLI PENIMBANG TARA
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white ">
                                        @foreach ($jttOfficers as $jttOfficer)
                                        <tr>
                                            <td class="p-1 text-sm font-normal text-left text-gray-500 whitespace-nowrap">
                                                {{ $jttOfficer->name() }}
                                            </td>
                                            <td class="p-1 text-sm font-normal text-left text-gray-500 whitespace-nowrap">
                                                {{ $jttOfficer->position() }}
                                            </td>
                                            <td class="p-1 text-sm font-normal text-center text-gray-500 whitespace-nowrap">
                                                <select id="small" class="flex-1 block w-full p-1 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" wire:model="jttRoles.{{ $jttOfficer->id }}">
                                                    <option value="">Sila Pilih</option>
                                                    <option value="1">PENGERUSI</option>
                                                    <option value="2">PENGERUSI GANTIAN</option>
                                                    <option value="3">AHLI-AHLI</option>
                                                    <option value="4">AHLI-AHLI GANTIAN</option>
                                                    <option value="5">PEMBENTANG</option>
                                                    <option value="6">URUSETIA</option>
                                                    <option value="7">PENGERUSI BERSAMA (PERHEBAT)</option>
                                                    <option value="8">AHLI (PERHEBAT)</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center mt-4">
                                <button class="inline-flex items-center p-5 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 " wire:click="startMeeting">
                                    MULA MESYUARAT
                                    <x-icon name="arrow-circle-right" class="w-6 h-6 ms-2" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
