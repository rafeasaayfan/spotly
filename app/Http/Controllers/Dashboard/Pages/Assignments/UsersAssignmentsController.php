<?php

namespace App\Http\Controllers\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersAssignmentsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        $columnsSearching = ['name', 'email'];
        $columnsSelection = ['id', 'name', 'email'];
        $relations = ['roles_name', 'permissions_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return Inertia::render('dashboard/pages/assignments/usersAssignments/Index', [
            'data' => $data,
        ]);
    }

    /**
     * Show the form for add a new roles.
     */
    public function assignment(string $id)
    {
        $attached = User::select('id', 'name')->with(['roles', 'permissions'])->findOrFail($id);

        $availablePermissions = Permission::whereDoesntHave('users', function ($query) use ($id) {
            $query->where('users.id', $id);
        })->get();

        $availableRoles = Role::whereDoesntHave('users', function ($query) use ($id) {
            $query->where('users.id', $id);
        })->get();

        return Inertia::render('dashboard/pages/assignments/usersAssignments/actions/Assignments', [
            'attached' => $attached,
            'availablePermissions' => $availablePermissions,
            'availableRoles' => $availableRoles,
        ]);
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
                return redirect()->back()->with('error', 'Invalid assignment type.');
        }

        return redirect()->back()->with('success', $message);
    }
}
