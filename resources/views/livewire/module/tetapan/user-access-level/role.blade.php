<div>
    <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
        <div class="flex justify-end">
            <x-button md icon="plus-circle" positive label="Tambah Kumpulan" wire:click="add" />
        </div>
        <div class="flex flex-col mt-6">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="table-scroll-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 ">
                                <tr class="bg-gray-200">
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">NAMA KUMPULAN</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">AKSES</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white ">
                                @forelse ($results as $result)
                                <tr class="even:bg-gray-100"">
                                        <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $result->name }}</td>
                                    <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">
                                        <div class="grid grid-cols-3 gap-2">
                                            @foreach ($result->pages as $data)
                                            <x-badge flat info label="{{ $data->page }}" />
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 whitespace-nowrap">
                                        <x-button icon="pencil" info label="Kemaskini" wire:click="edit({{ $result->id }})" />
                                        @if (!in_array($result->name, ['ADMINISTRATOR', 'PYD', 'PYM', 'PMC', 'PTT', 'URUSETIA NEGERI', 'HR']))
                                            <x-button icon="trash" negative label="Padam" wire:click="remove({{ $result->id }})" />
                                        @endif
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

    <x-modal blur wire:model="roleModal" align="center" max-width="lg">
        <x-card title="{{ $roleId ? 'Edit Kumpulan' : 'Tambah Kumpulan' }}">
            <x-input label="Nama Kumpulan" placeholder="Nama kumpulan" wire:model="name" />
            <x-select class="mt-2" label="Halaman" placeholder="Pilih halaman boleh diakses" multiselect :options="$options" option-label="page" option-value="id" wire:model="page" />

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-button flat label="Batal" x-on:click="close" />
                    <x-button positive label="{{ $roleId ? 'Kemaskini' : 'Simpan' }}" wire:click="save" />
                </div>
            </x-slot>
        </x-card>
    </x-modal>

</div>
