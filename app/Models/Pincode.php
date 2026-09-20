<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pincode extends Model
{
    protected $fillable = [
        'pincode',
        'city',
        'state',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

	public function products(): BelongsToMany
	{
	return $this->belongsToMany(
		Product::class,
		'pincode_product'
	)->withTimestamps();
	}
}