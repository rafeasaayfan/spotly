<?php

namespace App\Http\Controllers\Spotly\Client\Websites\Actions;

use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ViewWebsiteController extends Controller
{
    /**
     * Display the data of a website.
     */
    public function index(Website $website)
    {
        Gate::authorize('view', $website);

        try {
            $data = Website::with([
                'media',
                'websiteType:id,type',
                'activeWebsiteTemplate.template:id,name',
                'activeWebsiteTemplate.templateColor:id,name'
            ])
            ->findOrFail($website->id);

            return $this->inertiaRender('client/myWebsites/actions/View', ['website' => $data]);
        } catch (\Exception $e) {
            return $this->logResponse('ViewWebsiteController@index', $e, 'An error occurred while loading the website');
        }
    }
}
