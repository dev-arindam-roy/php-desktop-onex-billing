<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Procurement;
use App\Models\ProductVariants;
use App\Models\StatusMaster;
use App\Models\User;
use App\Models\Unit;

class ProcurementItems extends Model
{
    use HasFactory;

    protected $table = 'procurement_items';
    protected $primaryKey = 'id';

    public function procurement() {
        return $this->belongsTo(Procurement::class, 'procurement_id', 'id');
    }

    public function product() {
        return $this->belongsTo(ProductVariants::class, 'order_product_id', 'id');
    }

    public function unit() {
        return $this->belongsTo(Unit::class, 'order_product_unit_id', 'id');
    }

    public function progressStatus() {
        return $this->belongsTo(StatusMaster::class, 'progress_status_id', 'id');
    }

    public function associates() {
        return $this->belongsTo(User::class, 'procurement_associate_user_id', 'id');
    }
}
