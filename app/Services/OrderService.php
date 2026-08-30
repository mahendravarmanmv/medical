<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected CheckoutPricingEngine $pricingEngine
    ) {
    }

    /**
     * Create a complete Cash on Delivery order from the
     * customer's current session cart.
     *
     * All product/package/warranty prices are resolved
     * server-side.
     */
    public function createCodOrder(
        int $userId,
        array $addressData,
        array $cart,
        bool $wantsInstallation = false
    ): Order {
        if (empty($cart)) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        return DB::transaction(function () use (
            $userId,
            $addressData,
            $cart,
            $wantsInstallation
        ) {

            /*
             * -------------------------------------------------------------
             * 1. VALIDATE CART AND PREPARE TRUSTED ITEMS
             * -------------------------------------------------------------
             */
            $preparedItems = $this->validateAndPrepareItems($cart);

            if (empty($preparedItems)) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart does not contain any valid products.',
                ]);
            }

            /*
             * -------------------------------------------------------------
             * 2. BUILD TRUSTED PRICING CART
             * -------------------------------------------------------------
             *
             * CheckoutPricingEngine expects:
             *
             * product_id
             * price
             * quantity
             *
             * Prices here come from the database, never from
             * the client request.
             */
            $pricingCart = [];

            foreach ($preparedItems as $item) {
                $pricingCart[] = [
                    'product_id' => $item['product']->id,
                    'price'      => $item['unit_price'],
                    'quantity'   => $item['quantity'],
                ];
            }

            /*
             * -------------------------------------------------------------
             * 3. CALCULATE DEFINITIVE INVOICE
             * -------------------------------------------------------------
             */
            $pricing = $this->pricingEngine->calculateInvoiceSummary(
                $pricingCart,
                $wantsInstallation
            );

            /*
             * -------------------------------------------------------------
             * 4. CREATE ORDER
             * -------------------------------------------------------------
             */
            $order = Order::create([
                'user_id' => $userId,

                'order_number' => $this->generateOrderNumber(),

                'status' => 'pending',

                'subtotal' => $pricing['unit_price_subtotal'],

                'gst_amount' => $pricing['gst_tax_amount'],

                'delivery_charge' => $pricing['delivery_charges'],

                'installation_charges' =>
                    $pricing['installation_charges'],

                'discount_amount' =>
                    $pricing['discount_deductions'],

                'total_amount' =>
                    $pricing['final_payable_amount'],

                'payment_method' => 'cod',

                'payment_status' => 'pending',
            ]);

            /*
             * -------------------------------------------------------------
             * 5. CREATE ORDER ITEMS
             * -------------------------------------------------------------
             */
            foreach ($preparedItems as $item) {

                $product = $item['product'];
                $package = $item['package'];
                $warranty = $item['warranty'];

                $unitPrice = $item['unit_price'];
                $quantity = $item['quantity'];

                $lineTotal = round(
                    $unitPrice * $quantity,
                    2
                );

                OrderItem::create([
                    'order_id' => $order->id,

                    'product_id' => $product->id,

                    /*
                     * Dealer is intentionally nullable.
                     *
                     * We are NOT selecting a dealer based on pincode.
                     */
                    'dealer_id' => $item['dealer_id'],

                    /*
                     * Historical product snapshot.
                     */
                    'product_name' => $product->title,

                    'package_name' =>
                        $package?->package_name
                        ?? 'Standard Pack',

                    /*
                     * Historical warranty snapshot.
                     */
                    'warranty_years' =>
                        $warranty?->warranty_years,

                    'warranty_price' =>
                        $warranty
                            ? (float) $warranty->price
                            : null,

                    'unit_price' => $unitPrice,

                    'quantity' => $quantity,

                    'line_total' => $lineTotal,
                ]);
            }

            /*
             * -------------------------------------------------------------
             * 6. CREATE DELIVERY ADDRESS SNAPSHOT
             * -------------------------------------------------------------
             */
            OrderAddress::create([
                'order_id' => $order->id,

                'name' => $addressData['name'],

                'phone' => $addressData['phone'],

                'email' => $addressData['email'] ?? null,

                'address' => $addressData['address'],

                'city' => $addressData['city'],

                'state' => $addressData['state'],

                'pincode' => $addressData['pincode'],
            ]);

            /*
             * -------------------------------------------------------------
             * 7. CREATE COD PAYMENT
             * -------------------------------------------------------------
             */
            OrderPayment::create([
                'order_id' => $order->id,

                'payment_method' => 'cod',

                'payment_status' => 'pending',

                'amount' => $pricing['final_payable_amount'],

                'transaction_reference' => null,

                'notes' => 'Cash on Delivery',

                'paid_at' => null,
            ]);

            /*
             * -------------------------------------------------------------
             * 8. CREATE INITIAL STATUS HISTORY
             * -------------------------------------------------------------
             */
            OrderStatusHistory::create([
                'order_id' => $order->id,

                'status' => 'pending',

                'notes' =>
                    'Order placed successfully using Cash on Delivery.',
            ]);

            /*
             * -------------------------------------------------------------
             * 9. CLEAR CART ONLY AFTER SUCCESSFUL ORDER CREATION
             * -------------------------------------------------------------
             */
            session()->forget('cart');

            /*
             * -------------------------------------------------------------
             * 10. RETURN COMPLETE ORDER
             * -------------------------------------------------------------
             */
            return $order->fresh([
                'user',
                'items',
                'address',
                'payment',
                'statusHistories',
            ]);
        });
    }


    /**
     * Validate every cart item and resolve all prices
     * from the database.
     */
    protected function validateAndPrepareItems(
        array $cart
    ): array {

        $preparedItems = [];

        foreach ($cart as $cartKey => $cartItem) {

            if (!is_array($cartItem)) {
                continue;
            }

            $productId =
                isset($cartItem['product_id'])
                    ? (int) $cartItem['product_id']
                    : 0;

            $quantity =
                isset($cartItem['quantity'])
                    ? (int) $cartItem['quantity']
                    : 0;

            if ($productId <= 0 || $quantity < 1) {
                throw ValidationException::withMessages([
                    'cart' =>
                        'One of the items in your cart is invalid.',
                ]);
            }

            /*
             * Lock product row while validating stock.
             */
            $product = Product::query()
                ->whereKey($productId)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$product) {
                throw ValidationException::withMessages([
                    'cart' =>
                        'One of the products in your cart is no longer available.',
                ]);
            }

            /*
             * ---------------------------------------------------------
             * STOCK VALIDATION
             * ---------------------------------------------------------
             */
            if (
                $quantity > (int) $product->stock_quantity
            ) {
                throw ValidationException::withMessages([
                    'cart' =>
                        "Only {$product->stock_quantity} unit(s) of {$product->title} are available.",
                ]);
            }

            /*
             * ---------------------------------------------------------
             * PACKAGE VALIDATION
             * ---------------------------------------------------------
             */
            $packageName =
                $cartItem['package_name']
                ?? 'Standard Pack';

            $package = null;

            if (
                $packageName &&
                $packageName !== 'Standard Pack'
            ) {

                $package = $product->packages()
                    ->where(
                        'package_name',
                        $packageName
                    )
                    ->first();

                if (!$package) {
                    throw ValidationException::withMessages([
                        'cart' =>
                            "The selected package for {$product->title} is no longer available.",
                    ]);
                }
            }

            /*
             * ---------------------------------------------------------
             * WARRANTY VALIDATION
             * ---------------------------------------------------------
             */
            $warrantyId =
                !empty($cartItem['warranty_id'])
                    ? (int) $cartItem['warranty_id']
                    : null;

            $warranty = null;

            if ($warrantyId) {

                $warranty = $product->warranties()
                    ->whereKey($warrantyId)
                    ->first();

                if (!$warranty) {
                    throw ValidationException::withMessages([
                        'cart' =>
                            "The selected warranty for {$product->title} is no longer available.",
                    ]);
                }
            }

            /*
             * ---------------------------------------------------------
             * SERVER-SIDE PRICE RESOLUTION
             * ---------------------------------------------------------
             */
            $unitPrice = (float) $product->price;

            /*
             * Package price.
             */
            if ($package) {
                $unitPrice = (float) $package->price;
            }

            /*
             * Warranty price becomes the final all-inclusive
             * product price when warranty is selected.
             */
            if ($warranty) {
                $unitPrice = (float) $warranty->price;
            }

            /*
             * ---------------------------------------------------------
             * DEALER
             * ---------------------------------------------------------
             *
             * Dealer functionality remains available.
             *
             * We do NOT derive dealer from pincode.
             */
            $dealerId =
                !empty($cartItem['dealer_id'])
                    ? (int) $cartItem['dealer_id']
                    : null;

            if ($dealerId) {

                $dealerExists = $product->dealers()
                    ->where('dealers.id', $dealerId)
                    ->exists();

                if (!$dealerExists) {
                    throw ValidationException::withMessages([
                        'cart' =>
                            "The selected dealer is not associated with {$product->title}.",
                    ]);
                }
            }

            $preparedItems[] = [
                'cart_key' => $cartKey,

                'product' => $product,

                'package' => $package,

                'warranty' => $warranty,

                'dealer_id' => $dealerId,

                'quantity' => $quantity,

                'unit_price' => $unitPrice,
            ];
        }

        return $preparedItems;
    }


    /**
     * Generate a unique human-readable order number.
     */
    protected function generateOrderNumber(): string
    {
        do {

            $orderNumber =
                'SW-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(6));

        } while (
            Order::where(
                'order_number',
                $orderNumber
            )->exists()
        );

        return $orderNumber;
    }
}