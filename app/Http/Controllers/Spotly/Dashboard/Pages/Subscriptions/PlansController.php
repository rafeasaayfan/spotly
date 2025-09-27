<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Subscriptions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Plan;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Subscriptions\Plans\StorePlanRequest;
use App\Http\Requests\Dashboard\Pages\Subscriptions\Plans\UpdatePlanRequest;

class PlansController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Plan::query();

        $columnsSearching = ['name', 'price'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return $this->inertiaRender('dashboard/pages/subscriptions/plans/Plans', ['plans' => $data]);
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
    public function store(StorePlanRequest $request)
    {
        try {
            $validated = $request->validated();

            $plan = new Plan($validated);

            $plan->save();

            return $this->redirectSuccess('dashboard.plans.index', 'Plan created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('PlansController@store', $e, 'An error occurred while creating the plan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $plan = Plan::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $plan,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('PlansController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $plan = Plan::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $plan,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('PlansController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        try {
            $validated = $request->validated();

            $plan->fill($validated);

            $plan->save();

            return $this->redirectSuccess('dashboard.plans.index', 'Plan updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('PlansController@update', $e, 'An error occurred while updating the plan');
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
                'ids.*' => 'integer|exists:plans,id',
            ]);

            Plan::destroy($validated['ids']);

            return $this->backSuccess('Plan(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('PlansController@destroy', $e, 'An error occurred while deleting the plan(s)');
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $plan = Plan::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $plan->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Plan activated successfully.'
                : 'Plan deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('PlansController@toggleActive', $e, 'An error occurred while updating the plan status');
        }
    }
}
