<div class="ml-4">
  <style>
    .x-datetime-picker .error-message {
        display: none;
    }
  </style>
  <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
    Penyelenggaraan Tempoh PMGi
  </h2>
  
  <div class="overflow-x-auto bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
      {{-- Insert New Column --}}
      <div class="flex justify-end mb-4">
          <button type="button" wire:click="add()"
                  class="px-4 py-2 w-auto bg-gray-50 text-gray-600 border rounded-md flex items-center dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
              <span>Tambah Tempoh PMGi</span>
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
      </colgroup>
  
      <thead>
        <tr class="bg-gray-100 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
          <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Tarikh Kuat Kuasa</th>
          <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Peringkat PMGi</th>
          <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Tempoh Menunggu (Bulan)</th>
          <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Dicipta Pada</th>
          <th class="py-2 px-4 text-left border border-gray-300 dark:border-gray-700">Dicipta Oleh</th>
        </tr>
      </thead>
  
      <tbody class="text-sm text-gray-700 dark:text-gray-200">
        @forelse ($data as $item)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/70">
            <td class="py-2 px-4 border border-gray-300 dark:border-gray-700">{{ $item->effective_date }}</td>
            <td class="py-2 px-4 border border-gray-300 dark:border-gray-700">{{ $item->level->pmgi_level }}</td>
            <td class="py-2 px-4 border border-gray-300 dark:border-gray-700">{{ $item->wait_period }}</td>
            <td class="py-2 px-4 border border-gray-300 dark:border-gray-700">{{ $item->created_at }}</td>
            <td class="py-2 px-4 border border-gray-300 dark:border-gray-700">{{ $item->created_by }}</td>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
                wire:keydown.escape="$set('insert', true)"
                wire:click.self="$set('insert', true)">
            <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-xl">
                <h3 class="text-xl font-semibold mb-4">Tambah Tempoh PMGi</h3>
    
                <form wire:submit.prevent="store" method="POST">
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Tarikh Kuat Kuasa:</label>
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
                        <label class="block text-base font-medium text-gray-600">Peringkat pmgi:</label>
                        <select type="text" name="pmgi_level" wire:model='pmgi_level' class="w-full p-2 border border-gray-500 rounded-md">
                            <option value="">Pilih Tahap pmgi</option>

                            @foreach ($data as $pmgi)
                                <option value="{{ $pmgi->level->pmgi_level }}">
                                    {{ $pmgi->level->pmgi_level }} - {{ $pmgi->level->pmgi_level_desc }}
                                </option>
                            @endforeach
                        </select>
                        @error('pmgi_level')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror
                    </div>    
                                    
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Tempoh Menunggu (Bulan):</label>
                        <input type="text" name="wait_period" wire:model='wait_period' class="w-full p-2 border border-gray-500 rounded-md"/>
                        @error('wait_period')
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