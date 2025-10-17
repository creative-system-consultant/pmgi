<div class="ml-4">
  <h2 class="mt-6 text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-300 pb-2 dark:text-gray-100">
    Penyelenggaraan Pengencualian Log Masuk Pengguna
  </h2>

  <div class="overflow-x-auto bg-white dark:bg-gray-900 p-6 rounded-lg shadow-inner mt-6 text-gray-900 dark:text-gray-100">
     <div class="flex justify-between mb-4">
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

          {{-- Insert New Column Button --}}
          <button type="button" wire:click="add()"
                  class="px-4 py-2 w-auto bg-gray-50 text-gray-600 border rounded-md flex items-center dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
              <span class="text-sm font-medium">Tambah Pengecualian Log Masuk Pengguna</span>
          </button>
      </div>

      <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg text-sm">
            {{-- Keep Action column tight --}}
            <colgroup>
              <col>
              <col>
              <col class="w-px">
            </colgroup>

          <thead>
            <tr class="bg-gray-300 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-300">
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">ID Pengguna</th>
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Nama Pengguna</th>
              <th class="py-2 px-4 text-left border border-gray-400 dark:border-gray-700">Tindakan</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($data as $item)
              <tr class="bg-gray-200 hover:bg-gray-100 dark:bg-gray-600 dark:hover:bg-gray-800/70">
                <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">{{ $item->userid }}</td>
                <td class="py-2 px-4 bg-white hover:bg-gray-100 border border-gray-300 dark:bg-gray-500 dark:border-gray-700">
                  {{ $item->username }}
                </td>

                <td class="py-2 px-4 border border-gray-400 dark:border-gray-700">
                  <div class="flex justify-center gap-2">
                    <button type="button" wire:click="edit(@js($item->userid))"
                      class="px-3 py-1 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-md">
                      Edit
                    </button>
                    
                    <button type="button" wire:click="confirmDelete(@js($item->userid))"
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50 overflow-y-auto px-4 sm:px-6 lg:px-8"
             wire:keydown.escape="$set('insert', true)" wire:click.self="$set('insert', true)">

            <div class="bg-white p-4 sm:p-6 rounded-lg w-full max-w-md sm:max-w-lg lg:max-w-2xl shadow-xl my-8">
                <h3 class="text-lg sm:text-xl font-semibold mb-4">
                    Tambah Pengencualian Log Masuk Pengguna
                </h3>

                <form wire:submit.prevent="store" method="POST" class="space-y-4">        
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-gray-600">
                            ID Pengguna:
                        </label>
                        <input type="text" name="userid" wire:model="userid"
                            class="w-full p-2 sm:p-3 border border-gray-500 rounded-md text-sm sm:text-base" />
                        @error('userid')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm sm:text-base font-medium text-gray-600">
                            Nama Pengguna:
                        </label>
                        <input type="text" name="usename" wire:model="username"
                            class="w-full p-2 sm:p-3 border border-gray-500 rounded-md text-sm sm:text-base" />
                        @error('username')
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

    {{-- Edit Modal --}}
    @if ($edits)
        {{-- Modal Background --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50
                    px-4 sm:px-6 lg:px-8"
            wire:keydown.escape="$set('edits', true)"
            wire:click.self="$set('edits', true)">

            {{-- Modal Content --}}
            <div class="bg-white p-4 sm:p-6 rounded-lg w-full max-w-md sm:max-w-lg lg:max-w-2xl shadow-xl">
                <h3 class="text-lg sm:text-xl font-semibold mb-4 text-center sm:text-left">
                    Kemaskini Pengencualian Log Masuk Pengguna
                </h3>

                <form wire:submit.prevent="update" method="POST" class="space-y-4">
                    {{-- Field 1 --}}
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-gray-600">
                            ID Pengguna:
                        </label>
                        <input type="text"
                              name="userid"
                              wire:model="userid"
                              class="w-full p-2 sm:p-3 bg-gray-100 border border-gray-500 rounded-md text-sm sm:text-base"
                              readonly />
                    </div>

                    {{-- Field 2 --}}
                    <div>
                        <label class="block text-sm sm:text-base font-medium text-gray-600">
                            Nama Pengguna:
                        </label>
                        <input type="text"
                              name="username"
                              wire:model="username"
                              class="w-full p-2 sm:p-3 border border-gray-500 rounded-md text-sm sm:text-base" />
                        @error('username')
                            <span class="error text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                        <button type="button"
                                wire:click="close()"
                                class="w-full sm:w-auto py-2 px-4 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 text-sm sm:text-base">
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