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

    public function getProductDetails(int $id): JsonResponse
    {
        $product = Product::where('is_active', true)->findOrFail($id);
        return response()->json([
            'id' => $product->id,
            'title' => $product->title,
            'description' => $product->description,
            'price' => $product->price,
            'image_url' => $product->image_url,
        ]);
    }
    /**
 * Dynamic View Render for AJAX offcanvas basket extraction
 */
public function viewCart(): View
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Returns a clean partial Blade view frame isolate
        return view('cart.partials.drawer-items', compact('cart', 'total'));
    }

/**
 * Handle Item Elimination Sessions Securely
 */
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