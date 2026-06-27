<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPackage extends Model
{
    protected $fillable = ['product_id', 'package_name', 'price', 'emi_starting_price'];

    /**
     * Inverse relationship mapping back to parent product card
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}