<div class="ml-4">
  <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
    Penyelenggaraan Peratusan Penilaian PMGi
  </h2>
  
  <div class="overflow-x-auto bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
    <form wire:submit.prevent="searchState" class="flex items-center gap-2 mb-2">
        <label class="px-3 py-2 whitespace-nowrap">
            Cari Negeri:
        </label>

        <input type="text"
               wire:model="state_name"
               placeholder="Nama Negeri"
               class="px-2 py-2 border w-64 border-gray-300 rounded-md shadow-sm" />          

        <!-- Search Button -->
        <button type="submit"
                class="ml-4 px-3 py-2 text-white inline-flex items-center rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300">
            Cari
            <x-icon name="search" class="w-4 h-4 ms-2" />            
        </button>

          <!-- Reset Button -->
        <button type="button" wire:click='resetSearch'
                class="ml-4 px-3 py-2 text-gray-800 bg-gray-200 rounded-md hover:bg-gray-300">
            Set Semula Carian
        </button>
    </form>

      {{-- Insert New Column --}}
      <div class="flex justify-end mb-4">
          <button type="button" wire:click="add()"
                  class="px-4 py-2 w-auto bg-gray-50 text-gray-600 border rounded-md flex items-center dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
              <span>Tambah Peratusan Penilaian </span>
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
        <col>
        <col class="w-px">
      </colgroup>
  
      <thead>
        <tr class="bg-gray-300 border-gray-100 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
            <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">ID</th>
            <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Tarikh Kuatkuasa</th>
            <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Negeri</th>
            {{-- <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Penilaian</th> --}}
            <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Peratus Penilaian (%)</th>
            <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Dikemas Kini Pada</th>
            <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Dikemas Kini Oleh</th>
            <th class="py-2 px-2 text-left border border-gray-400 dark:border-gray-700 w-px whitespace-nowrap">Tindakan</th>
        </tr>
      </thead>

    {{-- @php
        $evaluation_labels = [
            1 => 'Kriteria 1 - Kutipan',
            2 => 'Kriteria 2 - Bilangan membayar',
            3 => 'Kriteria 3 - Lawatan Seliaan',
            4 =>'Kriteria 4 - Prestasi NPF (Kawalan)',
            5 =>'Kriteria 5 - Prestasi NPF (Pemulihan)',
        ];
    @endphp --}}
    
      <tbody class="text-sm text-gray-700 dark:text-gray-200">
        @forelse ($data as $item)
          <tr class="bg-gray-200 hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-800/70">
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->id }}</td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->effective_date }}</td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->bnmState->description }}</td>
            {{-- <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">
                {{ $evaluation_labels[$item->evaluation_id] ?? $item->evaluation_id }}
            </td> --}}
            <td class="py-2 px-4 bg-white hover:bg-gray-100 border dark:hover:bg-gray-800/70 border-gray-300 dark:bg-gray-500 dark:border-gray-700">
                {{ $item->evaluation_percentage }}
            </td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->updated_at }}</td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->updated_by }}</td>            
  
            {{-- Action --}}
            <td class="py-2 px-2 border border-gray-400 dark:border-gray-700 w-px whitespace-nowrap">
              <div class="flex items-center gap-2">
                <button type="button" wire:click="edit(@js($item->id))"
                  class="inline-flex items-center gap-1 text-white bg-blue-500 hover:bg-blue-600 border border-transparent px-3 py-1 rounded-md">
                  <span>Edit</span>
                </button>
              </div>
            </td>
          </tr>

        @empty
          <tr>
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

    @if ($insert)
        {{-- Modal Background --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950 bg-opacity-50"
                wire:keydown.escape="$set('insert', true)"
                wire:click.self="$set('insert', true)">
            <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-xl">
                <h3 class="text-xl font-semibold mb-4">Tambah Peratusan Penilaian</h3>
    
                <form wire:submit.prevent="store" method="POST">
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Tarikh Kuatkuasa:</label>
                        <x-datetime-picker 
                            wire:model="effective_date"
                            placeholder="dd/mm/yyyy"
                            without-time
                            :clearable="false"          
                            display-format="DD/MM/YYYY"  
                            min="{{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString() }}"  
                            errorless                                              
                            style="
                            padding: 0.5rem;
                            font-size: 1rem;
                            line-height: 1.5rem; 
                             border-color: rgb(107 114 128 / 1);            
                            "            
                        />            
                        @error('effective_date')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Negeri:</label>
                        <select type="text" name="state_code" wire:model='state_code' class="w-full p-2 border border-gray-500 rounded-md" >
                            <option value="">Pilih Negeri</option>

                            @foreach ($states as $stcode)
                                <option value="{{ $stcode->code }}">{{ $stcode->description }}</option>
                            @endforeach
                        </select>
                        @error('state_code')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Penilaian:</label>
                        <select type="text" name="evaluation_id" wire:model='evaluation_id' class="w-full p-2 border border-gray-500 rounded-md">
                            <option value="">Pilih Kriteria Penilaian</option>

                            @foreach ($evaluation_labels as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('evaluation_id')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror                        
                    </div> --}}
                    
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Peratus Penilaian (%):</label>
                        <input type="numeric" name="evaluation_percentage" wire:model='evaluation_percentage' class="w-full p-2 border border-gray-500 rounded-md" />
                        @error('evaluation_percentage')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror                        
                    </div>                    
  
                    <div class="flex justify-end gap-3">
                        <button type="button"
                                wire:click="close()"
                                class="py-2 px-4 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Cancel
                        </button>
    
                        <button type="submit"                    
                                class="py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>                  
                </form>
            </div>
        </div>
    @endif
  
    @if ($edits)
        {{-- Modal Background --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
                wire:keydown.escape="$set('edits', true)"
                wire:click.self="$set('edits', true)">
            <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-xl">
                <h3 class="text-xl font-semibold mb-4">Kemas Kini Peratus Penilaian</h3>
    
                <form wire:submit.prevent="update" method="POST">
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Tarikh Kuatkuasa:</label>
                        <input type="text" name="effective_date" wire:model='effective_date' class="w-full p-2 bg-gray-100 border border-gray-500 rounded-md" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Negeri:</label>
                        <input type="text" name="state_code" wire:model='state_code' class="w-full p-2 bg-gray-100 border border-gray-500 rounded-md" readonly/>
                    </div>        
                    
                    {{-- <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Penilaian:</label>
                        <input type="text" name="evaluation_id" wire:model='evaluation_id' class="w-full p-2 bg-gray-100 border border-gray-500 rounded-md" readonly/>             
                    </div> --}}

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Peratus Penilaian (%):</label>
                        <input type="text" name="evaluation_percentage" wire:model='evaluation_percentage' class="w-full p-2 border border-gray-500 rounded-md" />
                        @error('evaluation_percentage')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror                            
                    </div>   
  
                    <div class="flex justify-end gap-3">
                        <button type="button"
                                wire:click="close()"
                                class="py-2 px-4 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Cancel
                        </button>
    
                        <button type="submit"
                                class="py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>                  
                </form>
            </div>
        </div>
    @endif  
  </div>
</div>