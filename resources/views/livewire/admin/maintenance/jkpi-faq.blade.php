<div>
    <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">

        <h1 class="text-2xl font-bold mb-4">Soalan Lazim (FAQ) Untuk JKPi</h1>

        {{-- SUCCESS MESSAGE --}}
        @if (session()->has('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= UPLOAD FORM ================= --}}
        <form wire:submit.prevent="save" class="flex items-center w-full" enctype="multipart/form-data">

            <div class="flex-1">
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Upload Fail Soalan Lazim (PDF / DOC / DOCX)
                </label>

                <input
                    type="file"
                    wire:model="file"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                />

                {{-- VALIDATION ERROR --}}
                @error('file')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror

                {{-- UPLOADING INDICATOR --}}
                <div wire:loading wire:target="file" class="text-sm text-blue-600 mt-1">
                    Sedang memuat naik fail...
                </div>
            </div>

            <div class="ml-5">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="!cursor-wait opacity-70"
                    @if(!$file) disabled @endif
                    class="inline-flex items-center px-4 py-2 text-white rounded
                        @if(!$file)
                            bg-gray-500 cursor-not-allowed
                        @else
                            bg-green-600 hover:bg-green-700
                        @endif
                    "
                >
                    Kemaskini Fail
                </button>
            </div>
        </form>

        {{-- ================= CURRENT FILE ================= --}}
        <hr class="my-6">

        <h3 class="font-semibold mb-2">Fail Semasa:</h3>

        @if ($savedFile)
            <p class="mb-2 text-gray-800">
                <strong>{{ $savedFile->file_path }}</strong>
            </p>

            <a
                href="{{ asset('storage/' . $savedFile->file_path) }}"
                target="_blank"
                class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Download / View
            </a>
        @else
            <p class="text-gray-500">Tiada fail dimuat naik.</p>
        @endif

    </div>
</div>
