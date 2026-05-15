<div class="min-h-screen flex">
    {{-- Sidebar --}}
    <aside class="w-full shrink-0 border-r border-gray-200 bg-white/60">
        <div class="p-4 text-xl font-semibold">Menu Laporan</div>
        <nav class="px-2 space-y-1">
            {{-- Team dropdown --}}             
            <a href="{{ route('report.admin.fms_bank_officer') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Pegawai (Sumber : FMS)
            </a>

            <a href="{{ route('report.admin.fms_hrd_officer') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Pegawai (Sumber : HR)
            </a>

            <a href="{{ route('report.admin.senarai_pengawai_JKPi') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Senarai Pegawai Selesai JKPi Mengikut Peringkat
            </a>

            <a href="{{ route('report.admin.ringkasan_peringkat_PMGi') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Ringkasan Peringkat PMGi Mengikut Batch, Negeri & Cawangan
            </a>

            <a href="{{ route('report.admin.ringkasan_bil_PYD') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Ringkasan Bil PYD Mengikut Negeri & Cawangan
            </a>

            <a href="{{ route('report.admin.ringkasan_prestasi_PYD') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Ringkasan Prestasi PYD Mengikut Negeri & Cawangan
            </a>
        </nav>
    </aside>
</div>
