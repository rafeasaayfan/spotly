<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\User;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Users\StoreUserRequest;
use App\Http\Requests\Dashboard\Pages\Users\UpdateUserRequest;
use App\Jobs\Spotly\UserStatusMailJob;
use Illuminate\Support\Facades\Hash;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = User::query();

        $columnsSearching = ['name', 'email', 'phone_number'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return $this->inertiaRender('dashboard/pages/users/Users', ['users' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $countries = Country::with('media')->active()->get();

            return $this->jsonSuccess('', [
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('UsersController@create', $e, 'An error when fetching the create page');
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

            return $this->redirectSuccess('dashboard.users.index', 'User created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@store', $e, 'An error occurred while creating the user');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::with(['roles:id,name', 'permissions:id,name', 'websites:owner_id,name,is_active'])->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('UsersController@show', $e, 'An error when fetching the show page');
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

            return $this->jsonSuccess('', [
                'data' => $user,
                'countries' => $countries,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('UsersController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();
            $oldStatus = $user->status;

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            if($oldStatus !== $data['status']) {
                UserStatusMailJob::dispatch(
                    $user->email,
                    $user->name,
                    $data['status']
                );
            }

            return $this->redirectSuccess('dashboard.users.index', 'User updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@update', $e, 'An error occurred while updating the user');
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

            return $this->backSuccess('User(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@destroy', $e, 'An error occurred while deleting the user(s)');
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
            if(($user->id === Auth::id() || $user->hasRole('super_admin')) && $validated['status'] !== 'active') {
                return $this->backError('You can\'t ban or deactivate this account');
            }

            $user->update(['status' => $validated['status']]);

            $message = match ($validated['status']) {
                'active' => 'User activated successfully.',
                'inactive' => 'User deactivated successfully.',
                'banned' => 'User has been banned.',
            };

            UserStatusMailJob::dispatch($user->email, $user->name, $validated['status']);

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@changeStatus', $e, 'An error occurred while updating the user status');
        }
    }
}
