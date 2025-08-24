<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;

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

        return $this->inertiaRender('dashboard/pages/assignments/permissions/Permissions', ['permissions' => $data]);
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

            return $this->redirectSuccess('dashboard.permissions.index', 'Permission created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('PermissionsController@store', $e, 'An error occurred while creating the permission');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $permission,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('PermissionsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $permission,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('PermissionsController@edit', $e, 'An error when fetching the edit page');
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

            return $this->redirectSuccess('dashboard.permissions.index', 'Permission updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('PermissionsController@update', $e, 'An error occurred while updating the permission');
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

            return $this->backSuccess('Permission(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('PermissionsController@destroy', $e, 'An error occurred while deleting the permission(s)');
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

            return $this->jsonSuccess('', [
                'attachedRoles' => $attachedRoles,
                'availableRoles' => $availableRoles,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('PermissionsController@assignment', $e, 'An error when fetching the assignment page');
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
                return $this->backSuccess('Role assigned to permission successfully');
            } else {
                $role->revokePermissionTo($permission);
                return $this->backSuccess('Role revoked from permission successfully');
            }
        } catch (\Exception $e) {
            return $this->logResponse('PermissionsController@storeAssignments', $e, 'An error occurred while updating the role assignments');
        }
    }
}
