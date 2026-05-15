<div class="ml-4">
    <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
        Laporan Peringkat PMGi Mengikut Batch, Negeri & Cawangan
    </h2>

    <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
        <form wire:submit.prevent="searchFilter" class="flex flex-col sm:flex-row items-center gap-4 sm:gap-2 mb-4">
            <div class="grid grid-cols-12 gap-y-2 gap-x-4 mb-4 lg:grid-cols-8 z-10">
                <!-- Start Date -->
                <div class="z-50 col-span-2">
                    <x-datetime-picker label="Tarikh Mula" placeholder="Tarikh Mula" display-format="DD-MM-YYYY" wire:model="report_start_date" without-time />
                </div>

                <!-- End Date -->
                <div class="z-50 col-span-2">
                    <x-datetime-picker label="Tarikh Akhir" placeholder="Tarikh Akhir" display-format="DD-MM-YYYY" wire:model="report_end_date" without-time />
                </div>

                @if($role == 'admin')
                    <!-- Negeri -->
                    <div class="col-span-2">
                        <x-select empty-message="Sila Pilih Negeri" class="z-50" label="Negeri" placeholder="Sila Pilih" :options="$stateSelection" option-label="description" option-value="code" wire:model.live="negeri" />
                    </div>
                @endif

                <!-- Cawangan -->
                <div class="col-span-2">
                    <x-select empty-message="Sila Pilih Cawangan" class="z-50" label="Cawangan" placeholder="Sila Pilih" :options="$branchSelection" option-label="branch_name" option-value="branch_code" wire:model.live="branch_code" />
                </div>

                <!-- Peringkat -->
                <div class="col-span-2">
                    <x-select empty-message="Sila Pilih Peringkat" class="z-50" label="Peringkat" placeholder="Sila Pilih" :options="$pmgiLevel" option-label="description" option-value="level" wire:model="peringkat" />
                </div>

                <!-- Batch -->
                <div class="col-span-2">
                    {{-- <x-select empty-message="Sila Pilih Batch" class="z-50" label="Batch" placeholder="Sila Pilih" :options="" option-label="" option-value="" wire:model.live="batch" /> --}}
                    <x-select empty-message="Sila Pilih Batch" class="z-50" label="Batch" placeholder="Sila Pilih" wire:model="batch" />
                </div>
            </div>
            
            <div class="flex gap-4 justify-end">
                <!-- Search Button -->
                <button type="submit" class="ml-4 px-3 py-2 text-white inline-flex items-center rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300">
                    Cari
                    <x-icon name="search" class="w-4 h-4 ms-2" />            
                </button>

                <!-- Reset Button -->
                <button type="button" wire:click='resetSearch' class="ml-4 px-3 py-2 w-60 text-gray-800 bg-gray-200 rounded-md hover:bg-gray-300">
                    Set Semula Carian
                </button>
            </div>
        </form>

        <div class="overflow-x-auto ">    
        {{-- Download PDF --}}
        <div class="flex justify-start mb-4">
            {{-- Jana PDF Button --}}
            <button type="button" wire:click="exportPDF()" class="px-4 py-2 w-auto bg-gray-50 text-gray-600 border rounded-md flex items-center justify-center dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="text-sm font-medium mr-2">Jana PDF</span>
                <svg height="30" width="30" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                    viewBox="0 0 512 512" xml:space="preserve">
                    <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"/>
                    <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"/>
                    <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "/>
                    <path style="fill:#F15642;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
                        V416z"/>
                    <g>
                        <path style="fill:#FFFFFF;" d="M101.744,303.152c0-4.224,3.328-8.832,8.688-8.832h29.552c16.64,0,31.616,11.136,31.616,32.48
                            c0,20.224-14.976,31.488-31.616,31.488h-21.36v16.896c0,5.632-3.584,8.816-8.192,8.816c-4.224,0-8.688-3.184-8.688-8.816V303.152z
                            M118.624,310.432v31.872h21.36c8.576,0,15.36-7.568,15.36-15.504c0-8.944-6.784-16.368-15.36-16.368H118.624z"/>
                        <path style="fill:#FFFFFF;" d="M196.656,384c-4.224,0-8.832-2.304-8.832-7.92v-72.672c0-4.592,4.608-7.936,8.832-7.936h29.296
                            c58.464,0,57.184,88.528,1.152,88.528H196.656z M204.72,311.088V368.4h21.232c34.544,0,36.08-57.312,0-57.312H204.72z"/>
                        <path style="fill:#FFFFFF;" d="M303.872,312.112v20.336h32.624c4.608,0,9.216,4.608,9.216,9.072c0,4.224-4.608,7.68-9.216,7.68
                            h-32.624v26.864c0,4.48-3.184,7.92-7.664,7.92c-5.632,0-9.072-3.44-9.072-7.92v-72.672c0-4.592,3.456-7.936,9.072-7.936h44.912
                            c5.632,0,8.96,3.344,8.96,7.936c0,4.096-3.328,8.704-8.96,8.704h-37.248V312.112z"/>
                    </g>
                    <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"/>
                </svg>          
            </button>
        </div>    
    
        <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg text-sm">
            {{-- Keep Action column tight --}}
            <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            </colgroup>
        
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
                    <th colspan="7" class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700" />
                    <th colspan="2" class="py-2 px-4 text-center border border-gray-300 dark:border-gray-700">
                        Bil PYD
                    </th>
                </tr>
                <tr class="bg-gray-100 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
                    <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">
                        Bulan PMG-i
                    </th>
                    <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">
                        Bulan Dinilai
                    </th>
                    <th class="py-2 px-3 text-left border border-gray-300 dark:border-gray-700">
                        Tarikh Penilaian  
                    </th>
                    <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">
                        Peringkat
                    </th>
                    <th class="py-2 px-2 text-left border border-gray-300 dark:border-gray-700">
                        Batch
                    </th>
                    <th class="py-2 px-3 text-left border border-gray-300 dark:border-gray-700">
                        Negeri
                    </th>
                    <th class="py-2 px-3 text-left border border-gray-300 dark:border-gray-700">
                        Cawangan
                    </th>
                    <th class="py-2 px-3 text-left border border-gray-300 dark:border-gray-700">
                        PP
                    </th>
                    <th class="py-2 px-3 text-left border border-gray-300 dark:border-gray-700">
                        PC
                    </th>
                </tr>
            </thead>
        
            <tbody class="text-sm text-gray-700 dark:text-gray-200">
            @forelse ($results as $item)
                <tr class="text-xs hover:bg-gray-50 dark:hover:bg-gray-800/70">
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->bulan_pmgi}}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->bulan_dinilai }}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ date('d/m/Y', strtotime($item->tarikh_penilaian)) }}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->peringkat}}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->batch }}</td> 
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->negeri }}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->cawangan }}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->bil_pp }}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->bil_pc }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="py-2 px-4 text-center text-gray-700 dark:text-gray-200">
                        Tiada data dijumpai.
                    </td>
                </tr>            
            @endforelse
            </tbody>
        </table>
    
        <div class="mt-6">
            {{ $results->links() }}
        </div>
        </div>
    </div>
</div>