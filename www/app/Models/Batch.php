<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseProduct;
use App\Models\Purchase;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batches';
    protected $primaryKey = 'id';

    public function purchaseProducts() {
        return $this->hasMany(PurchaseProduct::class, 'batch_id', 'id');
    }

    public function purchase() {
        return $this->hasMany(Purchase::class, 'batch_id', 'id');
    }
}
