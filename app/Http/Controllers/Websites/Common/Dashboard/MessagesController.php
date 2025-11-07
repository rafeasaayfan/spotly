<?php

namespace App\Http\Controllers\Websites\Common\Dashboard;

use App\Http\Controllers\Websites\BaseController;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Models\WebsiteMessage;

class MessagesController extends BaseController
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WebsiteMessage::where('website_id', $this->website->id);

        $columnsSearching = ['name', 'email'];
        $columnsSelection = ['id', 'name', 'email', 'subject', 'type', 'status', 'message'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection);

        $websiteNameAndLogo = [
            'light_logo' => $this->website->light_logo,
            'dark_logo' => $this->website->dark_logo,
            'name' => $this->website->name,
        ];

        return $this->inertiaRender(
            'pages/messages/Messages',
            [
                'messages' => $data,
                'websiteNameAndLogo' => $websiteNameAndLogo
            ],
            true,
            true
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $message = WebsiteMessage::where('website_id', $this->website->id)->findOrFail($id);

            return $this->jsonSuccess('', [
                'data' => $message,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('MessagesController@show', $e, 'An error when fetching the show page');
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
                'ids.*' => 'integer|exists:website_messages,id,website_id,' . $this->website->id,
            ]);
    
            WebsiteMessage::destroy($validated['ids']);
    
            return $this->backSuccess('Message(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('MessagesController@destroy', $e, 'An error occurred while deleting the Message(s)');
        }
    }

    /**
     * Change the status of the specified resource.
    */
    public function changeStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:new,read,closed',
            ]);
    
            if($validated['status'] === 'new') {
                return $this->backError('You cannot set the status to "new".', 'warning');
            }
            
            $message = WebsiteMessage::where('website_id', $this->website->id)->findOrFail($id);
            $message->update(['status' => $validated['status']]);

            $returnMessage = '';

            if ($validated['status'] === 'read') $returnMessage = 'Message marked as read successfully';
            else if ($validated['status'] === 'closed') $returnMessage = 'Message closed successfully';

            return $this->backSuccess($returnMessage);
        } catch (\Exception $e) {
            return $this->logResponse('MessagesController@changeStatus', $e, 'An error occurred while updating the Message status');
        }
    }
}
