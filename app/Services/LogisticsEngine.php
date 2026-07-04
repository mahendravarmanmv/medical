<?php

namespace App\Services;

use App\Models\Dealer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LogisticsEngine
{
    public const DEFAULT_PINCODE = '500090';

    /**
     * Resolves the active user contextual location token securely.
     */
    /**
     * Resolve the active user pincode with guaranteed string delivery
     */
    public function resolveCurrentPincode(): string
    {
        // 1. Check authenticated user profile data
        if (auth()->check() && !empty(auth()->user()->pincode)) {
            return (string) auth()->user()->pincode;
        }
        
        // 2. Read session container context safely
        $sessionPincode = session()->get('user_delivery_pincode');
        if (!empty($sessionPincode)) {
            return (string) $sessionPincode;
        }

        // 3. Absolute fallback guarantee to prevent TypeErrors if everything else is null/empty
        $defaultPincode = defined('self::DEFAULT_PINCODE') ? self::DEFAULT_PINCODE : '500090';
        return (string) $defaultPincode;
    }

    /**
     * Matches a pincode to delivery rules with memory caching layered on top.
     */
    /**
     * Matches a pincode to delivery rules with pure array data structures for secure cache serialization.
     */
    public function getDeliveryMetrics(string $pincode): array
    {
        // Use standard caching to prevent slow database hits on every single mouse click
        return Cache::remember("delivery_metrics:{$pincode}", 3600, function () use ($pincode) {
            $rule = DB::table('delivery_regions')
                ->where(function ($query) use ($pincode) {
                    $query->where('pincode_pattern', $pincode)
                          ->orWhereRaw('? LIKE pincode_pattern', [$pincode]);
                })
                ->orderBy('priority', 'desc')
                ->first();

            if (!$rule) {
                // Scenario 4 Fallback: Pure array primitives prevent incomplete object serialization crashes
                return [
                    'delivery_hours'    => 5,
                    'has_store_pickup'  => false,
                    'base_delivery_fee' => 150.00
                ];
            }

            return [
                'delivery_hours'    => (int) $rule->delivery_hours,
                'has_store_pickup'  => (bool) $rule->has_store_pickup,
                'base_delivery_fee' => (float) $rule->base_delivery_fee
            ];
        });
    }

    /**
     * Finds the optimized dealer candidate for a targeted location profile.
     */
    public function getAssignedDealerId(string $pincode): ?int
    {
        return Cache::remember("assigned_dealer:{$pincode}", 1800, function () use ($pincode) {
            $dealer = Dealer::whereRaw('? LIKE pincode_coverage_pattern', [$pincode])
                ->where('is_verified', true)
                ->first();

            return $dealer ? $dealer->id : null;
        });
    }
}