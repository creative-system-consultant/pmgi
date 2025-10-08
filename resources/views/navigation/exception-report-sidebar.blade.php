<div class="min-h-screen flex">
    {{-- Sidebar --}}
    <aside class="w-full shrink-0 border-r border-gray-200 bg-white/60">
        <div class="p-4 text-xl font-semibold">Menu Laporan Pengecualian</div>
        <nav class="px-2 space-y-1">
            {{-- Team dropdown --}}
            <a href="{{ route('exceptionReport.admin.excl_branch') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Laporan Cawangan Dikecualikan
            </a>

            <a href="{{ route('exceptionReport.admin.excp_missing_branch') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Laporan Keciciran Cawangan
            </a>   
            
            <a href="{{ route('exceptionReport.admin.excp_missing_mgr') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Laporan Keciciran Pengurus
            </a>                                                  
        </nav>
    </aside>
</div>
