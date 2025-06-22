<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\EmailSubscriber;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Dashboard\Pages\EmailSubscribers\StoreEmailSubscriberRequest;

class EmailSubscribersController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
    public function store(StoreEmailSubscriberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $emailsubscriber = EmailSubscriber::findOrFail($id);

        return response()->json([
            'data' => $emailsubscriber,
        ]);
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
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:email_subscribers,id',
        ]);

        EmailSubscriber::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Subscriber(s) deleted successfully.'));
    }
}
