<?php

namespace App\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        if ($media->model_type === \App\Models\Website::class) {
            $prefix = "websites/{$media->model_id}";

        } elseif ($media->model_type === \App\Models\EcommerceProductVariant::class) {
            $product = \App\Models\EcommerceProductVariant::find($media->model_id);

            if ($product) {
                $prefix = "websites/{$product->website_id}/products/{$product->id}";
            }

        } elseif ($media->model_type === \App\Models\RestaurantMenuItem::class) {
            $menuItem = \App\Models\RestaurantMenuItem::find($media->model_id);

            if ($menuItem) {
                $prefix = "websites/{$menuItem->website_id}/menuItems/{$menuItem->id}";
            }
        }

        if ($prefix !== '') {
            return $prefix.'/'.$media->getKey();
        }

        return $media->getKey();
    }
}
