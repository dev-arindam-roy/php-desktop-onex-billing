<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseProduct;
use App\Models\Batch;
use App\Models\User;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchase';
    protected $primaryKey = 'id';

    public function purchaseProducts() {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id', 'id');
    }

    public function batchInfo() {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function vendorInfo() {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }
}
