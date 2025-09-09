<?php

namespace App\Http\Controllers\Spotly\Client;

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
        ]);

        $query = Website::status('approved')->with(['websiteType:id,type']);

        if (!empty($validated['search'])) {
            $query->where('name', 'like', '%' . $validated['search'] . '%');
        }

        $unpaidWebsites = $query->get();

        return $this->inertiaRender('client/MakePayment', [
            'unpaidWebsites' => $unpaidWebsites
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
