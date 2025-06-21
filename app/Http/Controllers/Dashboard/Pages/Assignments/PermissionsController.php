<?php

namespace App\Http\Controllers\Dashboard\Pages\Assignments;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function Laravel\Prompts\error;

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
        //
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

        return redirect()->route('dashboard.permissions.index')->with('message', 'Permission created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::findOrFail($id);

        return response()->json([
            'data' => $permission
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);

        return response()->json([
            'data' => $permission
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

        return redirect()->route('dashboard.permissions.index')->with('message', 'Permission updated successfully');
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

        return redirect()->back()->with('message', __('Permission(s) deleted successfully.'));
    }

    /**
     * Show the form for add a new roles.
     */
    public function assignment(string $id)
    {
        $attachedRoles = Permission::with('roles')->findOrFail($id);
        $availableRoles = Role::whereDoesntHave('permissions', function ($query) use ($id) {
            $query->where('permissions.id', $id);
        })->get();

        return response()->json([
            'attachedRoles' => $attachedRoles,
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
            'id' => 'required|integer|exists:roles,id',
        ]);

        $permission = Permission::findOrFail($id);
        $role = Role::findOrFail($validated['id']);

        if ($validated['action'] === 'add') {
            $role->givePermissionTo($permission);
            return redirect()->back()->with('message', 'Role assigned to permission successfully.');
        } else {
            $role->revokePermissionTo($permission);
            return redirect()->back()->with('message', 'Role revoked from Permission successfully.');
        }
    }
}
