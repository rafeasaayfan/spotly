<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Role::query();

        $columnsSearching = ['name'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return $this->inertiaRender('dashboard/pages/assignments/roles/Roles', ['roles' => $data]);
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
            $role = new Role($validated);
            $role->save();

            return $this->redirectSuccess('dashboard.roles.index', 'Role created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('RolesController@store', $e, 'An error occurred while creating the role');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $role = Role::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $role,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('RolesController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $role = Role::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $role,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('RolesController@edit', $e, 'An error when fetching the edit page');
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
            $role = Role::findOrFail($id);

            $role->update($validated);

            return $this->redirectSuccess('dashboard.roles.index', 'Role updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('RolesController@update', $e, 'An error occurred while updating the role');
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

            return $this->backSuccess('Role(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('RolesController@destroy', $e, 'An error occurred while deleting the role(s)');
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

            return $this->jsonSuccess('', [
                'attachedPermissions' => $attachedPermissions,
                'availablePermissions' => $availablePermissions,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('RolesController@assignment', $e, 'An error when fetching the assignment page');
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
                return $this->backSuccess('Permission assigned to role successfully');
            } else {
                $role->revokePermissionTo($permission);
                return $this->backSuccess('Permission revoked from role successfully');
            }
        } catch (\Exception $e) {
            return $this->logResponse('RolesController@storeAssignments', $e, 'An error occurred while updating the permission assignments');
        }
    }
}
