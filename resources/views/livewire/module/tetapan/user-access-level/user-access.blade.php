<div>
    <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
        <div class="flex justify-between">
            <div class="grid w-[50%] grid-cols-1 gap-x-4 gap-y-2">
                <div>
                    <label for="nama-pegawai" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Nama Pegawai</label>
                    <input type="text" id="nama-pegawai" class="block w-full p-2 text-xs text-gray-900 uppercase border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500" wire:model="name" wire:keydown.enter="find">
                </div>
            </div>
            <div class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded-lg cursor-pointer bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" wire:click="find">
                Cari
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
            </div>
        </div>

        <!-- Result -->
        @if($name)
        <div class="flex flex-col mt-6">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="table-scroll-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 ">
                                <tr class="bg-gray-200">
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">NAMA PEGAWAI</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-left text-gray-500 uppercase bg-gray-200 headcol">GELARAN</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol">KUMPULAN</th>
                                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase bg-gray-200 headcol"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white ">
                                @forelse ($results as $user)
                                <tr class="even:bg-gray-50"">
                                            <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800  whitespace-nowrap">{{ $user->username }}</td>
                                    <td class="p-2 text-sm font-normal tracking-tight text-left text-gray-800 whitespace-nowrap">{{ $user->position() }}</td>
                                    <td class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 whitespace-nowrap">
                                        @foreach ($user->roles as $data)
                                        <x-badge flat info label="{{ $data->name }}" />
                                        @endforeach
                                    </td>
                                    <td class="p-2 text-sm font-normal tracking-tight text-center text-gray-800 whitespace-nowrap">
                                        <x-button sm icon="pencil" info label="Kemaskini" wire:click="showModal('{{ $user->userid }}')" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="p-2 text-sm font-semibold tracking-tight text-center text-gray-800 whitespace-nowrap">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <x-modal blur wire:model="userAccessModal" align="center" max-width="lg">
        <x-card title="Tetapkan Akses">
            <x-input label="Nama Pegawai" wire:model="name" />
            <x-select class="mt-2" label="Kumpulan" placeholder="Pilih kumpulan" multiselect :options="$options" option-label="name" option-value="id" wire:model="role" />

            <x-slot name="footer">
                <div class="flex justify-end gap-x-4">
                    <x-button flat label="Batal" x-on:click="close" />
                    <x-button positive label="Simpan" wire:click="save" />
                </div>
            </x-slot>
        </x-card>
    </x-modal>

</div>
