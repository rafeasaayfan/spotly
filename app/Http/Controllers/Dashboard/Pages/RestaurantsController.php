<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RestaurantsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Restaurant::query();

        $columnsSearching = ['name', 'email'];
        $relations = ['user_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, [], $relations);

        $data->transform(function ($item) {
            $item->image = $item->getFirstMediaUrl('image');
            return $item;
        });

        return Inertia::render('dashboard/pages/restaurants/Restaurants', [
            'restaurants' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select(['id', 'name'])->get();

        return Inertia::render('dashboard/pages/restaurants/actions/Create', [
            'data' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:restaurants,email',
            'phone_number' => 'required|max:255|unique:restaurants,phone_number',
            'country' => 'required|max:255',
            'city' => 'required|max:255',
            'open_start' => 'nullable|date_format:H:i',
            'close_start' => 'nullable|date_format:H:i',
        ]);

        unset($validated['image']);

        $restaurant = new Restaurant($validated);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $restaurant->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        $restaurant->save();

        return redirect()->route('dashboard.restaurants.index')->with('success', 'Restaurant created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->image = $restaurant->getFirstMediaUrl('image');

        return Inertia::render('dashboard/pages/restaurants/actions/View', [
            'data' => $restaurant,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $users = User::select(['id', 'name'])->get();

        $restaurant->image = $restaurant->getFirstMediaUrl('image');

        return Inertia::render('dashboard/pages/restaurants/actions/Edit', [
            'data' => $restaurant,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //         $data = Test::findOrFail($id);

        // if ($request->only('status') && count($request->all()) === 1) {
        //     $request->validate([
        //         'status' => 'required',
        //     ]);

        //     $newStatus = $request->input('status') ? 'active' : 'inactive';
        //     $data->status = $newStatus;
        //     $data->save();

        //     return $this->successResponse('Status updated successfully');
        // }

        // $request->validate([
        //     'image' => 'nullable|image|max:2048',
        // ]);

        // $validated = $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255|unique:tests,email,' . $id,
        //     'status' => 'required|string|in:active,inactive',
        //     'description' => 'nullable|string',
        // ]);

        // $data->update($validated);

        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //     $data->clearMediaCollection('image');
        //     $data->addMediaFromRequest('image')->toMediaCollection('image');
        // }

        // return $this->successResponse('Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:restaurants,id',
        ]);

        User::destroy($validated['ids']);

        return redirect()->back()->with('success', __('Restaurant(s) deleted successfully.'));
    }
}
