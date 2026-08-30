<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->get();
        $sortOption = $request->query('sort', 'latest');

        // 2. Establish baseline active listing filters query track
        $productQuery = Product::where('is_active', true);

        // 3. Category selector tracking routines
		if ($request->filled('category_slug') && $request->input('category_slug') !== 'all') {
		$slug = $request->input('category_slug');

		$category = Category::where('slug', $slug)->first();

		if ($category) {
		$categoryIds = [$category->id];

		// If this is a parent category, include all direct subcategories.
		if (is_null($category->parent_id)) {
			$categoryIds = array_merge(
				$categoryIds,
				$category->subcategories()->pluck('id')->toArray()
			);
		}

		$productQuery->whereIn('category_id', $categoryIds);
		}
		}

        // Sort mappings
        $productQuery = match ($sortOption) {
            'low_price'  => $productQuery->orderBy('price', 'asc'),
            'high_price' => $productQuery->orderBy('price', 'desc'),
            default      => $productQuery->orderBy('created_at', 'desc'),
        };

        $products = $productQuery->get();

        if ($request->ajax()) {
            return view('home.partials.product-grid', compact('products'))->render();
        }

        return view('home.index', compact('categories', 'products', 'sortOption'));
    }

    public function getProductDetails(int $id): \Illuminate\Http\JsonResponse
    {
        // Eager load galleryImages, packages, AND the intermediate dealer pivot structures
        $product = Product::with(['galleryImages', 'packages', 'warranties', 'dealers'])
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'id'          => $product->id,
            'title'       => $product->title,
            'description' => $product->description ?? 'No direct product summary context supplied.',
            'price'       => $product->price,
            'image_url'   => asset($product->image_url),
            'stock'       => $product->stock_quantity ?? 20,
            'packages'    => $product->packages->map(function ($pkg) {
                return [
                    'package_name' => $pkg->package_name,
                    'price'        => $pkg->price,
                    'emi'          => $pkg->emi_starting_price
                ];
            }),
			'warranties' => $product->warranties->map(function ($warranty) {
			return [
			'id' => $warranty->id,
			'warranty_years' => $warranty->warranty_years,
			'price' => $warranty->price,
			];
			}),
            'gallery'     => $product->galleryImages->map(function ($img) {
                return asset($img->image_url);
            }),

            // FIX: Replaces the placeholder array with the real pivot collection
            'dealers'     => $product->dealers->map(function ($dealer) {
                return [
                    'dealer_name' => $dealer->dealer_name,
                    'price'       => $dealer->pivot->price // Pulls the specific price from pivot table
                ];
            })
        ]);
    }
}