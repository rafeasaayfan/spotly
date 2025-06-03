<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Users\StoreUserRequest;
use App\Http\Requests\Dashboard\Pages\Users\UpdateUserRequest;

class UsersController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        $columnsSearching = ['name', 'email'];
        $columnsSelection = [];
        $relations = [];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        // $data->transform(function ($item) {
        //    $item->image = $item->getFirstMediaUrl('image');
        //    return $item;
        // });

        return Inertia::render('dashboard/pages/users/Users', [
            'users' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('dashboard/pages/users/actions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        // unset($validated['image']);

        $user = new User($validated);

        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //    $user->addMediaFromRequest('image')
        //        ->toMediaCollection('image');
        // }

        $user->save();

        return redirect()->route('dashboard.users.index')->with('message', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        // $user->image = $user->getFirstMediaUrl('image');

        return Inertia::render('dashboard/pages/users/actions/View', [
            'data' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        // $user->image = $user->getFirstMediaUrl('image');

        return Inertia::render('dashboard/pages/users/actions/Edit', [
            'data' => $user,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        // unset($validated['image']);

        $user->fill($validated);

        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //    $user->clearMediaCollection('image');
        //    $user->addMediaFromRequest('image')
        //        ->toMediaCollection('image');
        // }

        $user->save();

        return redirect()->route('dashboard.users.index')->with('message', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:users,id',
        ]);

        User::destroy($validated['ids']);

        return redirect()->back()->with('message', __('User(s) deleted successfully.'));
    }
}
