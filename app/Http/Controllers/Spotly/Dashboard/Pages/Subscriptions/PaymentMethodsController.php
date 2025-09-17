<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Subscriptions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\PaymentMethod;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\PaymentMethods\StorePaymentMethodRequest;
use App\Http\Requests\Dashboard\Pages\PaymentMethods\UpdatePaymentMethodRequest;

class PaymentMethodsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = PaymentMethod::query();

        $columnsSearching = ['name', 'code'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return $this->inertiaRender('dashboard/pages/subscriptions/paymentMethods/PaymentMethods', ['paymentMethods' => $data]);
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

            return $this->redirectSuccess('dashboard.paymentMethods.index', 'Payment Method created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CategoriesController@store', $e, 'An error occurred while creating the payment method');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $paymentMethod,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('PaymentMethodsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $paymentMethod,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('PaymentMethodsController@edit', $e, 'An error when fetching the edit page');
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

            return $this->redirectSuccess('dashboard.paymentMethods.index', 'Payment Method updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('PaymentMethodsController@update', $e, 'An error occurred while updating the payment method');
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

            return $this->backSuccess('Payment Method(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('PaymentMethodsController@destroy', $e, 'An error occurred while deleting the payment method(s)');
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

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('PaymentMethodsController@toggleActive', $e, 'An error occurred while updating the payment method status');
        }
    }
}
