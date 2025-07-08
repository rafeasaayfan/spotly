<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\PaymentMethods\StorePaymentMethodRequest;
use App\Http\Requests\Dashboard\Pages\PaymentMethods\UpdatePaymentMethodRequest;

class PaymentMethodsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        $columnsSearching = ['name', 'code'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return Inertia::render('dashboard/pages/paymentMethods/PaymentMethods', [
            'paymentMethods' => $data,
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
    public function store(StorePaymentMethodRequest $request)
    {
        $validated = $request->validated();

        $paymentmethod = new PaymentMethod($validated);

        $paymentmethod->save();

        return redirect()->route('dashboard.paymentMethods.index')->with('message', 'PaymentMethod created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paymentmethod = PaymentMethod::findOrFail($id);

        return response()->json([
            'data' => $paymentmethod,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paymentmethod = PaymentMethod::findOrFail($id);

        return response()->json([
            'data' => $paymentmethod,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validated();

        $paymentMethod->fill($validated);

        $paymentMethod->save();

        return redirect()->route('dashboard.paymentMethods.index')->with('message', 'PaymentMethod updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:payment_methods,id',
        ]);

        PaymentMethod::destroy($validated['ids']);

        return redirect()->back()->with('message', __('PaymentMethod(s) deleted successfully.'));
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $paymentMethod->update([
            'is_active' => $validated['is_active'],
        ]);

        $message = $validated['is_active']
            ? 'Payment Method activated successfully.'
            : 'Payment Method deactivated successfully.';

        return redirect()->back()->with('message', $message);
    }
}
