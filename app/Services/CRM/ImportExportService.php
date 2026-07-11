<?php

namespace App\Services\CRM;

use App\Models\Client;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportExportService
{
    /**
     * Get the columns for a specific entity.
     */
    public function getEntityColumns(string $entity): array
    {
        return match ($entity) {
            'clients' => [
                'company_name', 'client_code', 'email', 'phone', 'website', 
                'industry', 'gst_number', 'address', 'city', 'state', 
                'country', 'postal_code', 'status'
            ],
            'leads' => [
                'lead_code', 'name', 'email', 'phone', 'company', 
                'source', 'status', 'priority', 'expected_value', 'remarks'
            ],
            'projects' => [
                'project_code', 'name', 'description', 'status', 'priority', 
                'start_date', 'due_date', 'budget', 'progress'
            ],
            default => []
        };
    }

    /**
     * Generate a CSV export for a specific entity.
     */
    public function exportCSV(string $entity)
    {
        $columns = $this->getEntityColumns($entity);
        if (empty($columns)) return null;

        $query = match ($entity) {
            'clients' => Client::query(),
            'leads' => Lead::query(),
            'projects' => Project::query(),
            default => null
        };

        if (!$query) return null;

        $callback = function() use ($query, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fputs($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, $columns);

            // Data
            $query->chunk(500, function($records) use ($file, $columns) {
                foreach ($records as $record) {
                    $row = [];
                    foreach ($columns as $column) {
                        $row[] = $record->{$column};
                    }
                    fputcsv($file, $row);
                }
            });

            fclose($file);
        };

        return $callback;
    }

    /**
     * Generate a template CSV for a specific entity.
     */
    public function generateTemplate(string $entity)
    {
        $columns = $this->getEntityColumns($entity);
        if (empty($columns)) return null;

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);
            
            // Add a sample row
            $sampleRow = array_map(function($col) {
                return 'Sample ' . str_replace('_', ' ', ucfirst($col));
            }, $columns);
            
            fputcsv($file, $sampleRow);
            fclose($file);
        };

        return $callback;
    }

    /**
     * Parse and import a CSV file.
     */
    public function importCSV(string $entity, string $filePath, int $userId): array
    {
        $columns = $this->getEntityColumns($entity);
        if (empty($columns)) {
            return ['success' => false, 'message' => 'Invalid entity.'];
        }

        $file = fopen($filePath, 'r');
        
        // Skip BOM if present
        $bom = fread($file, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($file);
        }

        $header = fgetcsv($file);
        
        if (!$header || count($header) !== count($columns)) {
            fclose($file);
            return ['success' => false, 'message' => 'Invalid CSV format. Please use the provided template.'];
        }

        $imported = 0;
        $failed = 0;
        
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file)) !== false) {
                // Skip empty rows
                if (count(array_filter($row)) === 0) continue;

                $data = array_combine($header, $row);
                $data['created_by'] = $userId;
                
                try {
                    match ($entity) {
                        'clients' => Client::create($data),
                        'leads' => Lead::create($data),
                        'projects' => Project::create($data),
                    };
                    $imported++;
                } catch (\Exception $e) {
                    Log::error("Import error on row: " . json_encode($data) . " - " . $e->getMessage());
                    $failed++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($file);
            return ['success' => false, 'message' => 'A fatal error occurred during import: ' . $e->getMessage()];
        }

        fclose($file);
        
        return [
            'success' => true,
            'imported' => $imported,
            'failed' => $failed,
            'message' => "Successfully imported {$imported} records. " . ($failed > 0 ? "{$failed} records failed (see logs)." : "")
        ];
    }
}
