<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\WebsitePaymentMethod;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\WebsitePaymentMethods\StoreWebsitePaymentMethodRequest;
use App\Http\Requests\Dashboard\Pages\WebsitePaymentMethods\UpdateWebsitePaymentMethodRequest;

class WebsitePaymentMethodsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = WebsitePaymentMethod::query();

        $columnsSearching = ['website.name', 'paymentMethod.name'];
        $columnsSelection = [];
        $relations = ['website_name', 'paymentMethod_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('dashboard/pages/websitePaymentMethods/WebsitePaymentMethods', ['websitePaymentMethods' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $websites = $this->getRelation('website', ['name']);
            $paymentMethods = $this->getRelation('paymentMethod', ['name']);

            return $this->jsonSuccess('', [
                'websites' => $websites,
                'paymentMethods' => $paymentMethods,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsitePaymentMethodsController@create', $e, 'An error when fetching the create page');
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

            return $this->redirectSuccess('dashboard.websitePaymentMethods.index', 'Website Payment Method created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsitePaymentMethodsController@store', $e, 'An error occurred while creating the Website Payment Method');
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

            return $this->jsonSuccess('', [
                'data' => $websitePaymentMethod,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsitePaymentMethodsController@show', $e, 'An error when fetching the show page');
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

            return $this->jsonSuccess('', [
                'data' => $websitePaymentMethod,
                'websites' => $websites,
                'paymentMethods' => $paymentMethods,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsitePaymentMethodsController@edit', $e, 'An error when fetching the edit page');
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

            return $this->redirectSuccess('dashboard.websitePaymentMethods.index', 'Website Payment Method updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsitePaymentMethodsController@update', $e, 'An error occurred while updating the Website Payment Method');
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

            return $this->backSuccess('Website Payment Methods(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsitePaymentMethodsController@destroy', $e, 'An error occurred while deleting the Website Payment Methods(s)');
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

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('WebsitePaymentMethodsController@toggleActive', $e, 'An error occurred while updating the Website Payment Method status');
        }
    }
}
