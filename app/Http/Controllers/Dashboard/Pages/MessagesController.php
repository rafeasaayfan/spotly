<?php

namespace App\Http\Controllers\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessagesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Message::query();

        $columnsSearching = ['name', 'email'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return Inertia::render('dashboard/pages/messages/Messages', [
            'messages' => $data,
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
        $message = Message::findOrFail($id);

        return response()->json([
            'data' => $message,
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
    public function update(Request $request, Message $message)
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
            'ids.*' => 'integer|exists:messages,id',
        ]);

        Message::destroy($validated['ids']);

        return redirect()->back()->with('message', __('Message(s) deleted successfully.'));
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,read,closed',
        ]);

        $message->update([
            'status' => $validated['status'],
        ]);

        $message = $validated['status'] === 'new'
            ? 'Message marked as new.'
            : 'Message marked as read.';

        if ($validated['status'] === 'closed') $message = 'Message marked as closed.';

        return redirect()->back()->with('message', $message);
    }
}
