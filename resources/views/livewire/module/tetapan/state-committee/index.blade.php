<div>
    <div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
        <h3 class="mb-2 text-xl font-bold text-gray-900">Lantikan Urusetia</h3>
        <span class="text-base font-normal text-gray-500">Lantikan Urusetia Negeri</span>

        <div class="mt-6 overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="bg-gray-100">
                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">
                                    BIL
                                </th>
                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">
                                    NEGERI
                                </th>
                                <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                    URUSETIA
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($datas as $index => $state)
                            <tr class="{{ $index % 2 != 0 ? 'bg-blue-50' : '' }}">
                                <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                    {{ $state->description }}
                                </td>
                                <td class="p-1 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                    <x-select placeholder="Pilih Urusetia" :options="$filteredOptions[$state->code] ?? []" option-label="username" option-value="userid" wire:model.lazy="selectedUsers.{{ $state->code }}" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex justify-center mt-6">
                        <button type="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800" wire:click="save">
                            Simpan
                        </button>
                    </div>
                    <div class="flex justify-center mt-6">
                        <button type="submit" class="inline-flex items-center py-2.5 px-4 font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800" wire:click="test">
                            test sp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
