<div class="flex flex-col p-4 my-4 mt-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6" x-cloak>
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-900">PMGi {{ $pmgiValue }}</h3>

        @if($datas->count() > 0)
        <div class="flex justify-center">
            <button type="submit" class="inline-flex items-center px-3 py-2 font-medium text-center text-white rounded-lg bg-primary-700 focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800" wire:click="showSelection({{ $pmgiValue }})">
                Pilih PYM @if($pmgiValue == '3') & PMC @endif
            </button>
        </div>
        @endif
    </div>

    <div class="overflow-x-auto mt-6">
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
                            <th scope="col" class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">GELARAN</th>
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
                            <td class="p-2 text-sm font-normal text-left text-gray-900 whitespace-nowrap border border-black">
                                {{ $loop->iteration }}
                            </td>
                            <td class="p-2 text-sm font-normal text-left text-gray-900 whitespace-nowrap border border-black">
                                {{ $data->USERNAME }}
                            </td>
                            <td class="p-2 text-sm font-normal text-left text-gray-900 whitespace-nowrap border border-black">
                                {{ $data->branch_name }}
                            </td>
                            <td class="p-2 text-sm font-normal text-left text-gray-900 whitespace-nowrap border border-black">
                                {{ $data->gelaran }}
                            </td>
                            <td class="p-1 text-sm font-normal text-center text-gray-500 whitespace-nowrap border border-black">
                                <div class="flex justify-center">
                                    @if($data->status == 0)
                                        <x-checkbox id="checkbox-{{ $data->USERID }}" value="{{ $data->USERID }}" wire:model="selection" />
                                    @else
                                        <div class="text-gray-900">
                                            <span class="block">PYM: {{ $data->pym_name ?? 'N/A' }}</span>
                                            @if($data->pmgi_level == 'PM3')
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
                class="block flex-1 mr-4 w-24 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                Label="Pegawai Yang Menilai (PYM)"
                placeholder="Sila Pilih"
                :options="$pym"
                option-label="officer_name"
                option-value="USERID"
                option-description="branch_name"
                wire:model="selectedPym" />

            @if($pmgi == 3)
                <x-select
                    class="block flex-1 mt-4 mr-4 w-24 text-sm text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    Label="Pegawai Pemudah Cara (PMC)"
                    placeholder="Sila Pilih"
                    :options="$pmc"
                    option-label="officer_name"
                    option-value="userid"
                    option-description="branch_name"
                    wire:model="selectedPmc" />
            @endif
        </div>

        <x-slot name="footer">
            <div class="flex gap-x-4 justify-between">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button primary label="Save" wire:click="save" />
            </div>
        </x-slot>
    </x-modal.card>

</div>
