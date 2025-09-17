<?php

namespace App\Http\Controllers\Spotly\Client\Payments;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Website;
use Illuminate\Http\Request;

class MakePaymentController extends Controller
{
    /**
     * Display the payment page.
     */
    public function index(Request $request) 
    {
        $validated = $request->validate([
            'search' => ['sometimes', 'string', 'nullable'],
            'status' => ['sometimes', 'string', 'nullable', 'in:expiredSoon,notPaid'],
        ]);

        $query = Website::status('approved')->with(['websiteType:id,type']);

        if (!empty($validated['search'])) {
            $query->where('name', 'like', '%' . $validated['search'] . '%');
        }
        if (!empty($validated['status']) && $validated['status'] === 'expiredSoon') {
            $query->expiringSubscription();
        } else if (!empty($validated['status']) && $validated['status'] === 'notPaid') {
            $query->expiredSubscription();
        }

        $websites = $query->get();

        return $this->inertiaRender('client/payments/MakePayment', [
            'websites' => $websites
        ]);
    }

    /**
     * Get the specified plans.
     */
    public function getPlans(Request $request) 
    {
        $validated = $request->validate([
            'websiteTypeId' => 'required|integer|exists:website_types,id'
        ]);
        
        $plans = Plan::where('website_type_id', $validated['websiteTypeId'])->active()->get();

        return $this->jsonSuccess('', ['plans' => $plans]);
    }
}
