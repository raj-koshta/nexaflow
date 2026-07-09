<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id|required_without:lead_id',
            'lead_id' => 'nullable|exists:leads,id|required_without:client_id',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip', // max 10MB
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Store file in public disk (storage/app/public/documents)
        $path = $file->store('documents', 'public');

        $document = Document::create([
            'client_id' => $request->client_id,
            'lead_id' => $request->lead_id,
            'file_name' => $fileName,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'size' => $size,
            'created_by' => Auth::id(),
        ]);
        
        $document->load('creator');

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully.',
            'document' => $document
        ]);
    }

    public function destroy(Document $document)
    {
        // Delete from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully.'
        ]);
    }
}
