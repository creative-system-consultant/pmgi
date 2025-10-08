<div class="ml-4">
  <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
    Penyelenggaraan Deskripsi Pengurus
  </h2>

  <div class="overflow-x-auto bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
      
      {{-- Insert New Column --}}
      <div class="flex justify-end mb-4">
          <button type="button" wire:click="add()"
                  class="px-4 py-2 w-auto bg-gray-50 text-gray-600 border rounded-md flex items-center dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
              <span class="text-sm font-medium">Tambah Deskripsi Pengurus</span>
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
              <col>
            </colgroup>

          <thead>
            <tr class="bg-gray-300 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Seq No</th>
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Deskripsi Pengurus</th>
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Dicipta Pada</th>
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Dicipta Oleh</th>
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Dikemas Kini Pada</th>
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Dikemas Kini Oleh</th>
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Tindakan</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($data as $item)
              <tr class="bg-gray-200 hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-800/70">
                <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->seq_no }}</td>
                <td class="py-2 px-4 bg-white hover:bg-gray-100 border border-gray-300 dark:bg-gray-500 dark:border-gray-700">
                  {{ $item->mgr_desc }}
                </td>
                <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->created_at }}</td>
                <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->created_by }}</td>
                <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->updated_at }}</td>
                <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->updated_by }}</td>
                <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">
                  <div class="flex justify-center gap-2">
                    <button type="button" wire:click="edit({{ $item->seq_no }})"
                      class="px-3 py-1 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-md">
                      Edit
                    </button>
                    
                    <button type="button" wire:click="confirmDelete({{ $item->seq_no }})"
                      class="px-3 py-1 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-md">
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-4 px-3 text-center text-gray-600 dark:text-gray-300">
                  Tiada data dijumpai.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

      {{-- Pagination --}}
      <div class="mt-4">
        {{ $data->links() }}
      </div>

    {{-- Insert Modal --}}
    @if ($insert)
      <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 sm:px-6 lg:px-8"
          wire:keydown.escape="close" wire:click.self="close">
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg w-full max-w-md sm:max-w-lg lg:max-w-2xl shadow-xl overflow-y-auto max-h-[90vh]">
            <h3 class="text-base sm:text-lg font-semibold mb-4 text-center sm:text-left">
                Tambah Deskripsi Pengurus
            </h3>

            <form wire:submit.prevent="store" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Deskripsi Pengurus:
                    </label>
                    <input type="text" name="mgr_desc" wire:model='mgr_desc'
                          class="w-full p-2 sm:p-3 border rounded-md text-sm sm:text-base dark:bg-gray-700 dark:border-gray-600" />
                    @error('mgr_desc')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                    <button type="button" wire:click="close"
                            class="w-full sm:w-auto py-2 px-4 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 text-sm sm:text-base">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm sm:text-base">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
      </div>
    @endif

    {{-- Edit Modal --}}
    @if ($edits)
      <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 sm:px-6 lg:px-8"
          wire:keydown.escape="close" wire:click.self="close">
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg w-full max-w-md sm:max-w-lg lg:max-w-2xl shadow-xl overflow-y-auto max-h-[90vh]">
            <h3 class="text-base sm:text-lg font-semibold mb-4 text-center sm:text-left">
                Kemas Kini Deskripsi Pengurus
            </h3>

            <form wire:submit.prevent="update" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Deskripsi Pengurus:
                    </label>
                    <input type="text" name="mgr_desc" wire:model='mgr_desc'
                          class="w-full p-2 sm:p-3 border rounded-md text-sm sm:text-base dark:bg-gray-700 dark:border-gray-600" />
                    @error('mgr_desc')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                    <button type="button" wire:click="close"
                            class="w-full sm:w-auto py-2 px-4 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 text-sm sm:text-base">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm sm:text-base">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
      </div>
    @endif
  </div>
</div>