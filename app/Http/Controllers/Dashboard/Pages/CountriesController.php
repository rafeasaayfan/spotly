<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Countries\StoreCountryRequest;
use App\Http\Requests\Dashboard\Pages\Countries\UpdateCountryRequest;
use Illuminate\Support\Facades\Log;

class CountriesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Country::query();

        $columnsSearching = ['country', 'country_ar', 'country_fr', 'code', 'phone_code'];
        $columnsSelection = [];
        $relations = [];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        $data->transform(function ($item) {
            $item->flag = $item->getFirstMediaUrl('flag');
            return $item;
        });

        return Inertia::render('dashboard/pages/countries/Countries', [
            'countries' => $data,
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
    public function store(StoreCountryRequest $request)
    {
        try {
            $validated = $request->validated();
            unset($validated['flag']);

            $country = new Country($validated);

            if ($request->hasFile('flag') && $request->file('flag')->isValid()) {
                $country->addMediaFromRequest('flag')
                    ->toMediaCollection('flag');
            }

            $country->save();

            return redirect()->route('dashboard.countries.index')->with('message', 'Country created successfully');
        } catch (\Exception $e) {
            Log::error('Error in CountriesController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while creating the country. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $country = Country::findOrFail($id);
            $country->flag = $country->getFirstMediaUrl('flag');

            return response()->json([
                'data' => $country,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on CountriesController@show',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $country = Country::findOrFail($id);

            $country->flag = $country->getFirstMediaUrl('flag');

            return response()->json([
                'data' => $country,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on CountriesController@edit',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        try {
            $validated = $request->validated();
            unset($validated['flag']);

            $country->fill($validated);

            if ($request->hasFile('flag') && $request->file('flag')->isValid()) {
                $country->clearMediaCollection('flag');
                $country->addMediaFromRequest('flag')
                    ->toMediaCollection('flag');
            }

            $country->save();

            return redirect()->route('dashboard.countries.index')->with('message', 'Country updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in CountriesController@update: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the country. Please try again.');
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

            return redirect()->back()->with('message', __('Country(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in CountriesController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the country(s). Please try again.');
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

            return redirect()->back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error in CountriesController@toggleActive: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while updating the country status. Please try again.');
        }
    }
}
