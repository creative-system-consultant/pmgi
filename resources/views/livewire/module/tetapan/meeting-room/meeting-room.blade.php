<div>
    <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
        <div class="flex items-center w-full mt-4">
            <div class="flex-1">
                <h3 class="mb-2 text-xl font-bold text-gray-900 ">Bilik Mesyuarat</h3>

                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="default_size">Nama Bilik Mesyuarat:</label>
                <input class="block w-full mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" wire:keydown.enter="add" wire:model="room_name">
            </div>
            <div class="ml-5">
                <x-button md icon="pencil" positive label="Tambah" wire:click="add" />
            </div>
        </div>

        <div class="flex flex-col mt-6">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="table-scroll-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 ">
                                <tr class="bg-gray-200">
                                    <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">BIL.</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">Bilik Mesyuarat</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white ">
                                @forelse ($rooms as $room)
                                    <tr>
                                        <td class="p-2 text-sm font-semibold tracking-tight text-left text-gray-800 border border-black border-dashed whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="p-2 text-sm font-semibold tracking-tight text-left text-gray-800 border border-black border-dashed whitespace-nowrap">{{ $room->room_name }}</td>
                                        <td class="p-2 text-sm font-semibold tracking-tight text-center text-gray-800 border border-black border-dashed whitespace-nowrap">
                                            <x-button icon="trash" negative label="Padam" wire:click="remove({{ $room->id }})" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-2 text-sm font-semibold tracking-tight text-center text-gray-800 whitespace-nowrap">Tiada Data</td>
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
