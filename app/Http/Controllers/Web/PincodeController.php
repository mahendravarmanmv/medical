<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PincodeController extends Controller
{
    /**
     * Select the customer's active shopping pincode.
     */
    public function select(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pincode' => [
                'required',
                'digits:6',
            ],
        ]);

        $pincode = Pincode::query()
            ->where('pincode', $validated['pincode'])
            ->where('is_active', true)
            ->first();

        if (!$pincode) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry this pin code is not servicable',
            ], 422);
        }

        /*
         * Store the selected shopping pincode in the session.
         *
         * This is intentionally independent from:
         * - User account
         * - Saved address
         * - Checkout delivery address
         */
        $request->session()->put([
            'selected_pincode_id' => $pincode->id,
            'selected_pincode' => $pincode->pincode,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pincode selected successfully.',
            'pincode' => [
                'id' => $pincode->id,
                'pincode' => $pincode->pincode,
                'city' => $pincode->city,
                'state' => $pincode->state,
            ],
        ]);
    }
}