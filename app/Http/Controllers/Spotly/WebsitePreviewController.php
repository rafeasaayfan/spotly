<?php

namespace App\Http\Controllers\Spotly;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\WebsiteType;
use Illuminate\Http\Request;

class WebsitePreviewController extends Controller
{
    /**
     * Preview a templates of a website
    */
    public function preview(Request $request) 
    {
        try {
            $templateName = $request->input('templateName');
            Template::where('name', $templateName)->get();

            $colors = $request->input('colors');

            $websiteTypeId = $request->input('websiteTypeId');
            $websiteType = WebsiteType::findOrFail($websiteTypeId);
            $websiteTypeName = $websiteType->type;
    
            $path = "preview/$websiteTypeName/$templateName/pages/home/Home";
    
            return $this->jsonSuccess('', [
                'path' => $path,
                'colors' => $colors
            ]);

        } catch(\Exception $e) {
            return $this->logJsonResponse('WebsitePreviewController@preview', $e, 'Template not found', );
        }
    }
}