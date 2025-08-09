<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\PaymentMethods\StorePaymentMethodRequest;
use App\Http\Requests\Dashboard\Pages\PaymentMethods\UpdatePaymentMethodRequest;
use Illuminate\Support\Facades\Log;

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
        try {
            $validated = $request->validated();

            $paymentMethod = new PaymentMethod($validated);
            $paymentMethod->save();

            return redirect()->route('dashboard.paymentMethods.index')->with('message', 'Payment Method created successfully.');

        } catch (\Exception $e) {
            Log::error('Error in PaymentMethodsController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the payment method. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($id);

            return response()->json([
                'data' => $paymentMethod,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on PaymentMethodsController@show',
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
            $paymentMethod = PaymentMethod::findOrFail($id);

            return response()->json([
                'data' => $paymentMethod,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on PaymentMethodsController@edit',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        try {
            $validated = $request->validated();

            $paymentMethod->fill($validated);
            $paymentMethod->save();

            return redirect()->route('dashboard.paymentMethods.index')->with('message', 'Payment Method updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error in PaymentMethodsController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the payment method. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids'   => 'required|array',
                'ids.*' => 'integer|exists:payment_methods,id',
            ]);

            PaymentMethod::destroy($validated['ids']);

            return redirect()->back()->with('message', __('Payment Method(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in PaymentMethodsController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the payment method(s). Please try again.');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error in PaymentMethodsController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while toggling the payment method status. Please try again.');
        }
    }
}
