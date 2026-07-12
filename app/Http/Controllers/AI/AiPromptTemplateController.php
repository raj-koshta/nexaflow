<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiPromptTemplate;

class AiPromptTemplateController extends Controller
{
    public function index()
    {
        $templates = AiPromptTemplate::latest()->get();
        return view('ai.templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'required|string',
            'user_prompt' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $template = AiPromptTemplate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Template created successfully.',
            'template' => $template
        ]);
    }

    public function update(Request $request, AiPromptTemplate $prompt_template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'required|string',
            'user_prompt' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $prompt_template->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully.',
            'template' => $prompt_template
        ]);
    }

    public function destroy(AiPromptTemplate $prompt_template)
    {
        $prompt_template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully.'
        ]);
    }
}
