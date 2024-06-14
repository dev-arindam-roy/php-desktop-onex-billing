<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductSubCategories;
use App\Models\ProductCategories;
use App\Models\ProductVariants;

class Products extends Model
{
    use HasFactory;

    protected $table = 'product_master';
    protected $primaryKey = 'id';

    public function productCategory() {
        return $this->belongsTo(ProductCategories::class, 'category_id', 'id');
    }

    public function productSubCategory() {
        return $this->belongsTo(ProductSubCategories::class, 'subcategory_id', 'id');
    }

    public function allVariants() {
        return $this->hasMany(ProductVariants::class, 'product_id', 'id');
    }
}
