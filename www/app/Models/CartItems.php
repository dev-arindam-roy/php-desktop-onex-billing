<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariants;
use App\Models\Unit;

class CartItems extends Model
{
    use HasFactory;

    protected $table = 'cart_products';
    protected $primaryKey = 'id';

    public function productVariant() {
        return $this->belongsTo(ProductVariants::class, 'product_id', 'id');
    }

    public function unit() {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
