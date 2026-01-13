<?php

namespace App\Providers;

use App\Models\SettUalPage;
use App\Models\SettUalRoleHasPage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use PDO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set the locale to Malay globally for Carbon
        Carbon::setLocale('ms_MY');
        
        // Add custom macro for executing stored procedures in MSSQL
        DB::macro('executeProcedure', function ($procedureName, $bindings = []) {
            $connection = DB::connection();
            $pdo = $connection->getPdo();
            
            // Build parameter placeholders
            $paramValues = [];
            $outputParams = [];
            
            foreach ($bindings as $key => $value) {
                if (is_array($value) && isset($value['value'])) {
                    // Handle output parameters
                    $outputParams[$key] = &$value['value'];
                } else {
                    // Handle input parameters
                    $paramValues[] = $value;
                }
            }
            
            // If we have output parameters, use a different approach
            if (!empty($outputParams)) {
                try {
                    // Use SET NOCOUNT ON to suppress row count messages that cause multiple result sets
                    $declares = ["SET NOCOUNT ON"];
                    $selects = [];
                    
                    foreach ($outputParams as $key => $value) {
                        $declares[] = "DECLARE @$key VARCHAR(4000)";
                        $selects[] = "@$key AS [$key]";
                    }
                    
                    // Build parameters for EXEC
                    $params = [];
                    for ($i = 0; $i < count($paramValues); $i++) {
                        $params[] = '?';
                    }
                    foreach ($outputParams as $key => $value) {
                        $params[] = "@$key OUTPUT";
                    }
                    
                    $sql = implode('; ', $declares) . '; ';
                    $sql .= "EXEC $procedureName " . implode(', ', $params) . '; ';
                    $sql .= "SELECT " . implode(', ', $selects);
                    
                    // Use prepared statement with proper parameter binding
                    $stmt = $pdo->prepare($sql);
                    
                    // Bind parameters with explicit types
                    for ($i = 0; $i < count($paramValues); $i++) {
                        $value = $paramValues[$i];
                        // Try to detect if it's a date and bind accordingly
                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                            // It's a date format, bind as string but let SQL Server handle conversion
                            $stmt->bindValue($i + 1, $value, PDO::PARAM_STR);
                        } else {
                            $stmt->bindValue($i + 1, $value, PDO::PARAM_STR);
                        }
                    }
                    
                    $stmt->execute();
                    
                    // Fetch the result (should be just one result set now due to SET NOCOUNT ON)
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    
                    // Update output parameter references
                    if (!empty($result)) {
                        foreach ($outputParams as $key => $value) {
                            $columnKey = $key;
                            if (isset($result[0]->$columnKey)) {
                                $outputParams[$key] = $result[0]->$columnKey;
                            }
                        }
                    }
                    
                    return $result;
                    
                } catch (\Exception $e) {
                    throw new \Exception("Error executing stored procedure: " . $e->getMessage());
                }
            } else {
                // Simple execution without output parameters
                $sql = "EXEC $procedureName " . implode(', ', array_fill(0, count($paramValues), '?'));
                
                // Use statement() instead of select() for procedures that don't return results
                try {
                    return $connection->statement($sql, $paramValues);
                } catch (\Exception $e) {
                    // If statement() fails, try select() as fallback
                    return $connection->select($sql, $paramValues);
                }
            }
        });
    }
}
