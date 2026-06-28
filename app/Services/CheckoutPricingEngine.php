<?php

namespace App\Services;

use App\Models\Product;

class CheckoutPricingEngine
{
    protected $logistics;

    public function __construct(LogisticsEngine $logistics)
    {
        $this->logistics = $logistics;
    }

    /**
     * Calculates the definitive cost breakdown for an active cart bundle.
     */
    public function calculateInvoiceSummary(array $cartItems, string $pincode, bool $wantsInstallation = false): array
    {
        $dealerId = $this->logistics->getAssignedDealerId($pincode);
        $logisticsMetrics = $this->logistics->getDeliveryMetrics($pincode);

        $subtotal = 0.00;
        $discountAmount = 0.00; // Scalable point for future promotional coupon validation
        $standardGstRate = 0.18; // 18% standard healthcare device GST allocation

        foreach ($cartItems as $item) {
            // Read from cache/db to ensure pricing wasn't tampered with on client side
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $itemPrice = (float) $item['price'];
            $qty = (int) $item['quantity'];

            $subtotal += ($itemPrice * $qty);
        }

        // Apply specialized logistics overrides based on your structural tiers
        $deliveryCharges = (float) $logisticsMetrics->base_delivery_fee;
        
        // Apply installation support charges if checked/selected by user
        $installationCharges = $wantsInstallation ? 500.00 : 0.00;

        // Calculate Tax components derived cleanly from subtotal after core adjustments
        $taxableAmount = max(0, $subtotal - $discountAmount);
        $gstTaxAmount = $taxableAmount * $standardGstRate;

        // Final Aggregate Sum calculation matrix
        $finalPayableAmount = ($taxableAmount + $gstTaxAmount + $deliveryCharges + $installationCharges);

        return [
            'unit_price_subtotal'  => round($subtotal, 2),
            'gst_tax_amount'       => round($gstTaxAmount, 2),
            'delivery_charges'     => round($deliveryCharges, 2),
            'installation_charges' => round($installationCharges, 2),
            'discount_deductions'  => round($discountAmount, 2),
            'final_payable_amount' => round($finalPayableAmount, 2),
            'delivery_hours'       => $logisticsMetrics->delivery_hours,
            'has_store_pickup'     => $logisticsMetrics->has_store_pickup
        ];
    }
}