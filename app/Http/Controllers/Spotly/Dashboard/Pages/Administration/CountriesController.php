<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Country;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Administration\Countries\StoreCountryRequest;
use App\Http\Requests\Dashboard\Pages\Administration\Countries\UpdateCountryRequest;

class CountriesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Country::query();

        $columnsSearching = ['country', 'country_ar', 'country_fr', 'code', 'phone_code'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return $this->inertiaRender('dashboard/pages/administration/countries/Countries', ['countries' => $data]);
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
    public function store(StoreCountryRequest $request)
    {
        try {
            $validated = $request->validated();
            $imagesData = $validated['flag'];
            unset($validated['flag']);

            $country = new Country($validated);

            $country->storeMediaImages($imagesData, 'flag');

            $country->save();

            return $this->redirectSuccess('dashboard.countries.index', 'Country created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CountriesController@store', $e, 'An error occurred while creating the country');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $country = Country::with('media')->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $country,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CountriesController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $country = Country::with('media')->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $country,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('CountriesController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        try {
            $validated = $request->validated();
            $imagesData = $validated['flag'];
            unset($validated['flag']);

            $country->fill($validated);

            $country->updateMediaImages($imagesData, 'flag');

            $country->save();

            return $this->redirectSuccess('dashboard.countries.index', 'Country updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CountriesController@update', $e, 'An error occurred while updating the country');
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
                'ids.*' => 'integer|exists:countries,id',
            ]);

            Country::destroy($validated['ids']);

            return $this->backSuccess('Country(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('CountriesController@destroy', $e, 'An error occurred while deleting the country(s)');
        }
    }

    // Toggle active status
    public function toggleActive(Request $request, $id)
    {
        try {
            $websiteType = Country::findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $websiteType->update([
                'is_active' => $validated['is_active'],
            ]);

            $message = $validated['is_active']
                ? 'Country activated successfully.'
                : 'Country deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('CountriesController@toggleActive', $e, 'An error occurred updating the country status');
        }
    }
}
