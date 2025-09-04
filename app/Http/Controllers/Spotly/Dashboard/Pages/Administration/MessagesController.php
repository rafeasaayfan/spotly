<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\Message;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = Message::query();

        $columnsSearching = ['name', 'email'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return $this->inertiaRender('dashboard/pages/administration/messages/Messages', ['messages' => $data]);
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
            $message = Message::findOrFail($id);


            return $this->jsonSuccess('', [
                'data' => $message,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('MessagesController@show', $e, 'An error when fetching the show page');
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
    public function update(Request $request, Message $message)
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
                'ids.*' => 'integer|exists:messages,id',
            ]);

            Message::destroy($validated['ids']);

            return $this->backSuccess('Message(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('MessagesController@destroy', $e, 'An error occurred while deleting the message(s)');
        }
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $message = Message::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:new,read,closed',
            ]);

            $message->update([
                'status' => $validated['status'],
            ]);

            $msg = $validated['status'] === 'new'
                ? 'Message marked as new.'
                : 'Message marked as read.';

            if ($validated['status'] === 'closed') {
                $msg = 'Message marked as closed.';
            }

            return redirect()->back()->with('message', $msg);
            return $this->backSuccess($msg);
        } catch (\Exception $e) {
            return $this->logResponse('MessagesController@changeStatus', $e, 'An error occurred while updating the message status');
        }
    }
}
