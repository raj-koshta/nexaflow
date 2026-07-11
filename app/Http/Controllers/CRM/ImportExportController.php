<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Services\CRM\ImportExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ImportExportController extends Controller
{
    protected $importExportService;

    public function __construct(ImportExportService $importExportService)
    {
        $this->importExportService = $importExportService;
    }

    /**
     * Display the import/export dashboard.
     */
    public function index()
    {
        return view('import_export.index');
    }

    /**
     * Download a CSV template for the given entity.
     */
    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'entity' => 'required|in:clients,leads,projects'
        ]);

        $entity = $request->entity;
        $callback = $this->importExportService->generateTemplate($entity);

        if (!$callback) {
            return back()->with('error', 'Invalid entity selected.');
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $entity . '_template.csv"',
        ];

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export data to CSV.
     */
    public function export(Request $request)
    {
        $request->validate([
            'entity' => 'required|in:clients,leads,projects'
        ]);

        $entity = $request->entity;
        $callback = $this->importExportService->exportCSV($entity);

        if (!$callback) {
            return back()->with('error', 'Unable to generate export.');
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $entity . '_export_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Import data from CSV.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entity' => 'required|in:clients,leads,projects',
            'file' => 'required|file|mimes:csv,txt|max:10240', // max 10MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Invalid input or file format. Please upload a valid CSV file.');
        }

        $entity = $request->entity;
        $file = $request->file('file');
        
        $result = $this->importExportService->importCSV($entity, $file->getRealPath(), auth()->id());

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }
}
