<div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6" x-cloak>
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-900">PMGi 3</h3>
        @if($datas->count() > 0)
        <div class="flex justify-center">
            <button type="submit" class="inline-flex items-center px-3 py-2 font-medium text-center text-white rounded-lg bg-primary-700 focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800" wire:click="save">
                Pilih PYM & PMC
            </button>
        </div>
        @endif
    </div>

    <div class="mt-6 overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">

                @if($datas->count() == 0)
                <div wire:loading.class="hidden" class="flex justify-center">
                    <img class="h-96" src="{{ asset('image/animation/no_data.gif') }}" alt="No Data">
                </div>
                @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="bg-gray-100">
                            <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">BIL</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">PEGAWAI</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">CAWANGAN</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($datas as $index => $data)
                        <tr class="{{ $index % 2 != 0 ? 'bg-blue-50' : '' }}">
                            <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                {{ $loop->iteration }}
                            </td>
                            <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                {{ $data->username }}
                            </td>
                            <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                {{ $data->branch_name }}
                            </td>
                            <td class="p-1 text-sm font-normal text-left text-gray-500 border border-black whitespace-nowrap">

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
