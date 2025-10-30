<div class="ml-4">
  <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
    Laporan Keciciran Pengurus
  </h2>
  <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
    <form wire:submit.prevent="searchDate" class="flex flex-col sm:flex-row items-center gap-4 sm:gap-2 mb-4">           
        <div class="flex items-center rounded-md">
            <label class="px-3 py-2 whitespace-nowrap">
                Tarikh Laporan:
            </label>
  
            <div>
              <x-datetime-picker 
                  wire:model="report_date"
                  placeholder="dd/mm/yyyy"
                  without-time 
                  :clearable="false"              
                  display-format="DD/MM/YYYY"
                  min="{{ $lastDaysOfMonths[0] }}"
                  max="{{ $lastDaysOfMonths[count($lastDaysOfMonths) - 1] }}" 
                  style="
                    padding-top: 0.6rem;
                    padding-bottom: 0.6rem;
                    padding-left: 0.6rem;
                    padding-right: 0.6rem;
                    font-size: 0.985rem;
                    line-height: 1.5rem;
                  "          
              />
            </div>
        </div>        
  
        <!-- Search Button -->
        <button type="submit"
                class="ml-4 px-3 py-2 text-white inline-flex items-center rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300">
            Cari
            <x-icon name="search" class="w-4 h-4 ms-2" />            
        </button>
  
          <!-- Reset Button -->
        <button type="button" wire:click='resetSearch';
                class="ml-4 px-3 py-2 text-gray-800 bg-gray-200 rounded-md hover:bg-gray-300">
            Set Semula Carian
        </button>
      </form>
      <div class="overflow-x-auto">
    
        <div class="flex justify-end mb-4">
            <button
                wire:click='exportExcel()' class="flex items-center w-48 bg-white text-gray-600 border border-gray-300 px-4 py-2 mx-1 transition-colors duration-200 rounded-md focus:outline-none" >
                <span class="mr-2">Download Excel</span>  
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 48 48">
                    <path fill="#169154" d="M29,6H15.744C14.781,6,14,6.781,14,7.744v7.259h15V6z"></path><path fill="#18482a" d="M14,33.054v7.202C14,41.219,14.781,42,15.743,42H29v-8.946H14z"></path><path fill="#0c8045" d="M14 15.003H29V24.005000000000003H14z"></path><path fill="#17472a" d="M14 24.005H29V33.055H14z"></path><g><path fill="#29c27f" d="M42.256,6H29v9.003h15V7.744C44,6.781,43.219,6,42.256,6z"></path><path fill="#27663f" d="M29,33.054V42h13.257C43.219,42,44,41.219,44,40.257v-7.202H29z"></path><path fill="#19ac65" d="M29 15.003H44V24.005000000000003H29z"></path><path fill="#129652" d="M29 24.005H44V33.055H29z"></path></g><path fill="#0c7238" d="M22.319,34H5.681C4.753,34,4,33.247,4,32.319V15.681C4,14.753,4.753,14,5.681,14h16.638 C23.247,14,24,14.753,24,15.681v16.638C24,33.247,23.247,34,22.319,34z"></path><path fill="#fff" d="M9.807 19L12.193 19 14.129 22.754 16.175 19 18.404 19 15.333 24 18.474 29 16.123 29 14.013 25.07 11.912 29 9.526 29 12.719 23.982z"></path>
                </svg>
            </button>                    
        </div>      
    
        <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg text-sm">
          {{-- Keep Action column tight --}}
          <colgroup>
            <col class="min-w-[90px] w-px">
            <col>
            <col>
            <col>
            <col>
            <col>
          </colgroup>
      
          <thead>
            <tr class="bg-gray-100 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
                <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">No</th>
                <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Negeri</th>
                <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Kod Cawangan</th>
                <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Cawangan</th>
                <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Tarikh Laporan</th>
            </tr>
          </thead>
        
          <tbody class="text-sm text-gray-700 dark:text-gray-200">
            @forelse ($data as $item)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">
                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                </td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->negeri }}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->branch_code }}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ $item->cawangan }}</td>
                <td class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">{{ date('d/m/Y', strtotime($item->report_date)) }}</td>
              </tr>
    
            @empty
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
                  <td colspan="7" class="py-2 px-4 text-center text-gray-700 dark:text-gray-200">
                      Tiada data dijumpai.
                  </td>
              </tr>            
            @endforelse
          </tbody>
        </table>
    
        <div class="mt-6">
          {{ $data->links() }}
        </div>
      </div>
    </div>
</div>
  