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

class ImportHrOfficerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:hr-data
                            {fileType=zip : The file type to process (zip or txt)}
                            {--protected : If set, indicates that the ZIP file is password protected}
                            {--password= : The password for the ZIP file if it is password protected}';

    // For a Password-Protected ZIP File:
    // php artisan import:hr-data zip --protected --password=CSC12345
    // For a Non-Password-Protected ZIP File:
    // php artisan import:hr-data zip
    // For a TXT File:
    // php artisan import:hr-data txt

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import LATEST HR Officer data file from FTP server. Checks against local archive to avoid reprocessing. Archives processed files LOCALLY in public folder.';

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
        $isProtected = $this->option('protected'); // Boolean: true if the ZIP file is password-protected
        $zipPassword = $this->option('password') ?? env('ZIP_PASSWORD'); // Use command line password or fallback to .env
        $logMessage = '';
        $status = 'Success'; // Default status

        // Initialize Whatsapp instance
        $whatsapp = new Whatsapp();

        $this->info("🚀 Starting to process LATEST HR data file from FTP server...");
        $logMessage .= "Starting to process LATEST HR data file from FTP server...\n";

        try {
            if ($fileType === 'zip') {
                // Handle ZIP file logic and pass protection information
                $result = $this->processZipFile($isProtected, $zipPassword);
            } elseif ($fileType === 'txt') {
                // Handle TXT file logic
                $result = $this->processTxtFile();
            } else {
                $errorMessage = "Invalid file type specified. Use 'zip' or 'txt'.";
                $this->error($errorMessage);
                $logMessage .= $errorMessage . "\n";
                $status = 'Error';
                $result = ['log' => $errorMessage, 'processed' => false];
            }
            
            $logMessage .= $result['log'];
            
            if (!$result['processed']) {
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
        $summaryMessage .= "Final Status: $status\n";
        
        $this->info($summaryMessage);
        $logMessage .= $summaryMessage;

        // Send the final log message to WhatsApp with the determined status
        $this->sendToWhatsapp($logMessage, $status);

        return $status === 'Error' ? 1 : 0;
    }

    /**
     * Process the latest ZIP file from FTP server.
     *
     * @param bool $isProtected Indicates if the ZIP file is password-protected
     * @param string|null $password The password for the ZIP file if it is protected
     * @return array ['log' => string, 'processed' => bool]
     */
    protected function processZipFile($isProtected, $password = null)
    {
        $logMessage = '';

        // Get the latest unprocessed zip file from FTP server
        $latestFile = $this->getLatestUnprocessedZipFile();

        if (!$latestFile) {
            $infoMessage = "No new zip files found on the FTP server. Latest file may already be processed and archived locally.";
            $this->info($infoMessage);
            return ['log' => $infoMessage . "\n", 'processed' => false];
        }

        $this->info("📁 Processing latest file: " . basename($latestFile));
        $logMessage .= "Processing latest file: " . basename($latestFile) . "\n";

        try {
            $result = $this->processSingleZipFile($latestFile, $isProtected, $password);
            $logMessage .= $result['log'];
            
            // Archive the file after successful processing using the local file path
            $this->archiveFile($result['localFile']);
            $logMessage .= "✅ File archived successfully: " . basename($latestFile) . "\n";
            
            $this->info("✅ Successfully processed and archived: " . basename($latestFile));
            return ['log' => $logMessage, 'processed' => true];
            
        } catch (\Exception $e) {
            $errorMessage = "❌ Failed to process " . basename($latestFile) . ": " . $e->getMessage();
            $this->error($errorMessage);
            $logMessage .= $errorMessage . "\n";
            return ['log' => $logMessage, 'processed' => false];
        }
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
     * Process the latest TXT file from FTP server.
     *
     * @return array ['log' => string, 'processed' => bool]
     */
    protected function processTxtFile()
    {
        $logMessage = '';

        // Get the latest unprocessed txt file from FTP server
        $latestFile = $this->getLatestUnprocessedTxtFile();

        if (!$latestFile) {
            $infoMessage = "No new txt files found on the FTP server. Latest file may already be processed and archived locally.";
            $this->info($infoMessage);
            return ['log' => $infoMessage . "\n", 'processed' => false];
        }

        $this->info("📄 Processing latest file: " . basename($latestFile));
        $logMessage .= "Processing latest file: " . basename($latestFile) . "\n";

        try {
            $result = $this->processSingleTxtFile($latestFile);
            $logMessage .= $result['log'];
            
            // Archive the file after successful processing using the local file path
            $this->archiveFile($result['localFile']);
            $logMessage .= "✅ File archived successfully: " . basename($latestFile) . "\n";
            
            $this->info("✅ Successfully processed and archived: " . basename($latestFile));
            return ['log' => $logMessage, 'processed' => true];
            
        } catch (\Exception $e) {
            $errorMessage = "❌ Failed to process " . basename($latestFile) . ": " . $e->getMessage();
            $this->error($errorMessage);
            $logMessage .= $errorMessage . "\n";
            return ['log' => $logMessage, 'processed' => false];
        }
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
     * Get the latest unprocessed ZIP file from FTP server.
     * Checks against local archive to avoid reprocessing.
     *
     * @return string|null
     */
    protected function getLatestUnprocessedZipFile()
    {
        // List all files in the FTPS main directory
        $files = Storage::disk('ftps')->files();

        // Filter matching zip files in main directory
        $ftpFiles = array_filter($files, function ($file) {
            return preg_match('/Masterlist Wargakerja \d{8}\.zip$/', basename($file));
        });

        if (empty($ftpFiles)) {
            return null;
        }

        // Get the latest file based on modification time
        $latestFile = collect($ftpFiles)->sortByDesc(function ($file) {
            return Storage::disk('ftps')->lastModified($file);
        })->first();

        if (!$latestFile) {
            return null;
        }

        // Check if this file is already archived locally
        $archivedFiles = $this->getLocalArchivedFiles('zip');
        $fileName = basename($latestFile);
        
        if (in_array($fileName, $archivedFiles)) {
            return null; // Latest file is already processed and archived
        }

        return $latestFile;
    }

    /**
     * Get the latest unprocessed TXT file from FTP server.
     * Checks against local archive to avoid reprocessing.
     *
     * @return string|null
     */
    protected function getLatestUnprocessedTxtFile()
    {
        // List all files in the FTPS main directory
        $files = Storage::disk('ftps')->files();

        // Filter matching txt files in main directory
        $ftpFiles = array_filter($files, function ($file) {
            return preg_match('/Masterlist Wargakerja \d{8}\.txt$/', basename($file));
        });

        if (empty($ftpFiles)) {
            return null;
        }

        // Get the latest file based on modification time
        $latestFile = collect($ftpFiles)->sortByDesc(function ($file) {
            return Storage::disk('ftps')->lastModified($file);
        })->first();

        if (!$latestFile) {
            return null;
        }

        // Check if this file is already archived locally
        $archivedFiles = $this->getLocalArchivedFiles('txt');
        $fileName = basename($latestFile);
        
        if (in_array($fileName, $archivedFiles)) {
            return null; // Latest file is already processed and archived
        }

        return $latestFile;
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
            $this->error("The headers in the file do not match the expected table columns.");
            return [];
        }

        $data = [];

        // Ensure each row has the correct number of columns, even if some columns are null
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
            // Ensure local archive directory exists (public folder)
            $archiveDir = storage_path('app/public/archived-hr-files');
            if (!File::exists($archiveDir)) {
                File::makeDirectory($archiveDir, 0755, true);
            }
            
            // Move file to local archive directory
            $archivedPath = $archiveDir . '/' . $fileName;
            File::move($localFilePath, $archivedPath);
            
            $this->info("📁 Archived file locally: $fileName → public/archived-hr-files/$fileName");
        } catch (\Exception $e) {
            throw new \Exception("Failed to archive file locally $fileName: " . $e->getMessage());
        }
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
            "*Module*: Import HR Data\n" .
            "*Status: " . ($status === 'Success' ? "Success*" : "Error*") . "\n\n" .
            $message;

        $whatsapp = new Whatsapp();

        // Send the formatted message to each phone number
        foreach ($this->phoneNumbers as $phoneNumber) {
            $whatsapp->send($phoneNumber, $formattedMessage);
        }
    }
}
