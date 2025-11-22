<?php

namespace App\Http\Controllers\Websites\Ecommerce\Dashboard\Pages;

use App\Http\Controllers\Websites\BaseController;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Products\StoreProductRequest;
use App\Http\Requests\Websites\Ecommerce\Dashboard\Pages\Products\UpdateProductRequest;
use App\Jobs\Websites\Ecommerce\CreateProductSlugJob;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\EcommerceProduct;
use App\Models\EcommerceProductVariant;
use Illuminate\Support\Facades\DB;

class ProductsController extends BaseController
{
    use DataTableTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EcommerceProduct::where('website_id', $this->website->id)->withStockQuantity()->withReservedQuantity();

        $columnsSearching = ['name', 'category.name', 'brand.name', 'price'];
        $columnsSelection = ['id', 'category_id', 'brand_id', 'name', 'price', 'discount_price', 'is_discount', 'is_in_home', 'is_special', 'is_active'];
        $relations = ['category_name', 'brand_name'];

        $data = $this->dataTable($query, $request, $columnsSearching, $columnsSelection, $relations);

        return $this->inertiaRender(
            'pages/products/Products',
            [
                'products' => $data,
                'websiteNameAndLogo' => $this->websiteNameAndLogo()
            ],
            true,
            true
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $categories = Category::active()->where('website_id', $this->website->id)->select(['id', 'name'])->get();
            $brands = Brand::active()->where('website_id', $this->website->id)->select(['id', 'name'])->get();
            $colors = Color::all();

            return $this->jsonSuccess('', [
                'categories' => $categories,
                'brands' => $brands,
                'colors' => $colors,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('ProductsController@create', $e, 'An error when fetching the create page');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $validated = $request->validated();
            $variants = $validated['variants'];
            unset($validated['variants']);

            $product = new EcommerceProduct($validated);
            $product->website_id = $this->website->id;
            $product->slug = uniqid('slug-', true);
            $product->save();

            foreach ($variants as $variant) {
                $product_variant = EcommerceProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $variant['color_id'] ?? null,
                    'stock_quantity' => $variant['stock_quantity']
                ]);

                if (isset($variant['ecommerce_product_image'])) {
                    $product_variant->addMedia($variant['ecommerce_product_image'])
                        ->toMediaCollection('ecommerce_product_image');
                }
            }

            CreateProductSlugJob::dispatch($product);

            return $this->redirectSuccess('dashboard.products.index', 'Product created successfully', forWebsite: true);
        } catch (\Exception $e) {
            return $this->logResponse('ProductsController@store', $e, 'An error occurred while creating the Product');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = EcommerceProduct::where('website_id', $this->website->id)->with(['variants', 'variants.media', 'variants.color'])->findOrFail($id);
            $product = $this->flattenRelationData($query, ['category_name', 'brand_name']);

            return $this->jsonSuccess('', [
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('ProductsController@show', $e, 'An error when fetching the show page');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $product = EcommerceProduct::where('website_id', $this->website->id)->with(['variants', 'variants.media', 'variants.color'])->findOrFail($id);

            $categories = Category::active()->where('website_id', $this->website->id)->select(['id', 'name'])->get();
            $brands = Brand::active()->where('website_id', $this->website->id)->select(['id', 'name'])->get();
            $colors = Color::all();

            return $this->jsonSuccess('', [
                'data' => $product,
                'categories' => $categories,
                'brands' => $brands,
                'colors' => $colors,
            ]);
        } catch (\Exception $e) {
            return $this->logJsonResponse('ProductsController@edit', $e, 'An error when fetching the edit page');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, EcommerceProduct $product)
    {
        if ($product->website_id !== $this->website->id) return;

        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $variants = $validated['variants'] ?? [];
            unset($validated['variants']);

            if ($validated['name'] !== $product->name) {
                CreateProductSlugJob::dispatch($product);
            }

            $data = array_merge([
                'is_in_home' => $product->is_active ? ($validated['is_active'] ? $product->is_in_home : false) : false,
                'is_special' => $product->is_active ? ($validated['is_active'] ? $product->is_special : false) : false,
            ], $validated);

            $product->update($data);

            $this->syncProductVariants($product, $variants);

            DB::commit();

            return $this->redirectSuccess('dashboard.products.index', 'Product updated successfully', forWebsite: true);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->logResponse('ProductsController@update', $e, 'An error occurred while updating the Product');
        }
    }

    /**
     * Handle the create, update, and deletion of product variants.
     *
     * @param EcommerceProduct $product
     * @param array $variants
     * @return void
     */
    protected function syncProductVariants(EcommerceProduct $product, array $variants): void
    {
        // Delete 
        $requestVariantIds = collect($variants)->pluck('id')->filter()->all();
        EcommerceProductVariant::where('product_id', $product->id)->whereNotIn('id', $requestVariantIds)
            ->chunk(10, function ($variants) {
                $variants->each->delete();
            });

        // Create or Update
        foreach ($variants as $variant) {
            $product_variant = EcommerceProductVariant::updateOrCreate([
                'id' => $variant['id'] ?? null,
                'product_id' => $product->id,
            ], [
                'stock_quantity' => $variant['stock_quantity'],
                'color_id' => $variant['color_id'],
            ]);

            if (isset($variant['ecommerce_product_image']) && $variant['ecommerce_product_image'] instanceof \Illuminate\Http\UploadedFile) {
                $product_variant->clearMediaCollection('ecommerce_product_image');
                $product_variant->addMedia($variant['ecommerce_product_image'])
                    ->toMediaCollection('ecommerce_product_image');
            }
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
                'ids.*' => 'integer|exists:ecommerce_products,id,website_id,' . $this->website->id,
            ]);

            EcommerceProduct::destroy($validated['ids']);

            return $this->backSuccess('Product(s) deleted successfully');
        } catch (\Exception $e) {
            return $this->logResponse('ProductsController@destroy', $e, 'An error occurred while deleting the Product(s)');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Request $request, $id)
    {
        try {
            $product = EcommerceProduct::where('website_id', $this->website->id)->findOrFail($id);

            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $product->update([
                'is_active' => $validated['is_active'],
                'is_in_home' => $validated['is_active'] ? $product->is_in_home : false,
                'is_special' => $validated['is_active'] ? $product->is_special : false,
            ]);

            $message = $validated['is_active']
                ? 'Product activated successfully.'
                : 'Product deactivated successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('ProductsController@toggleActive', $e, 'An error occurred while updating the Product status');
        }
    }

    /**
     * Toggle the "is_in_home" status of the specified resource.
     */
    public function toggleIsInHome(Request $request, $id)
    {
        try {
            $product = EcommerceProduct::where('website_id', $this->website->id)->findOrFail($id);

            if(!$product->is_active) return $this->backError('Please activate the product first');

            $validated = $request->validate([
                'is_in_home' => 'required|boolean',
            ]);

            if ($validated['is_in_home'] && $product->is_special) {
                $count = EcommerceProduct::where('website_id', $this->website->id)
                ->active()->special()->inHome()->count();

                if ($count >= 6) return $this->backError('Max 6 special products allowed on home');
            } 

            if ($validated['is_in_home'] && !$product->is_special) {
                $count = EcommerceProduct::where('website_id', $this->website->id)
                ->active()->inHome()->where('is_special', false)->count();

                if ($count >= 8) return $this->backError('Max 8 products allowed on home');
            } 

            $product->update([
                'is_in_home' => $validated['is_in_home'],
            ]);

            $message = $validated['is_in_home']
                ? 'Product added to home successfully.'
                : 'Product removed from home successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('ProductsController@toggleActive', $e, 'An error occurred while updating the Product status');
        }
    }

    /**
     * Toggle the "is_special" status of the specified resource.
     */
    public function toggleIsSpecial(Request $request, $id)
    {
        try {
            $product = EcommerceProduct::where('website_id', $this->website->id)->findOrFail($id);

            if(!$product->is_active) return $this->backError('Please activate the product first');

            $validated = $request->validate([
                'is_special' => 'required|boolean',
            ]);

            if ($validated['is_special'] && $product->is_in_home) {
                $count = EcommerceProduct::where('website_id', $this->website->id)
                ->active()->special()->inHome()->count();

                if ($count >= 6) return $this->backError('Max 6 special products allowed on home');
            } 
            
            if(!$validated['is_special'] && $product->is_in_home) {
                $count = EcommerceProduct::where('website_id', $this->website->id)
                ->active()->inHome()->where('is_special', false)->count();

                if ($count >= 2) {
                    $product->update([
                        'is_in_home' => false,
                    ]);
                }
            }
            
            $product->update([
                'is_special' => $validated['is_special'],
            ]);

            $message = $validated['is_special']
                ? 'Product marked as special successfully.'
                : 'Product unmarked as special successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('ProductsController@toggleActive', $e, 'An error occurred while updating the Product status');
        }
    }

    /**
     * Toggle the "is_discount" status of the specified resource.
     */
    public function toggleIsDiscount(Request $request, $id)
    {
        try {
            $product = EcommerceProduct::where('website_id', $this->website->id)->findOrFail($id);

            $validated = $request->validate([
                'is_discount' => 'required|boolean',
            ]);

            if($product->discount_price <= 0 && $validated['is_discount']) {
                return $this->backError('Please set the discount price first', 'warning');
            }

            $product->update([
                'is_discount' => $validated['is_discount'],
            ]);

            $message = $validated['is_discount'] ? 'Product marked as discount successfully.' : 'Product unmarked as discount successfully.';

            return $this->backSuccess($message);
        } catch (\Exception $e) {
            return $this->logResponse('ProductsController@toggleIsDiscount', $e, 'An error occurred while updating the Product discount status');
        }
    }
}
