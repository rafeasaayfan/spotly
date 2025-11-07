<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Subscriptions;

use App\Http\Controllers\Controller;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subscription::query();

        $columnsSearching = ['user.name', 'website.name', 'plan.name'];
        $columnsSelection = ['id', 'user_id', 'website_id', 'plan_id', 'status', 'start_date', 'end_date', 'created_at'];
        $relations = ['user_name', 'website_name', 'plan_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('dashboard/pages/subscriptions/overview/Subscriptions', ['subscriptions' => $data]);
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
    // public function store(StoreOverviewRequest $request)
    // {
    //     try {
    //         $validated = $request->validated();
    //         // unset($validated['image']);

    //         $overview = new Subscription($validated);

    //         // if ($request->hasFile('image') && $request->file('image')->isValid()) {
    //             // $overview->addMediaFromRequest('image')
    //             // ->toMediaCollection('image');
    //         // }

    //         $overview->save();

    //         return $this->redirectSuccess('dashboard.overview.index', 'Overview created successfully');
    //     } catch (\Exception $e) {
    //         return $this->logResponse('SubscriptionsController@store', $e, 'An error occurred while creating the Overview');
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $subscription = Subscription::with([
                'user:id,name,email,phone_number', 
                'website:id,name,subdomain,email,is_active,status,website_type_id',
                'website.websiteType:id,type',
                'plan:id,name,price,duration',
                'paymentMethod:id,name'
            ])->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $subscription,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('SubscriptionsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     try {
    //         $subscription = Subscription::findOrFail($id);

    //         return $this->jsonSuccess('', [
    //             'data' => $subscription,
    //         ]);
    //     } catch (\Exception $e) {
    //         return $this->logJsonResponse('SubscriptionsController@edit', $e, 'An error when fetching the edit page');
    //     }
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateOverviewRequest $request, Overview $overview)
    // {
    //     try {
    //         $validated = $request->validated();
    //         // unset($validated['image']);

    //         $overview->fill($validated);

    //         // if ($request->hasFile('image') && $request->file('image')->isValid()) {
    //             // $overview->clearMediaCollection('image');
    //             // $overview->addMediaFromRequest('image')
    //             // ->toMediaCollection('image');
    //         // }

    //         $overview->save();

    //         return $this->redirectSuccess('dashboard.overview.index', 'Overview updated successfully');
    //     } catch (\Exception $e) {
    //         return $this->logResponse('SubscriptionsController@update', $e, 'An error occurred while updating the Overview');
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'ids' => 'required|array',
    //             'ids.*' => 'integer|exists:overview,id',
    //         ]);
    
    //         Subscription::destroy($validated['ids']);
    
    //         return $this->backSuccess('Subscription(s) deleted successfully');
    //     } catch (\Exception $e) {
    //         return $this->logResponse('SubscriptionsController@destroy', $e, 'An error occurred while deleting the Overview(s)');
    //     }
    // }

    /**
     * Change the status of the specified resource.
    */
    // public function changeStatus(Request $request, $id)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'status' => 'required|string|in:active,inactive,banned',
    //         ]);

    //         $subscription = Subscription::findOrFail($id);
    //         $subscription->update(['status' => $validated['status']]);

    //         $message = $validated['status'] === 'active'
    //             ? 'User activated successfully.'
    //             : 'User deactivated successfully.';

    //         if ($validated['status'] === 'inactive') $message = 'The user is now inactive.';
    //         if ($validated['status'] === 'banned') $message = 'The user is now banned.';

    //         return $this->backSuccess($message);
    //     } catch (\Exception $e) {
    //         return $this->logResponse('SubscriptionsController@changeStatus', $e, 'An error occurred while updating the Overview status');
    //     }
    // }
}
