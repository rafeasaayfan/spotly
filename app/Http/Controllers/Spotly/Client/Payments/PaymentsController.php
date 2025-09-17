<?php

namespace App\Http\Controllers\Spotly\Client\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Payment;
use App\Traits\DataTableTrait;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Payment::where('user_id', Auth::id());

        $columnsSearching = ['user.name', 'website.name', 'plan.name', 'paymentMethod.name'];
        $columnsSelection = ['user_id', 'website_id', 'plan_id', 'payment_method_id', 'amount', 'currency', 'status', 'paid_at'];
        $relations = ['user_name', 'website_name', 'plan_name', 'paymentMethod_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender('client/payments/Payments', ['payments' => $data]);
    }
}
