<?php

namespace App\Http\Controllers\Spotly\Client\Websites\Actions;

use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UiWebsiteController extends Controller
{
    /**
     * Display the ui templates for a website.
     */
    public function index(Website $website)
    {
        Gate::authorize('ui', $website);

        try {
            $website->with([
                'websiteActiveTemplateColor',
                'websiteActiveTemplateColor.template:id,name',
                'websiteActiveTemplateColor.templateColor:id,name',
            ]);

            return $this->inertiaRender('', ['websiteTemplet' => ])
        } catch (\Exception $e) {
            return $this->logResponse('ClientWebsitesController@destroy', $e, 'An error occurred while deleting the website');
        }
    }
}
