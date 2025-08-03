<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\WebsiteType;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        } catch(\Exception $e) {
            return response()->json()->with(['error' => 'Template not found.'], 404);
        }

        $colors = $request->input('colors');

        $websiteTypeId = $request->input('websiteTypeId');
        $websiteType = WebsiteType::findOrFail($websiteTypeId);
        $websiteTypeName = $websiteType->type;

        $path = "preview/$websiteTypeName/$templateName/pages/home/Home";

        return response()->json([
            'path' => $path,
            'colors' => $colors
        ]);
    }
}