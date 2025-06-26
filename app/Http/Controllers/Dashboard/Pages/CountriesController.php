<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\Countries\StoreCountryRequest;
use App\Http\Requests\Dashboard\Pages\Countries\UpdateCountryRequest;

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
        $validated = $request->validated();
        unset($validated['flag']);

        $country = new Country($validated);

        if ($request->hasFile('flag') && $request->file('flag')->isValid()) {
            $country->addMediaFromRequest('flag')
                ->toMediaCollection('flag');
        }

        $country->save();

        return redirect()->route('dashboard.countries.index')->with('message', 'Country created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $country = Country::findOrFail($id);
        $country->flag = $country->getFirstMediaUrl('flag');

        return response()->json([
            'data' => $country,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $country = Country::findOrFail($id);

        $country->flag = $country->getFirstMediaUrl('flag');

        return response()->json([
            'data' => $country,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $validated = $request->validated();
        unset($validated['flag']);

        $country->fill($validated);

        if ($request->hasFile('image') && $request->file('flag')->isValid()) {
            $country->clearMediaCollection('flag');
            $country->addMediaFromRequest('flag')
                ->toMediaCollection('flag');
        }

        $country->save();

        return redirect()->route('dashboard.countries.index')->with('message', 'Country updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:countries,id',
        ]);

        Country::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Country(s) deleted successfully.'));
    }

    // Toggle active status
    public function toggleActive(Request $request, $id)
    {
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
    }
}
