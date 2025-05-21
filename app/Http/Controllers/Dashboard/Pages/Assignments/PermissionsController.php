<?php

namespace App\Http\Controllers\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        return Inertia::render('dashboard/pages/assignments/permissions/actions/Create');
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

        $permission = new Permission($validated);

        $permission->save();

        return redirect()->route('dashboard.permissions.index')->with('success', 'Permission created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::findOrFail($id);

        return Inertia::render('dashboard/pages/assignments/permissions/actions/View', [
            'data' => $permission,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);

        return Inertia::render('dashboard/pages/assignments/permissions/actions/Edit', [
            'data' => $permission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $data->update($validated);
        $data->save();

        return redirect()->route('dashboard.roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:permissions,id',
        ]);

        Permission::destroy($validated['ids']);

        return redirect()->back()->with('success', __('Permission(s) deleted successfully.'));
    }

    /**
     * Show the form for add a new roles.
     */
    public function assignment(string $id)
    {
        $permission = Permission::with('roles')->findOrFail($id);
        $availableRoles = Role::whereDoesntHave('permissions', function ($query) use ($id) {
            $query->where('permissions.id', $id);
        })->get();

        return Inertia::render('dashboard/pages/assignments/permissions/actions/Edit', [
            'data' => $permission,
            'availableRoles' => $availableRoles,
        ]);
    }

    /**
     * Update the specified resource in storage (insert roles to the permissions).
     */
    public function storeAssignments(Request $request, string $id)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:add,delete',
            'id' => 'required|string|exists:roles,id',
        ]);

        $permission = Permission::findOrFail($id);

        $role = Role::findOrFail($validated['id']);

        if ($validated['action'] === 'add') {
            $role->givePermissionTo($permission);
            return redirect()->back()->with('success', 'Permission assigned to role successfully.');

        } else {
            $role->revokePermissionTo($permission);
            return redirect()->back()->with('success', 'Permission revoked from role successfully.');
        }
    }
}
