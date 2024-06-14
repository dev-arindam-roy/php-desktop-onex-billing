<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StatusMaster;
use App\Models\Cart;
use App\Models\User;

class DeliveryTimeline extends Model
{
    use HasFactory;

    protected $table = 'delivery_timeline';
    protected $primaryKey = 'id';

    public function order() {
        return $this->belongsTo(Cart::class, 'order_id', 'id');
    }

    public function deliveryStatus() {
        return $this->belongsTo(StatusMaster::class, 'progress_status_id', 'id');
    }

    public function deliveryMan() {
        return $this->belongsTo(User::class, 'delivery_man_id', 'id');
    }
}
