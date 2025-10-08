<div class="ml-4">
  <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
    Penyelenggaraan Nota Sesi Pemantaun
  </h2>
  
  <div class="overflow-x-auto bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">

    <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg text-sm">
      {{-- Keep Action column tight --}}
      <colgroup>
        <col>
        <col>
        <col>
        <col class="w-44">
        <col class="w-40">
        <col class="w-px">
      </colgroup>
  
      <thead>
        <tr class="bg-gray-300 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Kod Nota Sesi</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Deskripsi Nota Sesi (Pengguna)</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Deskripsi Nota Sesi (Sistem)</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Dikemas Kini Pada</th>
          <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Dikemas Kini Oleh</th>
          <th class="py-2 px-2 text-left border border-gray-400 dark:border-gray-700 w-px whitespace-nowrap">Tindakan</th>
        </tr>
      </thead>
  
      <tbody class="text-sm text-gray-700 dark:text-gray-200">
        @forelse ($data as $item)
          <tr class="bg-gray-200 hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-800/70">
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->sesn_note_code }}</td>
            <td class="py-2 px-4 bg-white hover:bg-gray-100 border border-gray-300 dark:bg-gray-500 dark:border-gray-700">
              {{ $item->sesn_note_desc}}
            </td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->sesn_note_sys_desc }}</td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->updated_at }}</td>
            <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->updated_by }}</td>
  
            {{-- Action (tight, no wrap) --}}
            <td class="py-2 px-2 border border-gray-400 dark:border-gray-700 w-px whitespace-nowrap">
              <div class="flex items-center gap-2">
                <button type="button" wire:click="edit(@js($item->sesn_note_code))"
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50
                    overflow-y-auto px-4 sm:px-6 lg:px-8"
            wire:keydown.escape="$set('edits', false)"
            wire:click.self="$set('edits', false)">

            <div class="bg-white p-4 sm:p-6 rounded-lg w-full max-w-md sm:max-w-lg lg:max-w-xl shadow-xl my-8">
                <h3 class="text-lg sm:text-xl font-semibold mb-4">
                    Kemas Kini Deskripsi Nota Sesi
                </h3>

                <form wire:submit.prevent="update" method="POST" class="space-y-4">
                    {{-- Kod Nota Sesi --}}
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-gray-600">
                            Kod Nota Sesi:
                        </label>
                        <input type="text"
                              name="sesn_note_code"
                              wire:model="sesn_note_code"
                              class="w-full p-2 sm:p-3 bg-gray-100 border border-gray-500 rounded-md text-sm sm:text-base"
                              readonly />
                    </div>

                    {{-- Deskripsi Nota Sesi --}}
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-gray-600">
                            Deskripsi Nota Sesi:
                        </label>
                        <input type="text"
                              name="sesn_note_desc"
                              wire:model="sesn_note_desc"
                              class="w-full p-2 sm:p-3 border border-gray-500 rounded-md text-sm sm:text-base" />
                        @error('sesn_note_desc')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button type="button"
                                wire:click="close()"
                                class="w-full sm:w-auto py-2 px-4 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>

                        <button type="submit"
                                class="w-full sm:w-auto py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
  </div>
</div>