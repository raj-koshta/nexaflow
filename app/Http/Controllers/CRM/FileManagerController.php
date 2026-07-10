<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Document::with(['creator', 'client', 'lead'])->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('file_name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('type') && $request->type != '') {
            $type = $request->type;
            if ($type == 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($type == 'document') {
                $query->where('mime_type', 'like', 'application/pdf')
                      ->orWhere('mime_type', 'like', 'application/msword')
                      ->orWhere('mime_type', 'like', 'application/vnd.openxmlformats-officedocument%');
            }
        }

        $documents = $query->paginate(24);

        // Calculate total storage
        $totalStorageBytes = Document::sum('size');

        return view('file_manager.index', compact('documents', 'totalStorageBytes'));
    }

    /**
     * Download the specified resource.
     */
    public function download(Document $file)
    {
        if (!Storage::exists($file->file_path)) {
            return back()->with('error', 'File not found on storage.');
        }

        return Storage::download($file->file_path, $file->file_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $file)
    {
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }

        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }
}
