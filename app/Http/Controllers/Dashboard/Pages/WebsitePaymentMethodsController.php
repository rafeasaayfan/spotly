<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\WebsitePaymentMethod;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\WebsitePaymentMethods\StoreWebsitePaymentMethodRequest;
use App\Http\Requests\Dashboard\Pages\WebsitePaymentMethods\UpdateWebsitePaymentMethodRequest;

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
        $websites = $this->getRelation('webiste', ['name']);
        $paymentMethods = $this->getRelation('paymentMethod', ['name']);

        return response()->json([
            'websites' => $websites,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsitePaymentMethodRequest $request)
    {
        $validated = $request->validated();

        $websitePaymentMethod = new WebsitePaymentMethod($validated);

        $websitePaymentMethod->save();

        return redirect()->route('dashboard.websitePaymentMethods.index')->with('message', 'Website Payment Method created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = WebsitePaymentMethod::with(['website', 'paymentMethod'])->findOrFail($id);
        $websitePaymentMethod = $this->flattenRelationData($query, ['website_name', 'paymentMethod_name']);

        return response()->json([
            'data' => $websitePaymentMethod,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $websitePaymentMethod = WebsitePaymentMethod::findOrFail($id);
        $websites = $this->getRelation('webiste', ['name']);
        $paymentMethods = $this->getRelation('paymentMethod', ['name']);

        return response()->json([
            'data' => $websitePaymentMethod,
            'websites' => $websites,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsitePaymentMethodRequest $request, WebsitePaymentMethod $websitePaymentMethod)
    {
        $validated = $request->validated();

        $websitePaymentMethod->fill($validated);

        $websitePaymentMethod->save();

        return redirect()->route('dashboard.websitePaymentMethods.index')->with('message', 'Website Payment Method updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:website_payment_methods,id',
        ]);

        WebsitePaymentMethod::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Website Payment Methods(s) deleted successfully.'));
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
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
    }
}
