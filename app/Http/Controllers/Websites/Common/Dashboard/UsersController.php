<?php

namespace App\Http\Controllers\Websites\Common\Dashboard;

use App\Enums\Websites\UserRole;
use App\Enums\Websites\UserStatus;
use App\Http\Controllers\Websites\BaseController;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Common\Dashboard\Users\StoreUserRequest;
use App\Http\Requests\Websites\Common\Dashboard\Users\UpdateUserRequest;
use App\Jobs\Websites\Common\UserStatusMailJob;
use App\Models\Country;
use App\Models\WebsiteUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends BaseController
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WebsiteUser::where('website_id', $this->website->id);

        $columnsSearching = ['name', 'email', 'phone_number'];
        $columnsSelection = ['id', 'name', 'email', 'email_verified_at', 'phone_number', 'status', 'role'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection);

        $websiteNameAndLogo = [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];

        return $this->inertiaRender(
            'pages/users/Users',
            [
                'users' => $data,
                'websiteNameAndLogo' => $websiteNameAndLogo
            ],
            true,
            true
        );
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
            return $this->logJsonResponse('CategoriesController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = new WebsiteUser($validated);
            $user->website_id = $this->website->id;
            $user->save();

            event(new Registered($user));

            return $this->redirectSuccess('dashboard.users.index', 'User created successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@store', $e, 'An error occurred while creating the User');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = WebsiteUser::where('website_id', $this->website->id);

            $query->when($this->website->websiteType->type === 'e-commerce', function ($q) {
                $q->withCount([
                    'ecommerceCart as cart_count',

                    'ecommerceOrders as pending_orders_count' => function ($q) {
                        $q->where('status', 'pending');
                    },
                    'ecommerceOrders as confirmed_orders_count' => function ($q) {
                        $q->where('status', 'confirmed');
                    },
                    'ecommerceOrders as delivered_orders_count' => function ($q) {
                        $q->where('status', 'delivered');
                    },
                    'ecommerceOrders as cancelled_orders_count' => function ($q) {
                        $q->where('status', 'cancelled');
                    },
                    'ecommerceOrders as refunded_orders_count' => function ($q) {
                        $q->where('status', 'refunded');
                    },
                    'ecommerceOrders as rejected_orders_count' => function ($q) {
                        $q->where('status', 'rejected');
                    },
                ])
                ->withSum([
                    'ecommerceOrders as total_spent' => fn($q) => $q->where('status', 'delivered'),
                ], 'total_amount');
            });

            $user = $query->findOrFail($id);

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
            $user = WebsiteUser::where('website_id', $this->website->id)->findOrFail($id);
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
    public function update(UpdateUserRequest $request, WebsiteUser $user)
    {
        if ($user->website_id !== $this->website->id) return;

        if ($user->role === 'owner') {
            return $this->backError('To update the owner profile, please use the profile settings in Spotly Settings');
        }

        try {
            $oldStatus = $user->status;

            $validated = $request->validated();

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            if ($oldStatus !== $validated['status']) {
                UserStatusMailJob::dispatch(
                    $this->website->email,
                    $this->website->name,
                    $this->website->subdomain,
                    $this->website->websiteType->type,
                    $user->email,
                    $user->name,
                    $validated['status']
                );
            }

            return $this->redirectSuccess('dashboard.users.index', 'User updated successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@update', $e, 'An error occurred while updating the User');
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
                'ids.*' => 'integer|exists:website_users,id,website_id,' . $this->website->id,
            ]);

            $owner = WebsiteUser::whereIn('id', $validated['ids'])
                ->where('website_id', $this->website->id)
                ->where('role', 'owner')
                ->first();

            if ($owner) {
                return $this->backError('You cannot delete the owner!');
            }

            WebsiteUser::destroy($validated['ids']);

            return $this->backSuccess('User(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@destroy', $e, 'An error occurred while deleting the User(s)');
        }
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => ['required', 'string', Rule::enum(UserStatus::class)],
            ]);

            $user = WebsiteUser::where('website_id', $this->website->id)->findOrFail($id);
            if ($user->role === UserRole::OWNER) {
                return $this->backError('You cannot change the owner status!');
            }

            $user->update(['status' => $validated['status']]);

            $message = match ($validated['status']) {
                'active' => 'User activated successfully.',
                'inactive' => 'User deactivated successfully.',
                'banned' => 'User has been banned.',
            };

            UserStatusMailJob::dispatch(
                $this->website->email,
                $this->website->name,
                $this->website->subdomain,
                $this->website->websiteType->type,
                $user->email,
                $user->name,
                $validated['status']
            );

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@changeStatus', $e, 'An error occurred while updating the User status');
        }
    }

    /**
     * Change the role of the specified resource.
     */
    public function changeRole(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'role' => ['required', 'string', Rule::enum(UserRole::class)],
            ]);

            $user = WebsiteUser::where('website_id', $this->website->id)->findOrFail($id);
            if ($user->role === UserRole::OWNER) {
                return $this->backError('You cannot change the owner role!');
            }
            if ($validated['role'] === UserRole::OWNER) {
                return $this->backError('There can only be one owner per website!');
            }
            $user->update(['role' => $validated['role']]);

            if ($validated['role'] === UserRole::ADMIN) {
                $message = 'User promoted to admin successfully.';
            } else {
                $message = 'User role changed to user successfully.';
            }

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('UsersController@changeStatus', $e, 'An error occurred while updating the User status');
        }
    }
}
