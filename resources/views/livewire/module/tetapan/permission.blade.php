<div>
    <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
        <div class="flex justify-end">
            <x-button md icon="plus-circle" positive label="Tambah Permission" wire:click="add" />
        </div>
        <div class="flex flex-col mt-6">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="table-scroll-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 ">
                                <tr class="bg-gray-200">
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">PERMISSION</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">ACTION</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white ">
                                @foreach ($results as $user)
                                <tr class="even:bg-gray-50"">
                                        <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $user->username }}</td>
                                    <td class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 whitespace-nowrap">
                                        <x-button icon="pencil" info label="Edit" />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal blur wire:model.defer="addPermissionModal" align="center" max-width="lg">
        <x-card title="Tambah Permission">
            <x-input label="Nama Permission" placeholder="nama permission" />

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button positive label="Simpan" />
                </div>
            </x-slot>
        </x-card>
    </x-modal>

</div>
