<div>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
            <div class="flex items-center justify-center lg:flex">
                <div class="flex flex-col items-center justify-center mb-4 text-center lg:mb-0">
                    <div class="block md:max-w-lg">
                        <img src="{{ asset('image/illustrations/welcome.svg') }}" alt="Welcome">
                    </div>
                    <h3 class="mt-8 mb-2 text-xl font-bold text-gray-900">Selamat Datang, Administrator</h3>
                    <p class="mt-4 text-lg text-gray-600">Pilih salah satu daripada menu di atas untuk mula menguruskan pengguna, laporan, atau tetapan sistem.</p>

                    <div class="grid grid-cols-3 gap-6 mt-8">
                        <a href="{{ route('tetapan.user-access') }}" class="flex items-center justify-center p-4 bg-gray-100 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-200">
                            <span class="font-semibold text-gray-700">Tetapan Pengguna</span>
                        </a>
                        <a href="{{ route('tetapan.peratusan-kriteria') }}" class="flex items-center justify-center p-4 bg-gray-100 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-200">
                            <span class="font-semibold text-gray-700">Tetapan Kriteria Sistem</span>
                        </a>
                        <a href="{{ route('tetapan.meeting-room') }}" class="flex items-center justify-center p-4 bg-gray-100 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-200">
                            <span class="font-semibold text-gray-700">Tetapan Bilik Meeting</span>
                        </a>
                    </div>

                    {{-- <div class="mt-12 text-center">
                        <p class="text-sm text-gray-500">Perlu bantuan? <a href="#" class="text-blue-500 hover:underline">Hubungi Sokongan</a></p>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
