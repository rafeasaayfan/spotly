<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class PermissionsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Permission::query();

        $columnsSearching = ['name'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return Inertia::render('dashboard/pages/assignments/permissions/Permissions', [
            'permissions' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:40',
            'guard_name' => 'required|string|in:web,website',
            'description' => 'required|string|max:255',
        ]);

        try {
            $permission = new Permission($validated);
            $permission->save();

            return redirect()->route('dashboard.permissions.index')->with('message', 'Permission created successfully');
        } catch (\Exception $e) {
            Log::error('Error on PermissionsController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the permission. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            return response()->json([
                'data' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on PermissionsController@show',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            return response()->json([
                'data' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on PermissionsController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:40',
            'guard_name' => 'required|string|in:web,website',
            'description' => 'required|string|max:255',
        ]);

        try {
            $data = Permission::findOrFail($id);

            $data->update($validated);

            return redirect()->route('dashboard.permissions.index')->with('message', 'Permission updated successfully');
        } catch (\Exception $e) {
            Log::error('Error on PermissionsController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the permission. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:permissions,id',
            ]);

            Permission::destroy($validated['ids']);

            return redirect()->back()->with('message', __('Permission(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error on PermissionsController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting permission(s). Please try again.');
        }
    }

    /**
     * Show the form for add a new roles.
     */
    public function assignment(string $id)
    {
        try {
            $attachedRoles = Permission::with('roles')->findOrFail($id);
            $availableRoles = Role::whereDoesntHave('permissions', function ($query) use ($id) {
                $query->where('permissions.id', $id);
            })->get();

            return response()->json([
                'attachedRoles' => $attachedRoles,
                'availableRoles' => $availableRoles,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on PermissionsController@assignment',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage (insert roles to the permissions).
     */
    public function storeAssignments(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|string|in:add,delete',
                'id' => 'required|integer|exists:roles,id',
            ]);

            $permission = Permission::findOrFail($id);
            $role = Role::findOrFail($validated['id']);

            if ($validated['action'] === 'add') {
                $role->givePermissionTo($permission);
                return redirect()->back()->with('message', 'Role assigned to permission successfully.');
            } else {
                $role->revokePermissionTo($permission);
                return redirect()->back()->with('message', 'Role revoked from permission successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Error on PermissionsController@storeAssignments: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating role assignments. Please try again.');
        }
    }
}
