<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::latest()->get();
            return view('permissions.table', compact('permissions'))->render();
        }

        return view('permissions.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name'
        ]);

        $permission = Permission::create(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully.',
            'data' => $permission
        ]);
    }

    public function edit(Permission $permission)
    {
        return response()->json($permission);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id
        ]);

        $permission->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully.'
        ]);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully.'
        ]);
    }
}
