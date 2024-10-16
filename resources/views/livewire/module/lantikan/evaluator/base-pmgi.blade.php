<div class="flex flex-col p-4 my-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6" x-cloak>
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-900">PMGi {{ $pmgiValue }}</h3>

        @if($datas->count() > 0)
        <div class="flex justify-center">
            <button type="submit" class="inline-flex items-center px-3 py-2 font-medium text-center text-white rounded-lg bg-primary-700 focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800" wire:click="showSelection({{ $pmgiValue }})">
                Pilih PYM @if($pmgiValue == '3') & PMC @endif
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
                            <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">JAWATAN</th>
                            <th scope="col" class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                <div class="flex justify-center">
                                    <x-checkbox id="checkbox-select-all" wire:model.live="selectAll" />
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($datas as $index => $data)
                        <tr class=" @if($data->status == 1) bg-green-100 @endif">
                            <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                {{ $loop->iteration }}
                            </td>
                            <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                {{ $data->username }}
                            </td>
                            <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                {{ $data->branch_name }}
                            </td>
                            <td class="p-2 text-sm font-normal text-left text-gray-900 border border-black whitespace-nowrap">
                                {{ $data->jawatan }}
                            </td>
                            <td class="p-1 text-sm font-normal text-center text-gray-500 border border-black whitespace-nowrap">
                                <div class="flex justify-center">
                                    @if($data->status == 0)
                                        <x-checkbox id="checkbox-{{ $data->userid }}" value="{{ $data->userid }}" wire:model="selection" />
                                    @else
                                        <div class="text-gray-900">
                                            <span class="block">PYM: {{ $data->pym_name ?? 'N/A' }}</span>
                                            @if($data->pmgi_level == '3')
                                            <span class="block">PMC: {{ $data->pmc_name ?? 'N/A' }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- selection modal --}}
    <x-modal.card title="Pilih PYM {{ ($pmgi == 3) ? 'dan PMC' : '' }} bagi PMGi {{ $pmgi }}" blur align="center" max-width="lg" wire:model="cardModal">
        <div>
            <x-select
                class="flex-1 block w-24 mr-4 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                Label="Pegawai Yang Menilai (PYM)"
                placeholder="Sila Pilih"
                :options="$pym"
                option-label="officer_name"
                option-value="userid"
                option-description="branch_name"
                wire:model="selectedPym" />

            @if($pmgi == 3)
                <x-select
                    class="flex-1 block w-24 mt-4 mr-4 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    Label="Pegawai Pemudah Cara (PMC)"
                    placeholder="Sila Pilih"
                    :options="$pym"
                    option-label="officer_name"
                    option-value="userid"
                    option-description="branch_name"
                    wire:model="selectedPmc" />
            @endif
        </div>

        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button primary label="Save" wire:click="save" />
            </div>
        </x-slot>
    </x-modal.card>

</div>
