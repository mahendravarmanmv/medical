<?php

namespace App\Services;

use App\Models\Product;
use App\Models\SystemSetting;

class CheckoutPricingEngine
{
    /**
     * Calculate the definitive cost breakdown for an active cart.
     */
    public function calculateInvoiceSummary(
        array $cartItems,
        bool $wantsInstallation = false
    ): array {
		$subtotal = 0.00;
		$discountAmount = 0.00;

		$taxEnabled = SystemSetting::get(
		'tax',
		'enabled',
		true
		);

		$taxRate = SystemSetting::get(
		'tax',
		'rate',
		18.00
		);

		$standardGstRate = $taxEnabled
		? ((float) $taxRate / 100)
		: 0.00;

        foreach ($cartItems as $item) {
            if (!is_array($item)) {
                continue;
            }

            if (!isset($item['product_id'], $item['price'], $item['quantity'])) {
                continue;
            }

            $product = Product::find($item['product_id']);

            if (!$product) {
                continue;
            }

            $itemPrice = (float) $item['price'];
            $qty = (int) $item['quantity'];

            $subtotal += $itemPrice * $qty;
        }

        // No pincode-based delivery calculation.
        $deliveryCharges = 0.00;

        // Installation remains an optional checkout service.
        $installationCharges = $wantsInstallation
            ? 500.00
            : 0.00;

        $taxableAmount = max(
            0,
            $subtotal - $discountAmount
        );

        $gstTaxAmount = $taxableAmount * $standardGstRate;

        $finalPayableAmount =
            $taxableAmount
            + $gstTaxAmount
            + $deliveryCharges
            + $installationCharges;

        return [
            'unit_price_subtotal' => round($subtotal, 2),

            'gst_tax_amount' => round(
                $gstTaxAmount,
                2
            ),
			
			'tax_name' => SystemSetting::get(
			'tax',
			'name',
			'GST / Healthcare Tax'
			),

			'tax_rate' => (float) $taxRate,
			
			'tax_enabled' => (bool) $taxEnabled,

            'delivery_charges' => round(
                $deliveryCharges,
                2
            ),

            'installation_charges' => round(
                $installationCharges,
                2
            ),

            'discount_deductions' => round(
                $discountAmount,
                2
            ),

            'final_payable_amount' => round(
                $finalPayableAmount,
                2
            ),
        ];
    }
}