<?php

namespace App\Console\Commands;

use App\Notification\Whatsapp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDO;
use ZipArchive;

class ImportAllHrOfficerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:all-hr-data
                            {fileType=zip : The file type to process (zip or txt)}
                            {--protected : If set, indicates that the ZIP files are password protected}
                            {--password= : The password for the ZIP files if they are password protected}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import ALL HR Officer data files from FTP server (chronological order). Truncates table for each file, runs SP, then archives processed files LOCALLY. Compares FTP files with local archive to determine new files. STOPS on any failure to maintain chronological integrity.';

    /**
     * The phone numbers to which the WhatsApp notifications will be sent.
     *
     * @var array
     */
    protected $phoneNumbers = [
        '60189445211', // Replace with actual phone number 1
        '60122057891', // Replace with actual phone number 2
    ];

    /**
     * The column names that should match between the TXT file and the database table.
     *
     * @var array
     */
    protected $tableColumns = [
        'NEGERI',
        'CAWANGAN',
        'NO_PEKERJA',
        'NAMA',
        'NO_KP',
        'JAWATAN',
        'GELARAN',
        'JAWATAN_BULAN_SEBELUM',
        'TEMPOH_PENEMPATAN_SEMASA',
        'TEMPOH_KHIDMAT',
        'CAWANGAN_SEBELUM',
        'TARIKH_KUATKUASA',
        'ALAMAT',
        'STATUS',
        'RESIGN_DATE',
        'NOTEL',
        'JANTINA',
        'TARIKH_CUTI_DARI',
        'TARIKH_CUTI_HINGGA',
        'KOD_CUTI',
        'GRED',
        'TARIKH_LANTIKAN',
        'TARAF_JAWATAN',
        'DATE_DISIPLIN',
        'DESCRIPTION_DISIPLIN',
        'DISIPLIN_REASON',
    ];

    /**
     * The columns in the table that are date fields.
     *
     * @var array
     */
    protected $dateColumns = [
        'TARIKH_KUATKUASA',
        'RESIGN_DATE',
        'TARIKH_CUTI_DARI',
        'TARIKH_CUTI_HINGGA',
        'TARIKH_LANTIKAN',
        'DATE_DISIPLIN',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the file type argument
        $fileType = strtolower($this->argument('fileType')); // "zip" or "txt"
        $isProtected = $this->option('protected'); // Boolean: true if the ZIP files are password-protected
        $zipPassword = $this->option('password') ?? env('ZIP_PASSWORD'); // Use command line password or fallback to .env
        
        $logMessage = '';
        $status = 'Success'; // Default status
        $totalFiles = 0;
        $successfulFiles = 0;
        $failedFiles = 0;

        // Initialize Whatsapp instance
        $whatsapp = new Whatsapp();

        $this->info("🚀 Starting to process ALL HR data files from FTP server...");
        $logMessage .= "Starting to process ALL HR data files from FTP server...\n";

        try {
            if ($fileType === 'zip') {
                // Handle all ZIP files
                $result = $this->processAllZipFiles($isProtected, $zipPassword);
            } elseif ($fileType === 'txt') {
                // Handle all TXT files
                $result = $this->processAllTxtFiles();
            } else {
                $errorMessage = "Invalid file type specified. Use 'zip' or 'txt'.";
                $this->error($errorMessage);
                $logMessage .= $errorMessage . "\n";
                $status = 'Error';
                $result = ['log' => $errorMessage, 'total' => 0, 'success' => 0, 'failed' => 0];
            }
            
            $logMessage .= $result['log'];
            $totalFiles = $result['total'];
            $successfulFiles = $result['success'];
            $failedFiles = $result['failed'];
            
            if ($failedFiles > 0 && $successfulFiles > 0) {
                $status = 'Partial Success';
            } elseif ($failedFiles > 0) {
                $status = 'Error';
            }
            
        } catch (\Exception $e) {
            $errorMessage = "An error occurred: " . $e->getMessage();
            $this->error($errorMessage);
            $logMessage .= $errorMessage . "\n";
            $status = 'Error';
        }

        // Summary
        $summaryMessage = "\n📊 SUMMARY:\n";
        $summaryMessage .= "Total Files Found: $totalFiles\n";
        $summaryMessage .= "Successfully Processed: $successfulFiles\n";
        $summaryMessage .= "Failed: $failedFiles\n";
        $summaryMessage .= "Final Status: $status\n";
        
        $this->info($summaryMessage);
        $logMessage .= $summaryMessage;

        // Send the final log message to WhatsApp with the determined status
        $this->sendToWhatsapp($logMessage, $status);

        return $failedFiles > 0 ? 1 : 0;
    }

    /**
     * Process all ZIP files from the FTP server.
     * STOPS on first failure to maintain chronological integrity.
     *
     * @param bool $isProtected
     * @param string|null $password
     * @return array
     */
    protected function processAllZipFiles($isProtected, $password = null)
    {
        $logMessage = '';
        $totalFiles = 0;
        $successfulFiles = 0;
        $failedFiles = 0;

        // Get all unprocessed ZIP files from FTP (excluding already archived ones)
        $files = $this->getUnprocessedZipFilesFromFtp();
        
        if (empty($files)) {
            $infoMessage = "No new zip files found on the FTP server. All files may have been processed and archived locally.";
            $this->info($infoMessage);
            return ['log' => $infoMessage . "\n", 'total' => 0, 'success' => 0, 'failed' => 0];
        }

        $totalFiles = count($files);
        $this->info("Found $totalFiles unprocessed ZIP files to process chronologically.");
        $logMessage .= "Found $totalFiles unprocessed ZIP files to process chronologically.\n";

        // Process each file - STOP on first failure
        foreach ($files as $index => $file) {
            $fileNumber = $index + 1;
            
            $this->info("📁 Processing file $fileNumber/$totalFiles: " . basename($file));
            $logMessage .= "\n--- Processing file $fileNumber/$totalFiles: " . basename($file) . " ---\n";
            
            try {
                $result = $this->processSingleZipFile($file, $isProtected, $password);
                $logMessage .= $result['log'];
                
                // Archive the file after successful processing using the local file path
                $this->archiveFile($result['localFile']);
                $logMessage .= "✅ File archived successfully: " . basename($file) . "\n";
                
                $successfulFiles++;
                $this->info("✅ Successfully processed and archived: " . basename($file));
            } catch (\Exception $e) {
                $errorMessage = "❌ STOPPING: Failed to process " . basename($file) . ": " . $e->getMessage();
                $this->error($errorMessage);
                $this->error("🛑 Process stopped to maintain chronological integrity. Fix the issue and rerun.");
                $logMessage .= $errorMessage . "\n";
                $logMessage .= "🛑 Process stopped to maintain chronological integrity.\n";
                $failedFiles++;
                
                // STOP processing on first failure
                break;
            }
        }

        return [
            'log' => $logMessage,
            'total' => $totalFiles,
            'success' => $successfulFiles,
            'failed' => $failedFiles
        ];
    }

    /**
     * Process all TXT files from the FTP server.
     * STOPS on first failure to maintain chronological integrity.
     *
     * @return array
     */
    protected function processAllTxtFiles()
    {
        $logMessage = '';
        $totalFiles = 0;
        $successfulFiles = 0;
        $failedFiles = 0;

        // Get all unprocessed TXT files from FTP (excluding already archived ones)
        $files = $this->getUnprocessedTxtFilesFromFtp();
        
        if (empty($files)) {
            $infoMessage = "No new txt files found on the FTP server. All files may have been processed and archived locally.";
            $this->info($infoMessage);
            return ['log' => $infoMessage . "\n", 'total' => 0, 'success' => 0, 'failed' => 0];
        }

        $totalFiles = count($files);
        $this->info("Found $totalFiles unprocessed TXT files to process chronologically.");
        $logMessage .= "Found $totalFiles unprocessed TXT files to process chronologically.\n";

        // Process each file - STOP on first failure
        foreach ($files as $index => $file) {
            $fileNumber = $index + 1;
            
            $this->info("📄 Processing file $fileNumber/$totalFiles: " . basename($file));
            $logMessage .= "\n--- Processing file $fileNumber/$totalFiles: " . basename($file) . " ---\n";
            
            try {
                $result = $this->processSingleTxtFile($file);
                $logMessage .= $result['log'];
                
                // Archive the file after successful processing using the local file path
                $this->archiveFile($result['localFile']);
                $logMessage .= "✅ File archived successfully: " . basename($file) . "\n";
                
                $successfulFiles++;
                $this->info("✅ Successfully processed and archived: " . basename($file));
            } catch (\Exception $e) {
                $errorMessage = "❌ STOPPING: Failed to process " . basename($file) . ": " . $e->getMessage();
                $this->error($errorMessage);
                $this->error("🛑 Process stopped to maintain chronological integrity. Fix the issue and rerun.");
                $logMessage .= $errorMessage . "\n";
                $logMessage .= "🛑 Process stopped to maintain chronological integrity.\n";
                $failedFiles++;
                
                // STOP processing on first failure
                break;
            }
        }

        return [
            'log' => $logMessage,
            'total' => $totalFiles,
            'success' => $successfulFiles,
            'failed' => $failedFiles
        ];
    }

    /**
     * Get all unprocessed ZIP files from FTP server, sorted by date (oldest first).
     * Compares FTP files with local archived files to determine what needs processing.
     *
     * @return array
     */
    protected function getUnprocessedZipFilesFromFtp()
    {
        // List all files in the FTPS main directory
        $files = Storage::disk('ftps')->files();

        // Filter matching zip files in main directory
        $ftpFiles = array_filter($files, function ($file) {
            return preg_match('/Masterlist Wargakerja \d{8}\.zip$/', basename($file));
        });

        if (empty($ftpFiles)) {
            return [];
        }

        // Get list of already archived files locally
        $archivedFiles = $this->getLocalArchivedFiles('zip');
        
        // Filter out files that are already archived locally
        $unprocessedFiles = array_filter($ftpFiles, function ($file) use ($archivedFiles) {
            $fileName = basename($file);
            return !in_array($fileName, $archivedFiles);
        });

        if (empty($unprocessedFiles)) {
            return [];
        }

        // Sort by filename date (oldest first) - more reliable than modification time
        usort($unprocessedFiles, function ($a, $b) {
            $dateA = $this->extractDateFromFileName(pathinfo($a, PATHINFO_FILENAME));
            $dateB = $this->extractDateFromFileName(pathinfo($b, PATHINFO_FILENAME));
            return strcmp($dateA, $dateB); // Ascending order (oldest first)
        });

        return $unprocessedFiles;
    }

    /**
     * Get all unprocessed TXT files from FTP server, sorted by date (oldest first).
     * Compares FTP files with local archived files to determine what needs processing.
     *
     * @return array
     */
    protected function getUnprocessedTxtFilesFromFtp()
    {
        // List all files in the FTPS main directory
        $files = Storage::disk('ftps')->files();

        // Filter matching txt files in main directory
        $ftpFiles = array_filter($files, function ($file) {
            return preg_match('/Masterlist Wargakerja \d{8}\.txt$/', basename($file));
        });

        if (empty($ftpFiles)) {
            return [];
        }

        // Get list of already archived files locally
        $archivedFiles = $this->getLocalArchivedFiles('txt');
        
        // Filter out files that are already archived locally
        $unprocessedFiles = array_filter($ftpFiles, function ($file) use ($archivedFiles) {
            $fileName = basename($file);
            return !in_array($fileName, $archivedFiles);
        });

        if (empty($unprocessedFiles)) {
            return [];
        }

        // Sort by filename date (oldest first)
        usort($unprocessedFiles, function ($a, $b) {
            $dateA = $this->extractDateFromFileName(pathinfo($a, PATHINFO_FILENAME));
            $dateB = $this->extractDateFromFileName(pathinfo($b, PATHINFO_FILENAME));
            return strcmp($dateA, $dateB); // Ascending order (oldest first)
        });

        return $unprocessedFiles;
    }

    /**
     * Process a single ZIP file.
     *
     * @param string $ftpFilePath
     * @param bool $isProtected
     * @param string|null $password
     * @return array ['log' => string, 'localFile' => string]
     */
    protected function processSingleZipFile($ftpFilePath, $isProtected, $password = null)
    {
        $logMessage = '';

        // Extract the date from filename
        $fileName = pathinfo($ftpFilePath, PATHINFO_FILENAME);
        $dateString = $this->extractDateFromFileName($fileName);
        if (!$dateString) {
            throw new \Exception("Failed to extract date from the file name: $fileName");
        }

        // Download the file
        $localFilePath = $this->downloadFileFromFtp($ftpFilePath);
        $logMessage .= "Downloaded zip file: $localFilePath\n";

        // Define the extraction path
        $extractPath = storage_path('app/hr/temp_extracted_' . $dateString);

        // Ensure the extraction path exists
        if (!File::exists($extractPath)) {
            File::makeDirectory($extractPath, 0755, true);
        }

        // Open and extract the zip file
        $zip = new ZipArchive;
        if ($zip->open($localFilePath) === true) {
            // If the ZIP is protected, set the password
            if ($isProtected) {
                if ($password) {
                    $zip->setPassword($password);
                } else {
                    throw new \Exception("Password protected ZIP file detected, but no password provided.");
                }
            }

            // Extract the file to the extraction path
            if (!$zip->extractTo($extractPath)) {
                throw new \Exception('Failed to extract the zip file. Check if the password is correct.');
            }

            // Close the zip file
            $zip->close();
            $logMessage .= "Successfully extracted the zip file to: $extractPath\n";
        } else {
            throw new \Exception("Failed to open the zip file at path $localFilePath");
        }

        // Locate the extracted .txt file
        $extractedFiles = File::files($extractPath);
        $txtFile = null;

        foreach ($extractedFiles as $file) {
            if ($file->getExtension() === 'txt') {
                $txtFile = $file->getPathname();
                break;
            }
        }

        if (!$txtFile) {
            throw new \Exception('No .txt file found in the extracted contents.');
        }

        // Process the data (ALWAYS truncate - data is intermediary)
        $logMessage .= $this->processAndInsertData($txtFile, $dateString, true);

        // Clean up extraction directory but keep the downloaded file for archiving
        File::deleteDirectory($extractPath);
        $logMessage .= "Cleaned up temporary extraction directory.\n";

        return ['log' => $logMessage, 'localFile' => $localFilePath];
    }

    /**
     * Process a single TXT file.
     *
     * @param string $ftpFilePath
     * @return array ['log' => string, 'localFile' => string]
     */
    protected function processSingleTxtFile($ftpFilePath)
    {
        $logMessage = '';

        // Extract the date from filename
        $fileName = pathinfo($ftpFilePath, PATHINFO_FILENAME);
        $dateString = $this->extractDateFromFileName($fileName);
        if (!$dateString) {
            throw new \Exception("Failed to extract date from the file name: $fileName");
        }

        // Download the file
        $localFilePath = $this->downloadFileFromFtp($ftpFilePath);
        $logMessage .= "Downloaded txt file: $localFilePath\n";

        // Process the data (ALWAYS truncate - data is intermediary)
        $logMessage .= $this->processAndInsertData($localFilePath, $dateString, true);

        // Keep the downloaded file for archiving (don't delete it here)
        $logMessage .= "File ready for archiving.\n";

        return ['log' => $logMessage, 'localFile' => $localFilePath];
    }

    /**
     * Download a file from FTP server to local storage.
     *
     * @param string $ftpFilePath
     * @return string
     */
    protected function downloadFileFromFtp($ftpFilePath)
    {
        // Ensure the hr directory exists
        $hrDir = storage_path('app/hr');
        if (!File::exists($hrDir)) {
            File::makeDirectory($hrDir, 0755, true);
        }
        
        // Define local path to save the downloaded file
        $localFilePath = $hrDir . '/' . basename($ftpFilePath);

        // Download the file from the FTPS server to the local path
        $fileContents = Storage::disk('ftps')->get($ftpFilePath);
        if ($fileContents === false) {
            throw new \Exception("Failed to download the file: $ftpFilePath");
        }

        // Save the downloaded contents to the local path
        File::put($localFilePath, $fileContents);

        return $localFilePath;
    }

    /**
     * Process the data from the TXT file and insert it into the database.
     * 
     * @param string $filePath
     * @param string $dateString
     * @param bool $truncateTable
     * @return string
     */
    protected function processAndInsertData($filePath, $dateString, $truncateTable = true)
    {
        $logMessage = '';

        // Read the contents of the .txt file
        $contents = File::get($filePath);

        // Always truncate table (data is intermediary and moved by SP)
        if ($truncateTable) {
            try {
                DB::table('PMGI_IMP_HR_OFFICER')->truncate();
                $this->info("Truncated the PMGI_IMP_HR_OFFICER table (data is intermediary).");
                $logMessage .= "Truncated the PMGI_IMP_HR_OFFICER table (data is intermediary).\n";
            } catch (\Exception $e) {
                throw new \Exception("Failed to truncate table: " . $e->getMessage());
            }
        }

        // Process the file contents and insert data into the table
        $data = $this->processData($contents);

        // Insert data into database table
        $insertedRows = 0;
        foreach ($data as $row) {
            try {
                DB::table('PMGI_IMP_HR_OFFICER')->insert($row);
                $insertedRows++;
            } catch (\Exception $e) {
                $errorMessage = "Failed to insert row: " . json_encode($row) . " Error: " . $e->getMessage();
                $this->error($errorMessage);
                $logMessage .= $errorMessage . "\n";
            }
        }

        $this->info("Successfully inserted $insertedRows rows into the PMGI_IMP_HR_OFFICER table.");
        $logMessage .= "Successfully inserted $insertedRows rows into the PMGI_IMP_HR_OFFICER table.\n";

        // Run the stored procedure
        $logMessage .= $this->runStoredProcedure($dateString);

        return $logMessage;
    }

    /**
     * Process the contents of the TXT file and format the data for database insertion.
     *
     * @param string $contents
     * @return array
     */
    protected function processData($contents)
    {
        // Split the contents into rows using newline
        $lines = explode("\n", $contents);

        // Extract headers from the first line and trim spaces
        $headers = array_map('trim', explode("\t", trim($lines[0])));

        // Validate that the headers match the table columns
        if (array_diff($headers, $this->tableColumns)) {
            throw new \Exception("The headers in the file do not match the expected table columns.");
        }

        $data = [];

        // Process each row
        foreach (array_slice($lines, 1) as $line) {
            if (trim($line) === '') {
                continue;
            }

            $row = explode("\t", trim($line));
            while (count($row) < count($headers)) {
                $row[] = ""; // Append empty strings for missing columns
            }

            if (count($row) > count($headers)) {
                $row = array_slice($row, 0, count($headers));
            }

            $rowData = array_combine($headers, $row);

            // Format date columns
            foreach ($this->dateColumns as $dateColumn) {
                if (!empty($rowData[$dateColumn])) {
                    try {
                        $rowData[$dateColumn] = Carbon::createFromFormat('d/m/Y', $rowData[$dateColumn])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $this->error("Failed to parse date: " . $rowData[$dateColumn] . " for column: $dateColumn. Setting as null.");
                        $rowData[$dateColumn] = null;
                    }
                } else {
                    $rowData[$dateColumn] = null;
                }
            }

            $filteredData = array_intersect_key($rowData, array_flip($this->tableColumns));
            $data[] = $filteredData;
        }

        return $data;
    }

    /**
     * Extract the date from the file name.
     *
     * @param string $fileName
     * @return string|null
     */
    protected function extractDateFromFileName($fileName)
    {
        if (preg_match('/(\d{8})$/', $fileName, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get list of already archived files locally.
     *
     * @param string $fileType
     * @return array
     */
    protected function getLocalArchivedFiles($fileType)
    {
        $archiveDir = storage_path('app/public/archived-hr-files');
        
        if (!File::exists($archiveDir)) {
            return [];
        }
        
        $files = File::files($archiveDir);
        $archivedFiles = [];
        
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            // Match files with the expected pattern and file type
            if (preg_match('/Masterlist Wargakerja \d{8}\.' . $fileType . '$/', $fileName)) {
                $archivedFiles[] = $fileName;
            }
        }
        
        return $archivedFiles;
    }

    /**
     * Archive a processed file locally after successful processing.
     *
     * @param string $localFilePath The path to the downloaded file
     * @return void
     */
    protected function archiveFile($localFilePath)
    {
        $fileName = basename($localFilePath);
        
        try {
            // Ensure local archive directory exists
            $archiveDir = storage_path('app/archived-hr-files');
            if (!File::exists($archiveDir)) {
                File::makeDirectory($archiveDir, 0755, true);
            }
            
            // Move file to local archive directory
            $archivedPath = $archiveDir . '/' . $fileName;
            File::move($localFilePath, $archivedPath);
            
            $this->info("📁 Archived file locally: $fileName → archived-hr-files/$fileName");
        } catch (\Exception $e) {
            throw new \Exception("Failed to archive file locally $fileName: " . $e->getMessage());
        }
    }

    /**
     * Run the stored procedure `UP_PMGI_IMP_HR_OFFICER`.
     *
     * @param string $dateString
     * @return string
     */
    protected function runStoredProcedure($dateString)
    {
        $logMessage = '';
        
        try {
            $output = '';

            $procedureName = 'UP_PMGI_IMP_HR_OFFICER';

            $bindings = [
                $dateString,
                'SYSTEM',
                'pi_ret_msg' => [
                    'value' => &$output,
                    'type' => PDO::PARAM_STR,
                    'length' => 4000,
                ],
            ];

            // Execute the procedure
            DB::executeProcedure($procedureName, $bindings);

            if (substr($output, 0, 1) == '0') {
                $this->info("Stored procedure `UP_PMGI_IMP_HR_OFFICER` executed successfully with date: $dateString and user ID: SYSTEM.");
                $logMessage .= "Stored procedure executed successfully for date: $dateString. Output: $output\n";
            } else {
                $this->error("Stored procedure `UP_PMGI_IMP_HR_OFFICER` executed with errors for date: $dateString. Output: $output");
                $logMessage .= "Stored procedure executed with errors for date: $dateString. Output: $output\n";
            }
        } catch (\Exception $e) {
            $errorMessage = "Failed to execute stored procedure `UP_PMGI_IMP_HR_OFFICER` for date: $dateString. Error: " . $e->getMessage();
            $this->error($errorMessage);
            $logMessage .= $errorMessage . "\n";
        }
        
        return $logMessage;
    }

    /**
     * Send message to multiple WhatsApp contacts.
     *
     * @param string $message
     * @param string $status
     * @return void
     */
    protected function sendToWhatsapp($message, $status = 'Success')
    {
        // Add status to the message with dynamic formatting based on status
        $formattedMessage = "*System*: PMGI\n" .
            "*Module*: Import ALL HR Data\n" .
            "*Status: " . ($status === 'Success' ? "Success*" : ($status === 'Partial Success' ? "Partial Success*" : "Error*")) . "\n\n" .
            $message;

        $whatsapp = new Whatsapp();

        // Send the formatted message to each phone number
        foreach ($this->phoneNumbers as $phoneNumber) {
            $whatsapp->send($phoneNumber, $formattedMessage);
        }
    }
} 