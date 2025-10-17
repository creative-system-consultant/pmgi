<div class="min-h-screen flex">
    {{-- Sidebar --}}
    <aside class="w-full shrink-0 border-r border-gray-200 bg-white/60">
        <div class="p-4 text-xl font-semibold">Menu Laporan</div>
        <nav class="px-2 space-y-1">
            {{-- Team dropdown --}}
            <a href="{{ route('report.admin.sys_msg_log') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Log Mesej Sistem
            </a>              

            <a href="{{ route('report.admin.fms_bank_officer') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Pengawai (Sumber : FMS)
            </a>              

            <a href="{{ route('report.admin.fms_hrd_officer') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Pegawai (Sumber : HR)
            </a>              
        </nav>
    </aside>
</div>
