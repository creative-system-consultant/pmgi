<div class="min-h-screen flex">
    {{-- Sidebar --}}
    <aside class="w-full shrink-0 border-r border-gray-200 bg-white/60">
        <div class="p-4 text-xl font-semibold">Menu Penyelenggaraan</div>
        <nav class="px-2 space-y-1">
            {{-- Team dropdown --}}
            <a href="{{ route('maintenance.admin.ref_mgr_desc') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Deskripsi Pengurus
            </a>

            <a href="{{ route('maintenance.admin.ref_pmgi_result') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Deskripsi Keputusan PMGi
            </a>   
            
            <a href="{{ route('maintenance.admin.monitor_session_notes') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Nota Sesi Pemantauan
            </a>          
            
            <a href="{{ route('maintenance.admin.ref_pmgi_level') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Peringkat PMGi
            </a>    
            
            <a href="{{ route('maintenance.admin.ref_pmgi_period') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Tempoh PMGi
            </a>           
                    
            <a href="{{ route('maintenance.admin.ref_eval_pctg') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Peratusan Penilaian
            </a>

            <a href="{{ route('maintenance.admin.map_state_hr2fms') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Pemetaan Negeri - HR ke FMS
            </a>                           
            
            <a href="{{ route('maintenance.admin.map_brances_hr2fms') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Pemetaan Cawangan - HR ke FMS
            </a>                                         
        </nav>
    </aside>
</div>
