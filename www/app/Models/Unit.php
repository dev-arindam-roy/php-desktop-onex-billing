<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'unit_master';
    protected $primaryKey = 'id';

    public function subUnit() {
        return $this->belongsTo(Unit::class, 'child_unit_id', 'id');
    }
}
