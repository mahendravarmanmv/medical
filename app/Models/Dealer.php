<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dealer extends Model
{
    protected $fillable = ['dealer_name', 'city', 'is_verified'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('price')->withTimestamps();
    }
}