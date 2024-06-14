<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProcurementItems;
use App\Models\StatusMaster;
use App\Models\Cart;

class Procurement extends Model
{
    use HasFactory;

    protected $table = 'procurement_master';
    protected $primaryKey = 'id';

    public function cartOrders() {
        return $this->hasMany(Cart::class, 'procurement_id', 'id');
    }

    public function procurementItems() {
        return $this->hasMany(ProcurementItems::class, 'procurement_id', 'id');
    }

    public function progressStatus() {
        return $this->belongsTo(StatusMaster::class, 'progress_status_id', 'id');
    }
}
