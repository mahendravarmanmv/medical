<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\LogisticsEngine;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    // 1. Declare the logistics property explicitly
    protected $logistics;

    /**
     * 2. Inject the LogisticsEngine into the Constructor
     */
    public function __construct(LogisticsEngine $logistics)
    {
        $this->logistics = $logistics;
    }

    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->get();
        $sortOption = $request->query('sort', 'latest');

        // 1. Resolve active location pin code tracking contexts instantly
        $pincode = $this->logistics->resolveCurrentPincode();
        $assignedDealerId = $this->logistics->getAssignedDealerId($pincode);

        // 2. Establish baseline active listing filters query track
        $productQuery = Product::where('is_active', true);

        // RULE 1: Filter inventory to ONLY reveal items tied directly to the matched vendor profile
        if ($assignedDealerId) {
            $productQuery->whereHas('dealers', function ($query) use ($assignedDealerId) {
                $query->where('dealer_id', $assignedDealerId);
            });
        }

        // 3. Category selector tracking routines
        if ($request->has('category_slug') && $request->input('category_slug') !== 'all') {
            $slug = $request->input('category_slug');
            $productQuery->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
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

    public function setLocationToken(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'pincode' => 'required|string|size:6'
        ]);

        session()->put('user_delivery_pincode', $validated['pincode']);

        return response()->json([
            'success' => true,
            'pincode' => $validated['pincode']
        ]);
    }
}