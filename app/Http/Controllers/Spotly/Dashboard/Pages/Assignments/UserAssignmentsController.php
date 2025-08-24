<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\User;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserAssignmentsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = User::query();
        $columnsSearching = ['name', 'email'];
        $columnsSelection = ['id', 'name', 'email'];
        $relations = ['roles_name', 'permissions_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('dashboard/pages/assignments/userAssignments/Index', ['data' => $data]);
    }

    /**
     * Show the form for add a new roles.
     */
    public function assignment(string $id)
    {
        try {
            $attached = User::select('id', 'name')->with(['roles', 'permissions'])->findOrFail($id);

            $availablePermissions = Permission::whereDoesntHave('users', function ($query) use ($id) {
                $query->where('users.id', $id);
            })->get();

            $availableRoles = Role::whereDoesntHave('users', function ($query) use ($id) {
                $query->where('users.id', $id);
            })->get();

            return $this->jsonSuccess('', [
                'attached' => $attached,
                'availablePermissions' => $availablePermissions,
                'availableRoles' => $availableRoles,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('UserAssignmentsController@assignment', $e, 'An error when fetching the assignment page');
        }
    }

    /**
     * Update the specified resource in storage (assign/remove roles or permissions to/from a user).
     */
    public function storeAssignments(Request $request, string $id)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:add,delete',
            'type' => 'required|string|in:roles,permissions',
            'id' => 'required|integer|exists:' . ($request->type === 'roles' ? 'roles' : 'permissions') . ',id',
        ]);

        try {
            $user = User::findOrFail($id);

            switch ($validated['type']) {
                case 'roles':
                    $role = Role::findOrFail($validated['id']);
                    if ($validated['action'] === 'add') {
                        $user->assignRole($role);
                        $message = 'Role assigned successfully.';
                    } else {
                        $user->removeRole($role);
                        $message = 'Role removed successfully.';
                    }
                    break;

                case 'permissions':
                    $permission = Permission::findOrFail($validated['id']);
                    if ($validated['action'] === 'add') {
                        $user->givePermissionTo($permission);
                        $message = 'Permission granted successfully.';
                    } else {
                        $user->revokePermissionTo($permission);
                        $message = 'Permission revoked successfully.';
                    }
                    break;

                default:
                    return $this->backError('Invalid assignment type');
            }

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('UserAssignmentsController@storeAssignments', $e, 'An error occurred while updating the assignments');
        }
    }
}
