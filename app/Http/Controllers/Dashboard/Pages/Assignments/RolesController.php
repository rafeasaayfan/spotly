<?php

namespace App\Http\Controllers\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::query();

        $columnsSearching = ['name'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return Inertia::render('dashboard/pages/assignments/roles/Roles', [
            'roles' => $data,
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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'guard_name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);

            $role = new Role($validated);
            $role->save();

            return redirect()->route('dashboard.roles.index')->with('message', 'Role created successfully');
        } catch (\Exception $e) {
            Log::error('Error on RolesController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the role. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $role = Role::findOrFail($id);

            return response()->json([
                'data' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on RolesController@show',
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
            $role = Role::findOrFail($id);

            return response()->json([
                'data' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on RolesController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $role = Role::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'guard_name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);

            $role->update($validated);

            return redirect()->route('dashboard.roles.index')->with('message', 'Role updated successfully');
        } catch (\Exception $e) {
            Log::error('Error on RolesController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the role. Please try again.');
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
                'ids.*' => 'integer|exists:roles,id',
            ]);

            Role::destroy($validated['ids']);

            return redirect()->back()->with('message', __('Role(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error on RolesController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting role(s). Please try again.');
        }
    }

    /**
     * Show the form for add a new roles.
     */
    public function assignment(string $id)
    {
        try {
            $attachedPermissions = Role::with('permissions')->findOrFail($id);
            $availablePermissions = Permission::whereDoesntHave('roles', function ($query) use ($id) {
                $query->where('roles.id', $id);
            })->get();

            return response()->json([
                'attachedPermissions' => $attachedPermissions,
                'availablePermissions' => $availablePermissions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on RolesController@assignment',
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
                'id' => 'required|integer|exists:permissions,id',
            ]);

            $role = Role::findOrFail($id);
            $permission = Permission::findOrFail($validated['id']);

            if ($validated['action'] === 'add') {
                $role->givePermissionTo($permission);
                return redirect()->back()->with('message', 'Permission assigned to role successfully.');
            } else {
                $role->revokePermissionTo($permission);
                return redirect()->back()->with('message', 'Permission revoked from role successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Error on RolesController@storeAssignments: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('An error occurred while updating permission assignments. Please try again.');
        }
    }
}
