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
    public function index(Request $request): View
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->get();
        $sortOption = $request->query('sort', 'latest');
        $productQuery = Product::where('is_active', true);

        $productQuery = match ($sortOption) {
            'low_price'  => $productQuery->orderBy('price', 'asc'),
            'high_price' => $productQuery->orderBy('price', 'desc'),
            default      => $productQuery->orderBy('created_at', 'desc'),
        };

        $products = $productQuery->take(12)->get();
        return view('home.index', compact('categories', 'products', 'sortOption'));
    }

    public function getProductDetails(int $id): \Illuminate\Http\JsonResponse
    {
        // Eager load galleryImages, packages, AND the intermediate dealer pivot structures
        $product = Product::with(['galleryImages', 'packages', 'dealers'])
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

    public function viewCart(): View
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.partials.drawer-items', compact('cart', 'total'));
    }

    public function removeFromCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$validated['product_id']])) {
            unset($cart[$validated['product_id']]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => count($cart)
        ]);
    }
}