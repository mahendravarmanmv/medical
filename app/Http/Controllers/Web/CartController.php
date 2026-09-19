<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\OrderService;
use App\Services\CheckoutPricingEngine;
use App\Jobs\SendOrderNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CartController extends Controller
{
	protected CheckoutPricingEngine $pricingEngine;
	protected OrderService $orderService;

	public function __construct(
	CheckoutPricingEngine $pricingEngine,
	OrderService $orderService
	) {
	$this->pricingEngine = $pricingEngine;
	$this->orderService = $orderService;
	}

    /**
     * Add a product/package to the active cart.
     */
    /**
 * Add a product/package/warranty to the active cart.
 */
	public function add(Request $request): JsonResponse
	{
	$validated = $request->validate([
		'product_id'  => 'required|integer|exists:products,id',
		'quantity'    => 'required|integer|min:1',
		'package'     => 'nullable|string',
		'warranty_id' => 'nullable|integer|exists:product_warranties,id',
	]);

	$productId = (int) $validated['product_id'];
	$qty = (int) $validated['quantity'];
	$selectedPackage = $validated['package'] ?? null;
	$warrantyId = !empty($validated['warranty_id'])
		? (int) $validated['warranty_id']
		: null;

	/*
	 * -------------------------------------------------------------
	 * PRODUCT
	 * -------------------------------------------------------------
	 */
	$product = Product::where('is_active', true)
		->findOrFail($productId);

	/*
	 * -------------------------------------------------------------
	 * PACKAGE
	 * -------------------------------------------------------------
	 */
	$packageRow = null;

	if (
		$selectedPackage &&
		$selectedPackage !== 'Standard Pack'
	) {
		$packageRow = $product->packages()
			->where('package_name', $selectedPackage)
			->first();

		if (!$packageRow) {
			return response()->json([
				'success' => false,
				'message' => 'The selected package is no longer available.',
			], 422);
		}
	}

	/*
	 * -------------------------------------------------------------
	 * WARRANTY
	 * -------------------------------------------------------------
	 *
	 * Important:
	 * warranty must belong to the selected product.
	 */
	$warrantyRow = null;

	if ($warrantyId) {
		$warrantyRow = $product->warranties()
			->whereKey($warrantyId)
			->first();

		if (!$warrantyRow) {
			return response()->json([
				'success' => false,
				'message' => 'The selected warranty is not valid for this product.',
			], 422);
		}
	}

	/*
	 * -------------------------------------------------------------
	 * SERVER-SIDE PRICE
	 * -------------------------------------------------------------
	 */
	$activePrice = (float) $product->price;

	if ($packageRow) {
		$activePrice = (float) $packageRow->price;
	}

	/*
	 * Warranty price is the final all-inclusive product price
	 * whenever a warranty is selected.
	 */
	if ($warrantyRow) {
		$activePrice = (float) $warrantyRow->price;
	}

	/*
	 * -------------------------------------------------------------
	 * CART
	 * -------------------------------------------------------------
	 *
	 * Warranty must be part of the cart key.
	 *
	 * Otherwise:
	 *
	 * Product + 1 Year
	 * Product + 3 Years
	 *
	 * could incorrectly become the same cart item.
	 */
	$packageKey = (
		$selectedPackage &&
		$selectedPackage !== 'Standard Pack'
	)
		? slugify_cart_key($selectedPackage)
		: 'default';

	$warrantyKey = $warrantyId
		? "warranty-{$warrantyId}"
		: 'no-warranty';

	$cartKey =
		"{$productId}_{$packageKey}_{$warrantyKey}";

	$cart = session()->get('cart', []);

	if (isset($cart[$cartKey])) {

		$newQuantity =
			(int) $cart[$cartKey]['quantity'] + $qty;

		if (
			$product->stock_quantity !== null &&
			$newQuantity > (int) $product->stock_quantity
		) {
			return response()->json([
				'success' => false,
				'message' =>
					"Only {$product->stock_quantity} unit(s) of {$product->title} are available.",
			], 422);
		}

		$cart[$cartKey]['quantity'] = $newQuantity;

	} else {

		if (
			$product->stock_quantity !== null &&
			$qty > (int) $product->stock_quantity
		) {
			return response()->json([
				'success' => false,
				'message' =>
					"Only {$product->stock_quantity} unit(s) of {$product->title} are available.",
			], 422);
		}

		$cart[$cartKey] = [
			'product_id'     => $product->id,
			'title'          => $product->title,

			'package_name'   =>
				$selectedPackage ?? 'Standard Pack',

			'warranty_id'    =>
				$warrantyRow?->id,

			'warranty_years' =>
				$warrantyRow?->warranty_years,

			'warranty_price' =>
				$warrantyRow
					? (float) $warrantyRow->price
					: null,

			'price'          => $activePrice,

			'image_url'      => asset($product->image_url),

			'image'          => asset($product->image_url),

			'quantity'       => $qty,
		];
	}

	session()->put('cart', $cart);

	return response()->json([
		'success'    => true,
		'message'    => 'Product successfully added to your cart!',
		'cart_count' => count($cart),
	]);
	}

    /**
     * Render the shopping cart drawer.
     */
    public function viewCart(): View
    {
        $cart = session()->get('cart', []);

        if (!is_array($cart)) {
            $cart = [];
        }

        $total = 0;

        foreach ($cart as $item) {
            if (
                is_array($item) &&
                isset($item['price'], $item['quantity'])
            ) {
                $total +=
                    (float) $item['price'] *
                    (int) $item['quantity'];
            }
        }

        return view(
            'cart.partials.drawer-items',
            compact('cart', 'total')
        );
    }

    /**
     * Remove an item from the cart.
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_key' => 'required|string',
        ]);

        $cartKey = $validated['cart_key'];
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success'    => true,
            'cart_count' => count($cart),
        ]);
    }

    /**
     * Render checkout.
     */
	public function showCheckoutPage()
	{
	$cart = session()->get('cart', []);

	if (empty($cart)) {
		return redirect()
			->route('home')
			->with(
				'warning',
				'Your basket is currently empty.'
			);
	}

	$invoiceSummary = $this->pricingEngine
		->calculateInvoiceSummary(
			$cart,
			false
		);

	return view(
		'checkout.index',
		[
			'cart' => $cart,

			'subtotal' =>
				$invoiceSummary['unit_price_subtotal'],

			'gst' =>
				$invoiceSummary['gst_tax_amount'],

			'finalPayable' =>
				$invoiceSummary['final_payable_amount'],

			'taxName' =>
				$invoiceSummary['tax_name'],

			'taxRate' =>
				$invoiceSummary['tax_rate'],
				
			'taxEnabled' =>
			    $invoiceSummary['tax_enabled'],
		]
	);
	}

    /**
     * Return checkout calculation summary.
     */
    public function getCheckoutCalculationSummary(
        Request $request
    ): JsonResponse {
        $cart = session()->get('cart', []);

        $wantsInstallation = $request->boolean(
            'installation_required',
            false
        );

        $invoiceSummary =
            $this->pricingEngine->calculateInvoiceSummary(
                $cart,
                $wantsInstallation
            );

        return response()->json([
            'success' => true,

            'summary' => [
                'subtotal' => number_format(
                    $invoiceSummary['unit_price_subtotal'],
                    2
                ),

                'gst' => number_format(
                    $invoiceSummary['gst_tax_amount'],
                    2
                ),
				
				'tax_name' => $invoiceSummary['tax_name'],

				'tax_rate' => $invoiceSummary['tax_rate'],
				
				'tax_enabled' => $invoiceSummary['tax_enabled'],

                'delivery' => number_format(
                    $invoiceSummary['delivery_charges'],
                    2
                ),

                'installation' => number_format(
                    $invoiceSummary['installation_charges'],
                    2
                ),

                'discounts' => number_format(
                    $invoiceSummary['discount_deductions'],
                    2
                ),

                'final_payable' => number_format(
                    $invoiceSummary['final_payable_amount'],
                    2
                ),
            ],
        ]);
    }
	
	/**
 * Place a Cash on Delivery order.
 */
public function placeOrder(Request $request): JsonResponse
{
    /*
     * -------------------------------------------------------------
     * AUTHENTICATION
     * -------------------------------------------------------------
     */
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Please login before placing your order.',
            'redirect' => route('login'),
        ], 401);
    }

    /*
     * -------------------------------------------------------------
     * VALIDATE CHECKOUT FORM
     * -------------------------------------------------------------
     */
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'email' => [
            'required',
            'email',
            'max:255',
        ],

        'phone' => [
            'required',
            'string',
            'max:15',
        ],

        'address' => [
            'required',
            'string',
            'max:1000',
        ],

        'city' => [
            'required',
            'string',
            'max:100',
        ],

        'state' => [
            'required',
            'string',
            'max:100',
        ],

        'pincode' => [
            'required',
            'string',
            'max:10',
        ],

        'payment_method' => [
            'required',
            'string',
            'in:cod',
        ],

        'installation_required' => [
            'nullable',
            'boolean',
        ],
    ]);

    /*
     * -------------------------------------------------------------
     * CART
     * -------------------------------------------------------------
     */
    $cart = session()->get('cart', []);

    if (!is_array($cart) || empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Your cart is empty.',
        ], 422);
    }

    /*
     * -------------------------------------------------------------
     * CREATE ORDER
     * -------------------------------------------------------------
     */
    try {

        $order = $this->orderService->createCodOrder(
            auth()->id(),
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'pincode' => $validated['pincode'],
            ],
            $cart,
            (bool) ($validated['installation_required'] ?? false)
        );
		
		SendOrderNotificationJob::dispatch($order->id);

        return response()->json([
            'success' => true,

            'message' =>
                'Your order has been placed successfully.',

            'order_number' =>
                $order->order_number,

            'redirect' =>
                route(
                    'order.success',
                    $order->order_number
                ),
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {

        throw $e;

    } catch (\Throwable $e) {

        report($e);

        return response()->json([
            'success' => false,
            'message' =>
                'We could not place your order right now. Please try again.',
        ], 500);
    }
}

	/**
	* Increase/decrease cart quantity.
	*/
	public function updateQuantity(
	Request $request
	): JsonResponse {
	$validated = $request->validate([
		'cart_key' => 'required|string',
		'action' => 'required|string|in:increase,decrease',
	]);

	$cartKey = $validated['cart_key'];
	$action = $validated['action'];

	$cart = session()->get('cart', []);

	if (isset($cart[$cartKey])) {

		if ($action === 'increase') {

			$product = Product::find($cart[$cartKey]['product_id']);

			if (!$product || !$product->is_active) {
				return response()->json([
					'success' => false,
					'message' => 'This product is no longer available.',
				], 422);
			}

			$currentQuantity = (int) $cart[$cartKey]['quantity'];
			$newQuantity = $currentQuantity + 1;

			if (
				$product->stock_quantity !== null &&
				$newQuantity > (int) $product->stock_quantity
			) {
				return response()->json([
					'success' => false,
					'message' =>
						"Only {$product->stock_quantity} unit(s) of {$product->title} are available.",
				], 422);
			}

			$cart[$cartKey]['quantity'] = $newQuantity;

		} else {

			$cart[$cartKey]['quantity'] -= 1;

			if ($cart[$cartKey]['quantity'] < 1) {
				unset($cart[$cartKey]);
			}
		}

		session()->put('cart', $cart);
	}

	return response()->json([
		'success'    => true,
		'cart_count' => count($cart),
	]);
	}
	
	/**
	* Display the successful order confirmation page.
	*/
	public function orderSuccess(string $orderNumber): View
	{
	$order = \App\Models\Order::query()
		->where('order_number', $orderNumber)
		->where('user_id', auth()->id())
		->with([
			'items.product',
			'items.dealer',
			'address',
			'payment',
		])
		->firstOrFail();

	return view(
		'order.success',
		compact('order')
	);
	}
}

if (!function_exists('slugify_cart_key')) {
    function slugify_cart_key($text)
    {
        return strtolower(
            trim(
                preg_replace(
                    '/[^A-Za-z0-9-]+/',
                    '-',
                    $text
                ),
                '-'
            )
        );
    }
}