<div class="ml-4">
  <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
    Penyelenggaraan Senarai Negeri HR to FMS
  </h2>
  
  <div class="overflow-x-auto bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
    <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg text-sm">
      {{-- Keep Action column tight --}}
      <colgroup>
        <col>
        <col>
        <col>
        <col class="w-px">
      </colgroup>
  
      <thead>
        <tr class="bg-gray-300 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Nama Negeri Dalam Sistem HR</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Kod Negeri Dalam Sistem FMS</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Nama Negeri Dalam Sistem FMS</th>
          <th class="py-2 px-2 text-left border border-gray-400 dark:border-gray-700 w-px whitespace-nowrap">Tindakan</th>
        </tr>
      </thead>
  
      <tbody class="text-sm text-gray-700 dark:text-gray-200">
        @forelse ($data as $item)
          <tr class="bg-gray-200 hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-800/70">
            <td class="py-2 px-4 bg-white hover:bg-gray-100 border border-gray-400 dark:bg-gray-500 dark:border-gray-700">
              {{ $item->hr_state_name }}
            </td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->fms_state_code }}</td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->fms_state_name }}</td>
  
            {{-- Action --}}
            <td class="py-2 px-2 border border-gray-400 dark:border-gray-700 w-px whitespace-nowrap">
              <div class="flex items-center gap-2">
                <button type="button" wire:click="edit(@js($item->fms_state_name))"
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
  
    @if ($edits)
        {{-- Modal Background --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
                wire:keydown.escape="$set('edits', true)"
                wire:click.self="$set('edits', true)">
            <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-xl">
                <h3 class="text-xl font-semibold mb-4">Kemas Kini Nama Negeri Dalam Sistem HR</h3>
    
                <form wire:submit.prevent="update" method="POST">
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Negeri Dalam Sistem HR:</label>
                        <input type="text" name="hr_state_name" wire:model='hr_state_name' class="w-full p-2 border border-gray-500 rounded-md" />
                        @error('hr_state_name')
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