<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductCategories;
use App\Models\Products;

class ProductSubCategories extends Model
{
    use HasFactory;

    protected $table = 'product_subcategory';
    protected $primaryKey = 'id';

    public function category() {
        return $this->belongsTo(ProductCategories::class, 'category_id', 'id');
    }

    public function allProducts() {
        return $this->hasMany(Products::class, 'subcategory_id', 'id');
    }

}
