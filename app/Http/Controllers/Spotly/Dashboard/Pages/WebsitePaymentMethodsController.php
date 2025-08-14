<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\WebsitePaymentMethod;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\WebsitePaymentMethods\StoreWebsitePaymentMethodRequest;
use App\Http\Requests\Dashboard\Pages\WebsitePaymentMethods\UpdateWebsitePaymentMethodRequest;
use Illuminate\Support\Facades\Log;

class WebsitePaymentMethodsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WebsitePaymentMethod::query();

        $columnsSearching = ['website.name', 'paymentMethod.name'];
        $columnsSelection = [];
        $relations = ['website_name', 'paymentMethod_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return Inertia::render('dashboard/pages/websitePaymentMethods/WebsitePaymentMethods', [
            'websitePaymentMethods' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $websites = $this->getRelation('website', ['name']);
            $paymentMethods = $this->getRelation('paymentMethod', ['name']);

            return response()->json([
                'websites' => $websites,
                'paymentMethods' => $paymentMethods,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on WebsitePaymentMethodsController@create',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsitePaymentMethodRequest $request)
    {
        try {
            $validated = $request->validated();

            $websitePaymentMethod = new WebsitePaymentMethod($validated);

            $websitePaymentMethod->save();

            return redirect()->route('dashboard.websitePaymentMethods.index')->with('message', 'Website Payment Method created successfully');
        } catch (\Exception $e) {
            Log::error('Error in WebsitePaymentMethodsController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the Website Payment Method. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = WebsitePaymentMethod::with(['website', 'paymentMethod'])->findOrFail($id);
            $websitePaymentMethod = $this->flattenRelationData($query, ['website_name', 'paymentMethod_name']);

            return response()->json([
                'data' => $websitePaymentMethod,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on WebsitePaymentMethodsController@show',
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
            $websitePaymentMethod = WebsitePaymentMethod::findOrFail($id);
            $websites = $this->getRelation('website', ['name']);
            $paymentMethods = $this->getRelation('paymentMethod', ['name']);

            return response()->json([
                'data' => $websitePaymentMethod,
                'websites' => $websites,
                'paymentMethods' => $paymentMethods,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on WebsitePaymentMethodsController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsitePaymentMethodRequest $request, WebsitePaymentMethod $websitePaymentMethod)
    {
        try {
            $validated = $request->validated();

            $websitePaymentMethod->fill($validated);

            $websitePaymentMethod->save();

            return redirect()->route('dashboard.websitePaymentMethods.index')->with('message', 'Website Payment Method updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in WebsitePaymentMethodsController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the Website Payment Method. Please try again.');
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
                'ids.*' => 'integer|exists:website_payment_methods,id',
            ]);

            WebsitePaymentMethod::destroy($validated['ids']);

            return redirect()->back()->with('message', __('Website Payment Methods(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in WebsitePaymentMethodsController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the Website Payment Method(s). Please try again.');
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $websitePaymentMethod = WebsitePaymentMethod::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $websitePaymentMethod->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Website Payment Method activated successfully.'
                : 'Website Payment Method deactivated successfully.';

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in WebsitePaymentMethodsController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the Website Payment Method status. Please try again.');
        }
    }
}
