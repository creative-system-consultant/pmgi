<?php

namespace App\Console\Commands;

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
    protected $signature = 'import:hr-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import HR Officer data from a predefined TXT file inside a dynamic ZIP file into the PMGI_IMP_HR_OFFICER table and execute a stored procedure with the extracted date.';

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
        // Download the latest zip file from the FTP server
        $zipFilePath = $this->downloadLatestZipFileFromFtp();

        if (!$zipFilePath) {
            $this->error("No matching zip file found on the FTP server.");
            return 1;
        }

        $this->info("Downloaded zip file: $zipFilePath");

        // Extract the date portion from the file name (e.g., "20240930")
        $fileName = pathinfo($zipFilePath, PATHINFO_FILENAME);
        $dateString = $this->extractDateFromFileName($fileName);
        if (!$dateString) {
            $this->error("Failed to extract date from the file name: $fileName");
            return 1;
        }

        $this->info("Extracted date from file name: $dateString");

        // Define the extraction path
        $extractPath = storage_path('app/hr/temp_extracted');

        // Ensure the extraction path exists
        if (!File::exists($extractPath)) {
            File::makeDirectory($extractPath, 0755, true);
        }

        // Open the zip file using ZipArchive
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === true) {
            // Set the password for the zip file
            $zip->setPassword('CSC12345');

            // Extract the file to the extraction path
            if (!$zip->extractTo($extractPath)) {
                $this->error('Failed to extract the zip file. Check if the password is correct.');
                return 1;
            }

            // Close the zip file
            $zip->close();
            $this->info("Successfully extracted the zip file to: $extractPath");
        } else {
            $this->error("Failed to open the zip file at path $zipFilePath");
            return 1;
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
            $this->error('No .txt file found in the extracted contents.');
            return 1;
        }

        // Read the contents of the .txt file
        $contents = File::get($txtFile);

        // Truncate the table before inserting new data
        try {
            DB::table('PMGI_IMP_HR_OFFICER')->truncate();
            $this->info("Truncated the PMGI_IMP_HR_OFFICER table successfully.");
        } catch (\Exception $e) {
            $this->error("Failed to truncate table: " . $e->getMessage());
            return 1;
        }

        // Process the file contents and insert data into the table (same logic as before)
        $data = $this->processData($contents);

        // Insert data into Oracle DB table
        $insertedRows = 0;
        foreach ($data as $row) {
            try {
                DB::table('PMGI_IMP_HR_OFFICER')->insert($row);
                $insertedRows++;
            } catch (\Exception $e) {
                $this->error("Failed to insert row: " . json_encode($row) . " Error: " . $e->getMessage());
            }
        }

        $this->info("Successfully inserted $insertedRows rows into the PMGI_IMP_HR_OFFICER table.");

        // Run the stored procedure `UP_PMGI_IMP_HR_OFFICER`
        $this->runStoredProcedure($dateString);

        // Clean up extracted files
        File::deleteDirectory($extractPath);

        // **Delete the downloaded zip file**
        if (File::exists($zipFilePath)) {
            File::delete($zipFilePath);
            $this->info("Deleted the zip file: $zipFilePath");
        } else {
            $this->error("Failed to delete the zip file: $zipFilePath not found.");
        }

        return 0;
    }

    /**
     * Download the latest zip file from the FTP server using Laravel's Storage facade.
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
                // sent email to CSC
                $this->error("Stored procedure `UP_PMGI_IMP_HR_OFFICER` executed successfully with error, Check Email");
            }
        } catch (\Exception $e) {
            $this->error("Failed to execute stored procedure `UP_PMGI_IMP_HR_OFFICER`. Error: " . $e->getMessage());
        }
    }
}
