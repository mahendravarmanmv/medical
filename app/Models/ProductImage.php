<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    // Explicitly define mass-assignable properties for safety
    protected $fillable = ['product_id', 'image_url', 'sort_order'];

    /**
     * Inverse relationship mapping back to parent product card
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}