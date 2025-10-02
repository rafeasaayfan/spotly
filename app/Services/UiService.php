<?php

namespace App\Services;

use App\Models\TemplateColor;
use App\Models\WebsiteTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UiService
{
    protected int $websiteId;
    protected int | null $template_color_id;
    protected int $template_id;
    protected array $template_images;
    protected bool $is_custom;
    protected array $colors;

    /**
     * Create a new UiService instance.
     *
     * @param int   $websiteId         The website ID to associate the template with.
     * @param int   $template_color_id The template color ID.
     * @param int   $template_id       The template ID.
     * @param array $template_images   The template images.
     * @param bool  $is_custom         Whether the template uses custom colors.
     * @param array $colors            The custom colors (if any).
     */
    public function __construct(int $websiteId, int | null $template_color_id, int $template_id, array $template_images = [], bool $is_custom = false, array $colors = [])
    {
        $this->websiteId = $websiteId;
        $this->template_color_id = $template_color_id;
        $this->template_id = $template_id;
        $this->template_images = $template_images;
        $this->is_custom = $is_custom;
        $this->colors = $colors;
    }

    /**
     * Store a new website template.
     *
     * If custom colors are provided, create a new TemplateColor and use its ID.
     * Otherwise, use the provided template_color_id and template_images.
     *
     * @return void
     */
    public function storeTemplate()
    {
        try {
            $checkDuplicateTemplate = WebsiteTemplate::where('website_id', $this->websiteId)
                ->where('template_id', $this->template_id)->where('template_color_id', $this->template_color_id)
                ->where('is_custom', false)->first();
            if($checkDuplicateTemplate) {
                return 'You already have this template!';
            }
    
            $websiteTemplateCount = WebsiteTemplate::where('website_id', $this->websiteId)->count();
            if ($websiteTemplateCount === 4) {
                return 'You can\'t create more then four templates!';
            }

            return $this->createNewWebsiteTemplate(false);
        } catch (\Exception $e) {
            Log::error('Ui Service Error, Failed to create the website template', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create and activate a new website template.
     *
     * If custom colors are provided, creates a new TemplateColor (with the current user as creator) and uses its ID.
     * Otherwise, uses the provided template_color_id and template_images.
     *
     * @return bool Returns true on success, or an error message string on failure.
     */
    public function createNewWebsiteTemplate(bool $is_active = true)
    {
        try {
            $templateColorId = '';
            $template_images = [''];
    
            if (!empty($this->is_custom) && $this->is_custom && !empty($this->colors)) {
                $templateColors = TemplateColor::create([
                    'created_by' => Auth::id(),
                    ...$this->colors,
                    'is_custom' => $this->is_custom,
                ]);
                $templateColorId = $templateColors->id;
            } else {
                $templateColorId = $this->template_color_id;
                $template_images = $this->template_images;
            }
    
            WebsiteTemplate::create([
                'website_id' => $this->websiteId,
                'template_id' => $this->template_id,
                'template_color_id' => $templateColorId,
                'template_images' => $template_images,
                'is_custom' => $this->is_custom,
                'is_active' => $is_active
            ]);
    
            return true;
        } catch (\Exception $e) {
            Log::error('Ui Service Error, Failed to create the website template', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
