<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id|required_without:lead_id',
            'lead_id' => 'nullable|exists:leads,id|required_without:client_id',
            'content' => 'required|string',
        ]);

        $validated['created_by'] = Auth::id();

        $note = Note::create($validated);
        
        $note->load('creator');

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully.',
            'note' => $note
        ]);
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully.'
        ]);
    }
}
