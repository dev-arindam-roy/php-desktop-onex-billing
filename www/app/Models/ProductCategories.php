<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductSubCategories;
use App\Models\Products;

class ProductCategories extends Model
{
    use HasFactory;

    protected $table = 'product_category';
    protected $primaryKey = 'id';

    public function subcategories() {
        return $this->hasMany(ProductSubCategories::class, 'category_id', 'id');
    }

    public function allProducts() {
        return $this->hasMany(Products::class, 'category_id', 'id');
    }

}
