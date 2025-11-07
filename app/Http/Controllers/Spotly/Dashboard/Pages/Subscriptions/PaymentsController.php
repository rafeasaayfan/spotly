<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::query();

        $columnsSearching = ['user.name', 'website.name', 'plan.name', 'paymentMethod.name'];
        $relations = ['user_name', 'website_name', 'plan_name', 'paymentMethod_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, [], $relations);

        return $this->inertiaRender('dashboard/pages/subscriptions/payments/Payments', ['payments' => $data]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $payment = Payment::with([
                'user:id,name,email,phone_number', 
                'website:id,name,subdomain,email,is_active,status,website_type_id',
                'website.websiteType:id,type',
                'plan:id,name,price,duration',
                'paymentMethod:id,name'
            ])->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $payment,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('PaymentsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
    }
}
