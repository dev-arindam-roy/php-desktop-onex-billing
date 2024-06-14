<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Batch;
use App\Models\ProductVariants;

class BatchProducts extends Model
{
    use HasFactory;

    protected $table = 'batch_products';
    protected $primaryKey = 'id';

    public function batchInfo() {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function productVariantInfo() {
        return $this->belongsTo(ProductVariants::class, 'product_id', 'id');
    }

}
