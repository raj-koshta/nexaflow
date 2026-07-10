<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        
        // Fetch assigned projects and tasks for the overview tab
        $assignedProjects = \App\Models\Project::where('created_by', $user->id)
            ->with('client')
            ->latest()
            ->take(5)
            ->get();
            
        $assignedTasks = \App\Models\Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'Done')
            ->with('project')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('profile.edit', compact('user', 'assignedProjects', 'assignedTasks'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null; // Reset verification if email changes
        }

        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.'
            ]);
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ]);
        }

        return back()->with('success', 'Password updated successfully.');
    }
}
