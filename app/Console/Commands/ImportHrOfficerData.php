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
    protected $description = 'Import HR Officer data from a predefined TXT file inside a dynamic ZIP file or directly from a TXT file into the PMGI_IMP_HR_OFFICER table and execute a stored procedure with the extracted date.';

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
        $zipPassword = $this->option('password'); // Optional: the password for the ZIP file if protected
        $logMessage = '';
        $status = 'Success'; // Default status

        // Initialize Whatsapp instance
        $whatsapp = new Whatsapp();

        try {
            if ($fileType === 'zip') {
                // Handle ZIP file logic and pass protection information
                $logMessage .= $this->processZipFile($isProtected, $zipPassword);
            } elseif ($fileType === 'txt') {
                // Handle TXT file logic
                $logMessage .= $this->processTxtFile();
            } else {
                $errorMessage = "Invalid file type specified. Use 'zip' or 'txt'.";
                $this->error($errorMessage);
                $logMessage .= $errorMessage . "\n";
                $status = 'Error';
            }
        } catch (\Exception $e) {
            $errorMessage = "An error occurred: " . $e->getMessage();
            $this->error($errorMessage);
            $logMessage .= $errorMessage . "\n";
            $status = 'Error';
        }

        // Send the final log message to WhatsApp with the determined status
        $this->sendToWhatsapp($logMessage, $status);

        return 0;
    }

    /**
     * Process the ZIP file as per the existing logic.
     *
     * @param bool $isProtected Indicates if the ZIP file is password-protected
     * @param string|null $password The password for the ZIP file if it is protected
     * @return string
     */
    protected function processZipFile($isProtected, $password = null)
    {
        $logMessage = '';

        // Download the latest zip file from the FTP server
        $zipFilePath = $this->downloadLatestZipFileFromFtp();

        if (!$zipFilePath) {
            $errorMessage = "No matching zip file found on the FTP server.";
            $this->error($errorMessage);
            return $errorMessage . "\n";
        }

        $this->info("Downloaded zip file: $zipFilePath");
        $logMessage .= "Downloaded zip file: $zipFilePath\n";

        // Extract the date portion from the file name (e.g., "20240930")
        $fileName = pathinfo($zipFilePath, PATHINFO_FILENAME);
        $dateString = $this->extractDateFromFileName($fileName);
        if (!$dateString) {
            $errorMessage = "Failed to extract date from the file name: $fileName";
            $this->error($errorMessage);
            return $errorMessage . "\n";
        }

        $this->info("Extracted date from file name: $dateString");
        $logMessage .= "Extracted date from file name: $dateString\n";

        // Define the extraction path
        $extractPath = storage_path('app/hr/temp_extracted');

        // Ensure the extraction path exists
        if (!File::exists($extractPath)) {
            File::makeDirectory($extractPath, 0755, true);
        }

        // Open the zip file using ZipArchive
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === true) {
            // If the ZIP is protected, set the password
            if ($isProtected) {
                if ($password) {
                    $zip->setPassword($password);
                } else {
                    $errorMessage = "Password protected ZIP file detected, but no password provided.";
                    $this->error($errorMessage);
                    return $errorMessage . "\n";
                }
            }

            // Extract the file to the extraction path
            if (!$zip->extractTo($extractPath)) {
                $errorMessage = 'Failed to extract the zip file. Check if the password is correct.';
                $this->error($errorMessage);
                return $errorMessage . "\n";
            }

            // Close the zip file
            $zip->close();
            $this->info("Successfully extracted the zip file to: $extractPath");
            $logMessage .= "Successfully extracted the zip file to: $extractPath\n";
        } else {
            $errorMessage = "Failed to open the zip file at path $zipFilePath";
            $this->error($errorMessage);
            return $errorMessage . "\n";
        }

        // Locate the extracted .txt file (assuming only one .txt file is present)
        $extractedFiles = File::files($extractPath);
        $txtFile = null;

        foreach ($extractedFiles as $file) {
            if ($file->getExtension() === 'txt') {
                $txtFile = $file->getPathname();
                break;
            }
        }

        if (!$txtFile) {
            $errorMessage = 'No .txt file found in the extracted contents.';
            $this->error($errorMessage);
            return $errorMessage . "\n";
        }

        $logMessage .= $this->processAndInsertData($txtFile, $dateString);

        // Clean up extracted files
        File::deleteDirectory($extractPath);

        // Delete the downloaded zip file
        if (File::exists($zipFilePath)) {
            File::delete($zipFilePath);
            $this->info("Deleted the zip file: $zipFilePath");
            $logMessage .= "Deleted the zip file: $zipFilePath\n";
        }

        return $logMessage;
    }

    /**
     * Process the TXT file directly.
     *
     * @return string
     */
    protected function processTxtFile()
    {
        $logMessage = '';

        // Find the latest TXT file on the FTP server
        $txtFilePath = $this->downloadLatestTxtFileFromFtp();

        if (!$txtFilePath) {
            $errorMessage = "No matching txt file found on the FTP server.";
            $this->error($errorMessage);
            return $errorMessage . "\n";
        }

        $this->info("Downloaded txt file: $txtFilePath");
        $logMessage .= "Downloaded txt file: $txtFilePath\n";

        // Extract the date portion from the file name (e.g., "20240930")
        $fileName = pathinfo($txtFilePath, PATHINFO_FILENAME);
        $dateString = $this->extractDateFromFileName($fileName);
        if (!$dateString) {
            $errorMessage = "Failed to extract date from the file name: $fileName";
            $this->error($errorMessage);
            return $errorMessage . "\n";
        }

        $this->info("Extracted date from file name: $dateString");
        $logMessage .= "Extracted date from file name: $dateString\n";

        // Process and insert data from the TXT file
        $logMessage .= $this->processAndInsertData($txtFilePath, $dateString);

        // Delete the downloaded txt file
        if (File::exists($txtFilePath)) {
            File::delete($txtFilePath);
            $this->info("Deleted the txt file: $txtFilePath");
            $logMessage .= "Deleted the txt file: $txtFilePath\n";
        }

        return $logMessage;
    }

    /**
     * Download the latest ZIP file from the FTP server.
     *
     * @return string|null
     */
    protected function downloadLatestZipFileFromFtp()
    {
        // List all files in the FTPS directory
        $files = Storage::disk('ftps')->files();

        // Filter and find the latest zip file that matches the pattern "Masterlist Wargakerja *.zip"
        $matchingFiles = array_filter($files, function ($file) {
            return preg_match('/Masterlist Wargakerja \d{8}\.zip$/', basename($file));
        });

        if (empty($matchingFiles)) {
            $this->error("No matching zip files found on the FTP server.");
            return null;
        }

        // Get the latest file based on modification time
        $latestFile = collect($matchingFiles)->sortByDesc(function ($file) {
            return Storage::disk('ftps')->lastModified($file);
        })->first();

        if (!$latestFile) {
            $this->error("Failed to identify the latest zip file on the FTP server.");
            return null;
        }

        $this->info("Latest zip file on the FTP server: $latestFile");

        // Define local path to save the downloaded file
        $localFilePath = storage_path('app/hr') . '/' . basename($latestFile);

        // Download the file from the FTPS server to the local path
        $fileContents = Storage::disk('ftps')->get($latestFile);
        if ($fileContents === false) {
            $this->error("Failed to download the file: $latestFile");
            return null;
        }

        // Save the downloaded contents to the local path
        File::put($localFilePath, $fileContents);

        $this->info("Successfully downloaded the file to: $localFilePath");

        return $localFilePath;
    }

    /**
     * Download the latest TXT file from the FTP server.
     *
     * @return string|null
     */
    protected function downloadLatestTxtFileFromFtp()
    {
        // List all files in the FTPS directory
        $files = Storage::disk('ftps')->files();

        // Filter and find the latest txt file that matches the pattern "Masterlist Wargakerja *.txt"
        $matchingFiles = array_filter($files, function ($file) {
            return preg_match('/Masterlist Wargakerja \d{8}\.txt$/', basename($file));
        });

        if (empty($matchingFiles)) {
            $this->error("No matching txt files found on the FTP server.");
            return null;
        }

        // Get the latest file based on modification time
        $latestFile = collect($matchingFiles)->sortByDesc(function ($file) {
            return Storage::disk('ftps')->lastModified($file);
        })->first();

        if (!$latestFile) {
            $this->error("Failed to identify the latest txt file on the FTP server.");
            return null;
        }

        $this->info("Latest txt file on the FTP server: $latestFile");

        // Define local path to save the downloaded file
        $localFilePath = storage_path('app/hr') . '/' . basename($latestFile);

        // Download the file from the FTPS server to the local path
        $fileContents = Storage::disk('ftps')->get($latestFile);
        if ($fileContents === false) {
            $this->error("Failed to download the file: $latestFile");
            return null;
        }

        // Save the downloaded contents to the local path
        File::put($localFilePath, $fileContents);

        $this->info("Successfully downloaded the file to: $localFilePath");

        return $localFilePath;
    }

    /**
     * Process the data from the TXT file and insert it into the database.
     *
     * @param string $filePath
     * @param string $dateString
     * @return string
     */
    protected function processAndInsertData($filePath, $dateString)
    {
        $logMessage = '';

        // Read the contents of the .txt file
        $contents = File::get($filePath);

        // Truncate the table before inserting new data
        try {
            DB::table('PMGI_IMP_HR_OFFICER')->truncate();
            $this->info("Truncated the PMGI_IMP_HR_OFFICER table successfully.");
            $logMessage .= "Truncated the PMGI_IMP_HR_OFFICER table successfully.\n";
        } catch (\Exception $e) {
            $errorMessage = "Failed to truncate table: " . $e->getMessage();
            $this->error($errorMessage);
            return $errorMessage . "\n";
        }

        // Process the file contents and insert data into the table
        $data = $this->processData($contents);

        // Insert data into Oracle DB table
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
        $this->runStoredProcedure($dateString);

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
     * @return void
     */
    protected function runStoredProcedure($dateString)
    {
        try {
            $output = '';

            $procedureName = 'UP_PMGI_IMP_HR_OFFICER';

            $bindings = [
                'pi_reportdt' => $dateString,
                'pi_operated_by' => 'SYSTEM',
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
            } else {
                $this->error("Stored procedure `UP_PMGI_IMP_HR_OFFICER` executed successfully with errors. Check email.");
            }
        } catch (\Exception $e) {
            $this->error("Failed to execute stored procedure `UP_PMGI_IMP_HR_OFFICER`. Error: " . $e->getMessage());
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
