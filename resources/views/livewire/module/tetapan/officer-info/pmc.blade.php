<div>
    <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
        <div>
            <form wire:submit.prevent="save" class="flex items-center w-full">
                <div class="flex-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="default_size">Info Pegawai Pemudah Cara</label>
                    <input id="default_size" type="file" class="block w-full mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" wire:model="file">
                </div>
                <div class="ml-5">
                    <button wire:loading.attr="disabled" wire:loading.class="!cursor-wait" type="submit" class="inline-flex items-center justify-center px-4 py-2 text-base text-white transition-all duration-150 ease-in rounded outline-none group focus:ring-2 focus:ring-offset-2 hover:shadow-sm disabled:opacity-80 disabled:cursor-not-allowed gap-x-2  @if(!$file) ring-gray-500 bg-gray-500 hover:bg-gray-600 hover:ring-gray-600 @else ring-positive-500 bg-positive-500 hover:bg-positive-600 hover:ring-positive-600 @endif" @if(!$file) disabled @endif>
                        <x-icon name="pencil" class="w-4 h-4 shrink-0" />
                        Kemaskini Fail
                    </button>
                </div>
            </form>
        </div>
        <div class="flex justify-center mt-4">
            @if ($savedFile)
            <img class="w-50% h-50%" src="{{ asset('storage/' . $savedFile->filename) }}" alt="Logo">
            @endif
        </div>
    </div>
</div>
