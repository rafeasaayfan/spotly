<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Plan;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Plans\StorePlanRequest;
use App\Http\Requests\Dashboard\Pages\Plans\UpdatePlanRequest;
use Illuminate\Support\Facades\Log;

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

        return Inertia::render('dashboard/pages/plans/Plans', [
            'plans' => $data,
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
    public function store(StorePlanRequest $request)
    {
        try {
            $validated = $request->validated();

            $plan = new Plan($validated);

            $plan->save();

            return redirect()->route('dashboard.plans.index')->with('message', 'Plan created successfully');
        } catch (\Exception $e) {
            Log::error('Error in PlansController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the plan. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $plan = Plan::findOrFail($id);

            return response()->json([
                'data' => $plan,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on PlansController@show',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $plan = Plan::findOrFail($id);

            return response()->json([
                'data' => $plan,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on PlansController@edit',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
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

            return redirect()->route('dashboard.plans.index')->with('message', 'Plan updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in PlansController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the plan. Please try again.');
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

            return redirect()->back()->with('message', __('Plan(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in PlansController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the plan(s). Please try again.');
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

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in PlansController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the plan status. Please try again.');
        }
    }
}
