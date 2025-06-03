<?php

namespace App\Http\Controllers\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        return Inertia::render('dashboard/pages/assignments/roles/actions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $role = new Role($validated);

        $role->save();

        return redirect()->route('dashboard.roles.index')->with('message', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::findOrFail($id);

        return Inertia::render('dashboard/pages/assignments/roles/actions/View', [
            'data' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        return Inertia::render('dashboard/pages/assignments/roles/actions/Edit', [
            'data' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $data->update($validated);
        $data->save();

        return redirect()->route('dashboard.roles.index')->with('message', 'Role created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:roles,id',
        ]);

        Role::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Role(s) deleted successfully.'));
    }

    /**
     * Show the form for add a new roles.
     */
    public function assignment(string $id)
    {
        $attachedPermissions = Role::with('permissions')->findOrFail($id);
        $availablePermissions = Permission::whereDoesntHave('roles', function ($query) use ($id) {
            $query->where('roles.id', $id);
        })->get();

        return Inertia::render('dashboard/pages/assignments/roles/actions/AssignPermissions', [
            'attachedPermissions' => $attachedPermissions,
            'availablePermissions' => $availablePermissions,
        ]);
    }

    /**
     * Update the specified resource in storage (insert roles to the permissions).
     */
    public function storeAssignments(Request $request, string $id)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:add,delete',
            'id' => 'required|integer|exists:permissions,id',
        ]);


        $role = Role::findOrFail($id);

        $permission = Permission::findOrFail($validated['id']);

        if ($validated['action'] === 'add') {
            $role->givePermissionTo($permission);
            return redirect()->back()->with('success', 'Permission assigned to role successfully.');

        } else {
            $role->revokePermissionTo($permission);
            return redirect()->back()->with('success', 'Permission revoked from role successfully.');
        }
    }
}
