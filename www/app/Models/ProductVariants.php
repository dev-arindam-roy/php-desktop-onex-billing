<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductBundleFree;
use App\Models\Products;
use App\Models\Brand;
use App\Models\Unit;

class ProductVariants extends Model
{
    use HasFactory;

    protected $table = 'product_variants';
    protected $primaryKey = 'id';

    public function baseProduct() {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function productUnit() {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function productBrand() {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function childProducts() {
        return $this->hasMany(ProductBundleFree::class, 'variant_id', 'id');
    }

    public function bundleProducts() {
        return $this->hasMany(ProductBundleFree::class, 'variant_id', 'id')->where('type', 1);
    }

    public function freeProducts() {
        return $this->hasMany(ProductBundleFree::class, 'variant_id', 'id')->where('type', 2);
    }
}
