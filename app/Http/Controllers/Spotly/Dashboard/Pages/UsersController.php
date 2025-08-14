<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Users\StoreUserRequest;
use App\Http\Requests\Dashboard\Pages\Users\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        $columnsSearching = ['name', 'email', 'phone_number'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return Inertia::render('dashboard/pages/users/Users', [
            'users' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            return response()->json([
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on UsersController@create',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            return redirect()->route('dashboard.users.index')->with('message', 'User created successfully');
        } catch (\Exception $e) {
            Log::error('Error in UsersController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the user. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on UsersController@show',
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
            $user = User::findOrFail($id);

            $countries = Country::with('media')->active()->get();
            $countries->transform(function ($item) {
                $item->flag = $item->getFirstMediaUrl('flag');
                return $item;
            });

            return response()->json([
                'data' => $user,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on UsersController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return redirect()->route('dashboard.users.index')->with('message', 'User updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in UsersController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the user. Please try again.');
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
                'ids.*' => 'integer|exists:users,id',
            ]);

            User::destroy($validated['ids']);

            return redirect()->back()->with('message', __('User(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in UsersController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the user(s). Please try again.');
        }
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:active,inactive,banned',
            ]);

            $user = User::findOrFail($id);
            $user->update(['status' => $validated['status']]);

            $message = $validated['status'] === 'active'
                ? 'User activated successfully.'
                : 'User deactivated successfully.';

            if ($validated['status'] === 'inactive') $message = 'The user is now inactive.';
            if ($validated['status'] === 'banned') $message = 'The user is now banned.';

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in UsersController@changeStatus: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the user status. Please try again.');
        }
    }
}
