<div>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6">
            <div class="flex items-center justify-center lg:flex">
                <div class="flex flex-col items-center justify-center mb-4 text-center lg:mb-0">
                    <div class="block md:max-w-lg">
                        <img src="{{ asset('image/illustrations/welcome.svg') }}" alt="Welcome">
                    </div>
                    <h3 class="mt-8 mb-2 text-xl font-bold text-gray-900">Selamat Datang, Urusetia HQ</h3>
                    <p class="mt-4 text-lg text-gray-600">Sila pilih menu di bawah untuk mula tugasan anda.</p>

                    <div class="grid grid-cols-2 gap-6 mt-8">
                        <a href="{{ route('lantikan.penilai') }}" class="flex items-center justify-center p-4 bg-gray-100 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-200">
                            <span class="font-semibold text-gray-700">Lantikan PYM & PMC</span>
                        </a>
                        
                        <a href="{{ route('report.admin.fms_bank_officer') }}" class="flex items-center justify-center p-4 bg-gray-100 border border-gray-200 rounded-lg shadow-sm hover:bg-gray-200">
                            <span class="font-semibold text-gray-700">Lihat Laporan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
