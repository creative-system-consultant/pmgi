<div>
    <div class="px-4 pt-6 2xl:px-0">
        <h3 class="mb-2 text-xl font-bold text-gray-900 ">Tugasan Anda Sebagai</h3>

        {{-- ringkasan --}}
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <!-- Card header -->
            <div class="items-center lg:flex">
                <div class="mb-4 lg:mb-0">
                    <h3 class="mb-2 text-lg font-bold text-gray-900 ">Urusetia Jawatankuasa Timbang Tara (JTT)</h3>
                </div>
            </div>
            <div class="mt-6">
                <div class="flex-col justify-center w-full">
                    <div class="flex justify-center">
                        <img src="{{ asset('image/illustrations/meeting.svg') }}" class="w-96" alt="astronaut image">
                    </div>
                    <div class="flex justify-center gap-4">
                        <button class="inline-flex items-center p-5 text-sm font-bold text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 " wire:click="startMeeting(1)">
                            MULA MESYUARAT JTT
                            <x-icon name="arrow-circle-right" class="w-6 h-6 ms-2" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
