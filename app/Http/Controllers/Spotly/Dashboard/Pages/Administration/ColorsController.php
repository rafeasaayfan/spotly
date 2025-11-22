<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Administration;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Pages\Administration\Colors\StoreColorRequest;
use App\Http\Requests\Dashboard\Pages\Administration\Colors\UpdateColorRequest;

class ColorsController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Color::query();

        $columnsSearching = ['name', 'ar_name', 'code'];
        $columnsSelection = ['id', 'name', 'ar_name', 'code', 'created_at'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection);

        return $this->inertiaRender('dashboard/pages/administration/colors/Colors', ['colors' => $data]);
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
    public function store(StoreColorRequest $request)
    {
        try {
            $validated = $request->validated();

            $color = new Color($validated);
            $color->save();

            return $this->redirectSuccess('dashboard.colors.index', 'Color created successfully');
        } catch (\Exception $e) {
            return $this->logResponse('ColorsController@store', $e, 'An error occurred while creating the Color');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $color = Color::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $color,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('ColorsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $color = Color::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $color,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('ColorsController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColorRequest $request, Color $color)
    {
        try {
            $validated = $request->validated();

            $color->fill($validated);

            $color->save();

            return $this->redirectSuccess('dashboard.colors.index', 'Color updated successfully');
        } catch (\Exception $e) {
            return $this->logResponse('ColorsController@update', $e, 'An error occurred while updating the Color');
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
                'ids.*' => 'integer|exists:colors,id',
            ]);
    
            Color::destroy($validated['ids']);
    
            return $this->backSuccess('Color(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('ColorsController@destroy', $e, 'An error occurred while deleting the Color(s)');
        }
    }
}
