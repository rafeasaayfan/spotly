<?php

namespace App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\DeliveryFee;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\DeliveryFees\StoreDeliveryFeeRequest;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\DeliveryFees\UpdateDeliveryFeeRequest;

class DeliveryFeesController extends Controller
{
    use DataTableTrait;

    public $website;

    public function __construct()
    {
        $this->website = app('website')->load('media');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DeliveryFee::where('website_id', $this->website->id);

        $columnsSearching = ['city'];
        $columnsSelection = ['id', 'city', 'amount', 'created_at'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection);

        $websiteNameAndLogo = [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];

        return $this->inertiaRender(
            'pages/deliveryFees/DeliveryFees',
            [
                'deliveryFees' => $data,
                'websiteNameAndLogo' => $websiteNameAndLogo
            ],
            true,
            true
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $cities = config('cities.lebanon');

            return $this->jsonSuccess('', [
                'cities' => $cities
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('DeliveryFeesController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeliveryFeeRequest $request)
    {
        try {
            $validated = $request->validated();

            $deliveryFee = new DeliveryFee($validated);
            $deliveryFee->website_id = $this->website->id;
            $deliveryFee->save();

            return $this->redirectSuccess('dashboard.deliveryFees.index', 'Delivery Fee created successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('DeliveryFeesController@store', $e, 'An error occurred while creating the DeliveryFee');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $deliveryFee = DeliveryFee::where('website_id', $this->website->id)->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $deliveryFee,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('DeliveryFeesController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $deliveryFee = DeliveryFee::where('website_id', $this->website->id)->findOrFail($id);
            $cities = config('cities.lebanon');

            return $this->jsonSuccess('', [
                'data' => $deliveryFee,
                'cities' => $cities
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('DeliveryFeesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeliveryFeeRequest $request, DeliveryFee $deliveryFee)
    {
        if($deliveryFee->website_id !== $this->website->id) return;

        try {
            $validated = $request->validated();

            $deliveryFee->fill($validated);
            $deliveryFee->save();

            return $this->redirectSuccess('dashboard.deliveryFees.index', 'Delivery Fee updated successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('DeliveryFeesController@update', $e, 'An error occurred while updating the DeliveryFee');
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
                'ids.*' => 'integer|exists:delivery_fees,id,website_id,' . $this->website->id,
            ]);
    
            DeliveryFee::destroy($validated['ids']);
    
            return $this->backSuccess('Delivery Fee(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('DeliveryFeesController@destroy', $e, 'An error occurred while deleting the DeliveryFee(s)');
        }
    }
}
