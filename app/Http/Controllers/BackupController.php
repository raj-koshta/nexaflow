<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class BackupController extends Controller
{
    /**
     * Display a listing of the backups.
     */
    public function index()
    {
        $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
        $disk = Storage::disk($diskName);
        $name = config('backup.backup.name', 'Laravel');
        
        $files = $disk->files($name);

        $backups = [];
        foreach ($files as $file) {
            if (substr($file, -4) === '.zip' && $disk->exists($file)) {
                $backups[] = [
                    'file_path' => $file,
                    'file_name' => str_replace($name . '/', '', $file),
                    'file_size' => $this->humanFilesize($disk->size($file)),
                    'created_at' => \Carbon\Carbon::createFromTimestamp($disk->lastModified($file))->format('Y-m-d H:i:s'),
                    'created_at_human' => \Carbon\Carbon::createFromTimestamp($disk->lastModified($file))->diffForHumans(),
                ];
            }
        }

        // Sort backups by created_at descending
        usort($backups, function($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return view('settings.backups', compact('backups'));
    }

    /**
     * Trigger a new backup process.
     */
    public function store()
    {
        try {
            // Run files only because mysqldump is not in system PATH
            Artisan::call('backup:run', ['--only-files' => true]); 

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a specific backup.
     */
    public function download(Request $request)
    {
        $fileName = $request->query('file_name');
        $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
        $disk = Storage::disk($diskName);
        $name = config('backup.backup.name', 'Laravel');
        $file = $name . '/' . $fileName;

        if ($disk->exists($file)) {
            return $disk->download($file);
        }

        abort(404, 'Backup file not found.');
    }

    /**
     * Delete a specific backup.
     */
    public function destroy(Request $request)
    {
        $fileName = $request->input('file_name');
        $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
        $disk = Storage::disk($diskName);
        $name = config('backup.backup.name', 'Laravel');
        $file = $name . '/' . $fileName;

        if ($fileName && $disk->exists($file)) {
            $disk->delete($file);
            return response()->json([
                'success' => true,
                'message' => 'Backup deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Backup file not found.'
        ], 404);
    }

    /**
     * Convert bytes to human readable format.
     */
    private function humanFilesize($bytes, $decimals = 2)
    {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
}
