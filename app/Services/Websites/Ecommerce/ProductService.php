<?php

namespace App\Services\Websites\Ecommerce;

use App\Models\Brand;
use App\Models\Category;
use App\Models\EcommerceOrder;
use App\Models\EcommerceProduct;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    protected int $websiteId;

    public function __construct(int $websiteId)
    {
        $this->websiteId = $websiteId;
    }

    /**
     * Get home special products.
     */
    public function getHomeSpecialProducts()
    {
        return $this->getProducts('special');
    }

    /**
     * Get home non-special products.
     */
    public function getHomeProducts()
    {
        return $this->getProducts('home');
    }

    /**
     * Get product by slug.
     */
    public function getProductBySlug(string $slug, $productCartItems)
    {
        $product = $this->loadProducts()->where('slug', $slug)->firstOrFail();

        $cartItems = $productCartItems?->where('product_id', $product->id)->get();

        $productVariantQuantityService = new ProductVariantQuantityService();
        $product = $productVariantQuantityService->updateProductQuantity($product, $cartItems);

        // Add attributes map for hierarchical selection
        // $product->most_used_attribute = $this->getMostUsedAttribute($product);

        $product->variant_display_type = $this->resolveVariantDisplayType($product);
        if ($product->variant_display_type === 'steps') {
            $product->attributes_values_map = $this->getProductAttributesValuesMap($product);
        }
        $product->is_out_of_stock = $this->isProductOutOfStock($product);

        return $product;
    }

    /**
     * Get shop products.
     */
    public function getShopProducts(array $filters)
    {
        return $this->getProducts('shop', $filters);
    }

    /**
     * Get order products.
     */
    public function getOrderProducts(array $filters)
    {
        $paginatedOrders = $this->getFilteredOrders($filters);

        return $paginatedOrders;
    }

    /**
     * Get products by type. Types: home, special, shop.
     * Get products with colors and images loaded.
     */
    protected function getProducts(string $type, array $filters = [])
    {
        $query = $this->loadProducts();

        if ($type === 'special') {
            $query->special()->inHome();
        } else if ($type === 'home') {
            $query->inHome();
        } else if ($type === 'shop') {
            $paginatedProducts = $this->filterProducts($filters, $query);
            $products = $paginatedProducts->through(fn($product) => $this->enrichProduct($product));
            $products->each(fn($product) => $product->is_out_of_stock = $this->isProductOutOfStock($product));

            return $products;
        }

        $products = $query->get()->map(fn($product) => $this->enrichProduct($product));
        $products->each(fn($product) => $product->is_out_of_stock = $this->isProductOutOfStock($product));

        return $products;
    }

    /**
     * Load products with their category, brand, and variants attributes.
     */
    protected function loadProducts()
    {
        return EcommerceProduct::where('website_id', $this->websiteId)->active()
            ->with(['category:id,name,ar_name', 'brand:id,name', 'variants.attributes']);
    }

    /**
     * Enrich product with colors and images.
     */
    protected function enrichProduct(EcommerceProduct $product)
    {
        $product->all_colors = $this->getProductColors($product);
        $product->all_images = $this->getProductImages($product);
        $product->all_variants = $this->getProductVariants($product);

        return $product;
    }

    /**
     * Get all product colors from variants.
     */
    protected function getProductColors(EcommerceProduct $product)
    {
        return $product->variants
            ->flatMap(fn($variant) => $variant->attributes)
            ->filter(fn($attr) => $attr->color_id)
            ->map(fn($attr) => [
                'id' => $attr->color_id,
                'name' => $attr->color_name,
                'name_ar' => $attr->color_name_ar,
                'code' => $attr->color_code,
            ])
            ->unique('id')
            ->values();
    }

    /**
     * Get all product images from variants.
     */
    protected function getProductImages(EcommerceProduct $product)
    {
        return $product->variants
            ->flatMap(fn($variant) => $variant->getMedia('ecommerce_product_images'))
            ->map(fn($media) => $media->getUrl())
            ->take(6)
            ->values();
    }

    /**
     * Get all product variants.
     */
    protected function getProductVariants(EcommerceProduct $product)
    {
        return $product->variants
            ->flatMap(fn($variant) => $variant->attributes)
            ->filter(fn($attr) => $attr->attribute_name !== 'color')
            ->map(fn($attr) => [
                'id' => $attr->attribute_value_id,
                'name' => $attr->attribute_value_value,
                'name_ar' => $attr->attribute_value_value_ar,
            ])
            ->unique('name')
            ->values();
    }

    /**
     * Get the most used attribute.
     */
    protected function getMostUsedAttribute(EcommerceProduct $product)
    {
        $counts = [];

        foreach ($product->variants as $variant) {
            foreach ($variant->attributes as $attribute) {
                $name = $attribute->attribute_name;

                if (!isset($counts[$name])) {
                    $counts[$name] = [
                        'attribute_name' => $name,
                        'attribute_name_ar' => $attribute->attribute_name_ar,
                        'count' => 0,
                    ];
                }

                $counts[$name]['count']++;
            }
        }

        // Sort DESC by count
        return collect($counts)
            ->sortByDesc('count')
            ->values()
            ->first();
    }

    /**
     * Determines how product variants should be displayed: 
     * 'single' for products with only one variant,
     * 'cards' if there are no attributes,
     * 'steps' if there is an attribute common to all variants,
     * otherwise defaults to 'cards'.
     */
    protected function resolveVariantDisplayType(EcommerceProduct $product): string
    {
        $variants = $product->variants;

        if ($variants->count() === 1) {
            return 'single';
        }

        if ($variants->every(fn($v) => $v->attributes->isEmpty())) {
            return 'cards';
        }

        $attributeCount = 0;

        foreach($variants as $variant) {
            if($attributeCount === 0) {
                $attributeCount = $variant->attributes->count();
            } elseif($attributeCount > 0 && $attributeCount !== $variant->attributes->count()) {
                return 'cards';
            }
        }
        return 'steps';
    }

    /**
     * Get hierarchical attributes map showing dependencies between attributes.
     */
    protected function getProductAttributesValuesMap(EcommerceProduct $product)
    {
        $attributes = [];

        foreach ($product->variants as $variant) {
            if ($variant->display_quantity !== 0) {

                foreach ($variant->attributes as $variantAttribute) {
                    $attributeId = $variantAttribute->attribute_id;

                    if (!isset($attributes[$attributeId])) {
                        $attributes[$attributeId] = [
                            'id' => $variantAttribute->attribute_id,
                            'attribute_name' => $variantAttribute->attribute_name,
                            'attribute_name_ar' => $variantAttribute->attribute_name_ar,
                            'values' => [],
                        ];
                    }

                    if ($variantAttribute->attribute_name === 'color') {
                        if (!isset($attributes[$attributeId]['values'][$variantAttribute->color_id])) {
                            $attributes[$attributeId]['values'][$variantAttribute->color_id] = [
                                'id' => $variantAttribute->color_id,
                                'color_name' => $variantAttribute->color_name,
                                'color_name_ar' => $variantAttribute->color_name_ar,
                                'color_code' => $variantAttribute->color_code,
                                'variant_ids' => [$variant->id],
                            ];
                        } else {
                            if (!in_array($variant->id, $attributes[$attributeId]['values'][$variantAttribute->color_id]['variant_ids'])) {
                                $attributes[$attributeId]['values'][$variantAttribute->color_id]['variant_ids'][] = $variant->id;
                            }
                        }
                    } elseif($variantAttribute->attribute_value_id !== null) {
                        if (!isset($attributes[$attributeId]['values'][$variantAttribute->attribute_value_id])) {
                            $attributes[$attributeId]['values'][$variantAttribute->attribute_value_id] = [
                                'id' => $variantAttribute->attribute_value_id,
                                'attribute_value_value' => $variantAttribute->attribute_value ?? $variantAttribute->attribute_value_value,
                                'attribute_value_value_ar' => $variantAttribute->attribute_value ?? $variantAttribute->attribute_value_value_ar,
                                'variant_ids' => [$variant->id],
                            ];
                        } else {
                            if (!in_array($variant->id, $attributes[$attributeId]['values'][$variantAttribute->attribute_value_id]['variant_ids'])) {
                                $attributes[$attributeId]['values'][$variantAttribute->attribute_value_id]['variant_ids'][] = $variant->id;
                            }
                        }
                    } else {
                        if (!isset($attributes[$attributeId]['values'][$variantAttribute->attribute_value])) {
                            $attributes[$attributeId]['values'][$variantAttribute->attribute_value] = [
                                'id' => $variantAttribute->attribute_value,
                                'attribute_value_value' => $variantAttribute->attribute_value ?? $variantAttribute->attribute_value_value,
                                'attribute_value_value_ar' => $variantAttribute->attribute_value ?? $variantAttribute->attribute_value_value_ar,
                                'variant_ids' => [$variant->id],
                            ];
                        } else {
                            if (!in_array($variant->id, $attributes[$attributeId]['values'][$variantAttribute->attribute_value]['variant_ids'])) {
                                $attributes[$attributeId]['values'][$variantAttribute->attribute_value]['variant_ids'][] = $variant->id;
                            }
                        }
                    }
                }
            }
        }

        return array_values($attributes);
    }

    /**
     * Check if a given product is out of stock across all its variants.
     *
     * Returns true if all variants are out of stock;
     * returns false if at least one variant is available or stock is not set.
     */
    protected function isProductOutOfStock(EcommerceProduct $product): bool
    {
        foreach ($product->variants as $variant) {
            $stock = $variant->stock_quantity;
            $reserved = $variant->reserved_quantity;

            if ($stock === null) {
                return false;
            }

            $available = $stock - ($reserved ?? 0);
            if ($available > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Filter products.
     */
    protected function filterProducts(array $filters, $query)
    {
        $perPage  = (int) ($filters['limit'] ?? 12);
        $search   = trim($filters['search'] ?? '');
        $category = $filters['category'] ?? null;
        $brand    = $filters['brand'] ?? null;
        $minPrice = $filters['minPrice'] ?? 0;
        $maxPrice = $filters['maxPrice'] ?? null;
        $special  = $filters['special'] ?? false;
        $onSale   = $filters['onSale'] ?? false;

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $categoryId = Category::active()->where('website_id', $this->websiteId)
                ->where(function ($q) use ($category) {
                    $q->where('name', $category)->orWhere('ar_name', $category);
                })->value('id');;

            if ($categoryId) $query->where('category_id', $categoryId);
        }

        if ($brand) {
            $brandId = Brand::active()->where('website_id', $this->websiteId)
                ->where('name', $brand)->value('id');;

            if ($brandId) $query->where('brand_id', $brandId);
        }

        if ($special) {
            $query->where('is_special', true);
        }

        if ($onSale) {
            $query->where('is_discount', true);
        }

        if ($minPrice !== null && $maxPrice !== null) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        } elseif ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        } elseif ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get filtered orders.
     */
    protected function getFilteredOrders(array $filters)
    {
        $status = $filters['status'] ?? 'pending';
        $search = isset($filters['search']) ? trim($filters['search']) : null;
        $sort_by = $filters['sort_by'] ?? 'date';
        $sort_dir = $filters['sort_dir'] ?? 'desc';

        $query = EcommerceOrder::where('website_id', $this->websiteId)
            ->when(Auth::guard('website')->check(), function ($q) {
                $q->where('website_user_id', Auth::guard('website')->id());
            }, function ($q) {
                $q->where('session_id', session()->getId());
            });

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%");
            });
        }

        if ($sort_by === 'amount') {
            $query->orderBy('total_amount', $sort_dir);
        } else {
            $query->orderBy('created_at', $sort_dir);
        }

        return $query->with(['paymentMethod:id,name', 'items.product:id,name,slug'])->paginate(6);
    }
}
