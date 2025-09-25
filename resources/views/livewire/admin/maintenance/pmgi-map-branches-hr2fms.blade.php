<div class="ml-4">
  <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
    Penyelenggaraan Senarai Cawangan HR Kepada FMS
  </h2>
  
  <div class="overflow-x-auto bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
      {{-- Insert New Column --}}
      <div class="flex justify-end mb-4">
          <button type="button" wire:click="add()"
                  class="px-4 py-2 w-auto bg-gray-50 text-gray-600 border rounded-md flex items-center dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
              <span>Tambah Senarai Cawangan</span>
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
        <col class="w-px">
      </colgroup>
  
      <thead>
        <tr class="bg-gray-300 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Seq No</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Nama Negeri Dalam Sistem FMS</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Nama Cawangan Dalam Sistem FMS</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Kod Cawangan FMS</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Name Negeri Dalam Sistem HR</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Nama Cawangan Dalam Sistem HR</th>
          <th class="py-2 px-2 text-left border border-gray-400 dark:border-gray-700 w-px whitespace-nowrap">Tindakan</th>
        </tr>
      </thead>
  
      <tbody class="text-sm text-gray-700 dark:text-gray-200">
        @forelse ($data as $item)
          <tr class=" bg-gray-200 hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-800/70">
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">
              {{ $item->seq_no }}
            </td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->fms_state_name }}</td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->fms_branch_name }}</td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->fms_branch_code }}</td>
            <td class="py-2 px-4 bg-white hover:bg-gray-100 border border-gray-300 dark:bg-gray-500 dark:border-gray-700">
                {{ $item->hr_state_name }}
            </td>
            <td class="py-2 px-4 bg-white hover:bg-gray-100 border border-gray-300 dark:bg-gray-500 dark:border-gray-700">
                {{ $item->hr_branch_name }}
            </td>
  
            {{-- Action (tight, no wrap) --}}
            <td class="py-2 px-2 border border-gray-400 dark:border-gray-700 w-px whitespace-nowrap">
              <div class="flex items-center gap-2">
                <button type="button" wire:click="edit({{ $item->seq_no }})"
                  class="inline-flex items-center gap-1 text-white bg-blue-500 hover:bg-blue-600 border border-transparent px-3 py-1 rounded-md">
                  <span>Edit</span>
                </button>
  
                <button type="button" wire:click="confirmDelete({{ $item->seq_no }})"
                  class="inline-flex items-center gap-1 text-white bg-red-500 hover:bg-red-600 border border-transparent px-3 py-1 rounded-md">
                  <span>Hapus</span>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
                wire:keydown.escape="$set('insert', true)"
                wire:click.self="$set('insert', true)">
            <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-xl">
                <h3 class="text-xl font-semibold mb-4">Tambah Senarai Cawangan HR Kepada FMS</h3>

                <form wire:submit.prevent="store" method="POST">
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Negeri Dalam Sistem FMS:</label>
                        <select type="text" name="fms_state_name" wire:model='fms_state_name' wire:change='loadBranches' class="w-full p-2 border border-gray-500 rounded-md">
                            <option value="">Pilih Nama Negeri Dalam Sistem FMS</option>

                            @foreach ($state as $fms)
                                <option value="{{ $fms->fms_state_name }}">{{ $fms->fms_state_name }}</option>
                            @endforeach
                        </select>
                        @error('fms_state_name')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror                        
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Cawangan Dalam Sistem FMS:</label>
                        <select type="text" name="fms_branch_name" wire:model='fms_branch_name' wire:change='loadBranchCode' class="w-full p-2 border border-gray-500 rounded-md" @if(!$fms_state_name) disabled class="bg-gray-100" @endif>
                            @if (!$fms_state_name)
                                <option value="">Pilih Nama Negeri Dalam Sistem FMS Dahulu</option>
                            @else
                                <option value="">Pilih Nama Cawangan Dalam Sistem FMS</option>
                            @endif

                            @foreach ($filterBranches as $br)
                                <option value="{{ $br }}">{{ $br }}</option>
                            @endforeach
                            
                        </select>
                        @error('fms_branch_name')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror                        
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Kod Cawangan FMS:</label>
                        <input type="text" name="fms_branch_code" wire:model='fms_branch_code' class="w-full p-2 border bg-gray-100 border-gray-500 rounded-md" readonly/>
                        @error('fms_branch_code')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror                        
                    </div>                    

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Negeri Dalam Sistem HR:</label>
                        <select type="text" name="hr_state_name" wire:model='hr_state_name' class="w-full p-2 border border-gray-500 rounded-md">
                            <option value="">Pilih Nama Negeri Dalam Sistem HR</option>
                            
                            @foreach ($state as $hr)
                                <option value="{{ $hr->hr_state_name }}">{{ $hr->hr_state_name }}</option>
                            @endforeach
                        </select>
                        @error('hr_state_name')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Cawangan Dalam Sistem HR:</label>
                        <input type="text" name="hr_branch_name" wire:model='hr_branch_name' class="w-full p-2 border border-gray-500 rounded-md" />
                        @error('hr_branch_name')
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
                <h3 class="text-xl font-semibold mb-4">Kemas Kini Nama Negeri dan Nama Cawangan Dalam Sistem HR</h3>
    
                <form wire:submit.prevent="update" method="POST">
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Negeri Dalam Sistem FMS:</label>
                        <input type="text" name="fms_state_name" wire:model='fms_state_name' class="w-full p-2 border bg-gray-100 border-gray-500 rounded-md" readonly/>
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Cawangan Dalam Sistem FMS:</label>
                        <input type="text" name="fms_branch_name" wire:model='fms_branch_name' class="w-full p-2 border bg-gray-100 border-gray-500 rounded-md" readonly/>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Kod Cawangan FMS:</label>
                        <input type="text" name="fms_branch_code" wire:model='fms_branch_code' class="w-full p-2 border bg-gray-100 border-gray-500 rounded-md" readonly/>
                    </div>                    

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Negeri Dalam Sistem HR:</label>
                        <select type="text" name="hr_state_name" wire:model='hr_state_name' class="w-full p-2 border border-gray-500 rounded-md">
                            <option value="">Pilih Nama Negeri</option>
                            
                            @foreach ($state as $hr)
                                <option value="{{ $hr->hr_state_name }}">
                                    {{ $hr->hr_state_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('hr_state_name')
                            <span class="error text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-base font-medium text-gray-600">Nama Cawangan Dalam Sistem HR:</label>
                        <input type="text" name="hr_branch_name" wire:model='hr_branch_name' class="w-full p-2 border border-gray-500 rounded-md" />
                        @error('hr_branch_name')
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