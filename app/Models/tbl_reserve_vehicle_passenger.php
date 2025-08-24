<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_reserve_vehicle_passenger extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_reserve_vehicle_passenger';
    protected $primaryKey = 'id';
    protected $fillable = ['reserve_vehicle_id', 'passenger_id', 'passenger_name', 'status'];

    public function reserve_vehicle()
    {
        return $this->belongsTo(tbl_reserve_vehicle::class, 'reserve_vehicle_id');
    }

    public function passenger()
    {
        return $this->belongsTo(tbl_user::class, 'passenger_id');
    }
    
    
}
    