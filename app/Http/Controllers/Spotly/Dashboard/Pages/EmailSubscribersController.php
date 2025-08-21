<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\EmailSubscriber;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class EmailSubscribersController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = EmailSubscriber::query();

        $columnsSearching = ['email'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return Inertia::render('dashboard/pages/emailSubscribers/EmailSubscribers', [
            'emailSubscribers' => $data,
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $emailsubscriber = EmailSubscriber::findOrFail($id);

            return response()->json([
                'data' => $emailsubscriber,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error on EmailSubscribersController@show',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailSubscriber $emailsubscriber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:email_subscribers,id',
            ]);

            EmailSubscriber::destroy($validated['ids']);

            return redirect()->back()->with('message', __('Subscriber(s) deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error in EmailSubscribersController@destroy: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors('An error occurred while deleting the subscriber(s). Please try again.');
        }
    }
}
