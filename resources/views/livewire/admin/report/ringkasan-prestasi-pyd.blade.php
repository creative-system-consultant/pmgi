<div class="ml-4">
    <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
        Laporan Ringkasan Prestasi PYD Mengikut Negeri & Cawangan
    </h2>

    <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
        <form wire:submit.prevent="searchFilter" class="flex flex-col sm:flex-row items-center gap-4 sm:gap-2 mb-4">
            <div class="grid grid-cols-12 gap-y-2 gap-x-4 mb-4 lg:grid-cols-8 z-10">
                <!-- Start Date -->
                <div class="z-50 col-span-2">
                    <x-datetime-picker label="Tarikh Laporan" placeholder="Tarikh Laporan" display-format="DD-MM-YYYY" wire:model="report_date" without-time />
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
                    <th scope="col" rowspan="2" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">NEGERI</th>
                    <th scope="col" rowspan="2" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">CAWANGAN</th>
                    <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">
                        KRITERIA 1 <br>
                        PATUT KUTIP (RM)<br>VS<br>DAPAT KUTIP (RM) <br>
                        {{ $percentage->get(0)->evaluation_percentage }}%
                    </th>
                    <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">
                        KRITERIA 2 <br>
                        PATUT KUTIP (BIL)<br>VS<br>DAPAT KUTIP (BIL) <br>
                        {{ $percentage->get(1)->evaluation_percentage }}%
                        
                    </th>
                    <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">
                        KRITERIA 3 <br>
                        LAWATAN SELIAAN <br>
                        {{ $percentage->get(2)->evaluation_percentage }}%
                    </th>
                    <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">
                        KRITERIA 4 <br>
                        PRESTASI NPF (KAWALAN) <br>
                        {{ $percentage->get(3)->evaluation_percentage }}%
                    </th>
                    <th scope="col" colspan="4" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">
                        KRITERIA 5
                        PRESTASI NPF (PEMULIHAN) <br>
                        {{ $percentage->get(4)->evaluation_percentage }}%
                    </th>
                    <th scope="col" rowspan="2" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">% CAPAI</th>
                    <th scope="col" rowspan="2" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border-gray-300 border-solid border">% TIDAK CAPAI</th>
                </tr>
                <tr class="bg-gray-200">
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL TIDAK CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">TOTAL BIL</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">% CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL TIDAK CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">TOTAL BIL</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">% CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL TIDAK CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">TOTAL BIL</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">% CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL TIDAK CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">TOTAL BIL</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">% CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">BIL TIDAK CAPAI</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">TOTAL BIL</th>
                    <th scope="col" class="p-2 text-xs font-medium tracking-tight text-center text-gray-500 uppercase border border-gray-300 border-solid whitespace-nowrap">% CAPAI</th>
                </tr>
            </thead>
        
            <tbody class="text-sm text-gray-700 dark:text-gray-200">
            @forelse ($results as $item)
                <tr class="text-xs hover:bg-gray-50 dark:hover:bg-gray-800/70">
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->negeri}}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->cawangan }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->rm_dpt_kutip}}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->xrm_dpt_kutip }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->k1_tot_bil }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ number_format($item->k1_pctg_capai, 2) }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->bil_dpt_kutip }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->xbil_dpt_kutip }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->k2_tot_bil }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ number_format($item->k2_pctg_capai, 2) }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->bil_lawat }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->xbil_lawat }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->k3_tot_bil }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ number_format($item->k3_pctg_capai, 2) }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->bil_kawal_npf }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->xbil_kawal_npf }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->k4_tot_bil }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ number_format($item->k4_pctg_capai, 2) }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->bil_pulih_npf }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->xbil_pulih_npf }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ $item->k5_tot_bil }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ number_format($item->k5_pctg_capai, 2) }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ number_format($item->tot_pctg_capai, 2) }}</td>
                <td class="py-2 px-4 text-right border border-gray-300 dark:border-gray-700">{{ number_format($item->tot_pctg_xcapai, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="30" class="py-2 px-4 text-center text-gray-700 dark:text-gray-200">
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