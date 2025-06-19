<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ScheduleHrImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:hr-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily scheduled HR data import at midnight - imports latest file from FTP server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🕛 Starting scheduled HR data import...');
        
        try {
            // Call the HR import command with protected ZIP files
            $exitCode = Artisan::call('import:hr-data', [
                'fileType' => 'zip',
                '--protected' => true
            ]);
            
            if ($exitCode === 0) {
                $this->info('✅ Scheduled HR import completed successfully');
            } else {
                $this->error('❌ Scheduled HR import completed with errors');
            }
            
            // Display the output from the import command
            $this->line(Artisan::output());
            
            return $exitCode;
            
        } catch (\Exception $e) {
            $this->error('❌ Scheduled HR import failed: ' . $e->getMessage());
            return 1;
        }
    }
}
