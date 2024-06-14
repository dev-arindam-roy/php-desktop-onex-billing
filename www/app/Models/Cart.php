<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StatusMaster;
use App\Models\Procurement;
use App\Models\CartItems;
use App\Models\User;
use App\Models\DeliveryTimeline;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart_master';
    protected $primaryKey = 'id';

    public function cartItems() {
        return $this->hasMany(CartItems::class, 'cart_id', 'id');
    }

    public function procurementBtach() {
        return $this->hasOne(Procurement::class, 'id', 'procurement_id');
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function orderStatus() {
        return $this->belongsTo(StatusMaster::class, 'order_status_id', 'id');
    }

    public function deliveryStatus() {
        return $this->belongsTo(StatusMaster::class, 'delivery_status_id', 'id');
    }

    public function deliveryMan() {
        return $this->belongsTo(User::class, 'delivery_man_user_id', 'id');
    }

    public function deliveryTimeline() {
        return $this->hasMany(DeliveryTimeline::class, 'order_id', 'id');
    }
}
