<div class="min-h-screen flex">
    {{-- Sidebar --}}
    <aside class="w-full shrink-0 border-r border-gray-200 bg-white/60">
        <div class="p-4 text-xl font-semibold">Menu Laporan Khas</div>
        <nav class="px-2 space-y-1">
            {{-- PENGECUALIAN Section with Label inside the Separator --}}
            <div class="flex items-center justify-center my-2">
                <div class="border-t border-gray-300 flex-1"></div>
                <div class="px-2 text-sm font-semibold"> PENGECUALIAN</div>
                <div class="border-t border-gray-300 flex-1"></div>
            </div>

            {{-- <a href="{{ route('exceptionReport.admin.excl_branch') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Laporan Cawangan Dikecualikan
            </a> --}}

            <a href="{{ route('exceptionReport.admin.excp_missing_branch') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Laporan Keciciran Cawangan
            </a>   
            
            <a href="{{ route('exceptionReport.admin.excp_missing_mgr') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Laporan Keciciran Pengurus
            </a>   

            {{-- AUDIT Section with Label inside the Separator --}}
            <div class="flex items-center justify-center my-2">
                <div class="border-t border-gray-300 flex-1"></div>
                <div class="px-2 text-sm font-semibold"> AUDIT</div>
                <div class="border-t border-gray-300 flex-1"></div>
            </div>

            <a href="{{ route('exceptionReport.admin.audit_mgr_desc') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Deskripsi Pengurus
            </a>

            <a href="{{ route('exceptionReport.admin.audit_pmgi_result') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Deskripsi Keputusan PMGi
            </a>   
            
            <a href="{{ route('exceptionReport.admin.audit_monitor_session_notes') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Nota Sesi Pemantauan
            </a>          
            
            <a href="{{ route('exceptionReport.admin.audit_pmgi_level') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Peringkat PMGi
            </a>    
            
            <a href="{{ route('exceptionReport.admin.audit_pmgi_period') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Tempoh PMGi
            </a>           
                    
            <a href="{{ route('exceptionReport.admin.audit_eval_pctg') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Peratusan Penilaian PMGi
            </a>

            <a href="{{ route('exceptionReport.admin.audit_map_state_hr2fms') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Pemetaan Negeri - HR ke FMS
            </a>                           
            
            <a href="{{ route('exceptionReport.admin.audit_map_branches_hr2fms') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Pemetaan Cawangan - HR ke FMS
            </a>                     

            <a href="{{ route('exceptionReport.admin.audit_jtt_roles') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Peranan JTT 
            </a>                       

            <div class="flex items-center justify-center my-2">
                <div class="border-t border-gray-300 flex-1"></div>
                <div class="px-2 text-sm font-semibold"> SISTEM</div>
                <div class="border-t border-gray-300 flex-1"></div>
            </div> 
            
            <a href="{{ route('exceptionReport.admin.sys_msg_log') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                Log Mesej Sistem
            </a>             
        </nav>
    </aside>
</div>
