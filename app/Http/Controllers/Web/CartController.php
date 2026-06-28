<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\LogisticsEngine;
use App\Services\CheckoutPricingEngine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    protected $logistics;
    protected $pricingEngine;

    /**
     * Constructor injection handling both logistics and pricing engines
     */
    public function __construct(LogisticsEngine $logistics, CheckoutPricingEngine $pricingEngine)
    {
        $this->logistics = $logistics;
        $this->pricingEngine = $pricingEngine;
    }

    /**
     * Synchronize and insert items into the active cart array matrix
     */
    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'package'    => 'nullable|string' // Tracks if a custom variant package card was highlighted
        ]);

        $productId = (int) $validated['product_id'];
        $qty = (int) $validated['quantity'];
        $selectedPackage = $validated['package'] ?? null;

        // Resolve active location to pinpoint competitive dealer price overrides
        $pincode = $this->logistics->resolveCurrentPincode();
        $dealerId = $this->logistics->getAssignedDealerId($pincode);

        $product = Product::where('is_active', true)->findOrFail($productId);

        // Standard Default Base Price
        $activePrice = (float) $product->price;

        // If a customized package variation was chosen instead, resolve its unique price column bounds
        if ($selectedPackage && $selectedPackage !== 'Standard Pack') {
            $packageRow = $product->packages()->where('package_name', $selectedPackage)->first();
            if ($packageRow) {
                $activePrice = (float) $packageRow->price;
            }
        } elseif ($dealerId) {
            // Otherwise, apply localized dealer pricing maps if present
            $dealerPivot = $product->dealers()->where('dealer_id', $dealerId)->first();
            if ($dealerPivot) {
                $activePrice = (float) $dealerPivot->pivot->price;
            }
        }

        $cart = session()->get('cart', []);
        
        // Formulate a clean unique cart matrix item key tracking frame configuration
        $cartKey = $selectedPackage ? "{$productId}_" . slugify_cart_key($selectedPackage) : "{$productId}_default";

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $qty;
        } else {
            $cart[$cartKey] = [
                'product_id'   => $product->id,
                'title'        => $product->title,
                'package_name' => $selectedPackage ?? 'Standard Pack',
                'price'        => $activePrice,
                'image_url'    => asset($product->image_url), // Keeps JavaScript safe
                'image'        => asset($product->image_url), // FIX: Prevents drawer-items.blade.php array key 500 error
                'quantity'     => $qty
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'    => true,
            'message'    => 'Item successfully integrated with session basket arrays.',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Return current session basket items bundle array map configurations
     */
    /**
     * Return current session basket items bundle array map configurations
     */
    public function viewCart(): JsonResponse
    {
        // Force get the cart array context or default to empty
        $cart = session()->get('cart', []);
        
        // Return clear, direct JSON data packets to the JavaScript handler engine
        return response()->json([
            'success' => true,
            'cart'    => $cart
        ]);
    }

    /**
     * Remove an item from the session cart array cleanly
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_key' => 'required|string'
        ]);

        $cartKey = $validated['cart_key'];
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success'    => true,
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Render the full-screen checkout template profile view canvas
     */
    public function showCheckoutPage()
    {
        $cart = session()->get('cart', []);
        
        // Redirect back if user manually hits /checkout directly without selecting any goods first
        if (empty($cart)) {
            return redirect()->route('home')->with('warning', 'Your basket is currently empty.');
        }

        return view('checkout.index');
    }

    /**
     * Streams detailed billing calculations down to the frontend via AJAX.
     */
    public function getCheckoutCalculationSummary(Request $request): JsonResponse
    {
        $cart = session()->get('cart', []);
        $pincode = $this->logistics->resolveCurrentPincode();
        
        // Read checkbox option toggle state seamlessly
        $wantsInstallation = $request->boolean('installation_required', false);

        $invoiceSummary = $this->pricingEngine->calculateInvoiceSummary($cart, $pincode, $wantsInstallation);

        return response()->json([
            'success' => true,
            'pincode' => $pincode,
            'summary' => [
                'subtotal'             => number_format($invoiceSummary['unit_price_subtotal'], 2),
                'gst_tax'              => number_format($invoiceSummary['gst_tax_amount'], 2),
                'delivery'             => number_format($invoiceSummary['delivery_charges'], 2),
                'installation'         => number_format($invoiceSummary['installation_charges'], 2),
                'discounts'            => number_format($invoiceSummary['discount_deductions'], 2),
                'final_payable'        => number_format($invoiceSummary['final_payable_amount'], 2),
                'delivery_time_string' => "Delivered within " . $invoiceSummary['delivery_hours'] . " hours",
                'pickup_eligible'      => $invoiceSummary['has_store_pickup']
            ]
        ]);
    }
}

/**
 * Isolated helper function to ensure no duplicate global function definitions
 */
if (!function_exists('slugify_cart_key')) {
    function slugify_cart_key($text) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
    }
}