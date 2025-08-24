<?php

namespace App\Http\Controllers\Spotly\Dashboard\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FilterRequest;
use App\Models\WebsiteMessage;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;

class WebsiteMessagesController extends Controller
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FilterRequest $request)
    {
        $query = WebsiteMessage::query();

        $columnsSearching = ['website.name', 'email'];

        $data = $this->dataTable($query, $request, $columnsSearching);

        return $this->inertiaRender('dashboard/pages/websiteMessages/WebsiteMessages', ['websiteMessages' => $data]);
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
            $contactmessage = WebsiteMessage::findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $contactmessage,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('WebsiteMessagesController@show', $e, 'An error when fetching the show page');
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
    public function update(Request $request, WebsiteMessage $contactmessage)
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
                'ids.*' => 'integer|exists:website_messages,id',
            ]);

            WebsiteMessage::destroy($validated['ids']);

            return $this->backSuccess('Message(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteMessagesController@destroy', $e, 'An error occurred while deleting the message(s)');
        }
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $websiteMessage = WebsiteMessage::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:new,read,closed',
            ]);

            $websiteMessage->update([
                'status' => $validated['status'],
            ]);

            $message = $validated['status'] === 'new'
                ? 'Message marked as new.'
                : 'Message marked as read.';

            if ($validated['status'] === 'closed') $message = 'Message marked as closed.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('WebsiteMessagesController@changeStatus', $e, 'An error occurred while updating the message status');
        }
    }
}
