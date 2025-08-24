<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_reserve_vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_reserve_vehicle';
    protected $primaryKey = 'id';
    protected $fillable = ['vehicle_id', 'user_id', 'requested_name', 'destination', 'longitude','latitude','driver','start_datetime','end_datetime','reason','reservation_type_id','qrcode','status'];

    public function vehicle()
    {
        return $this->belongsTo(tbl_vehicle::class, 'vehicle_id');
    }

    public function user()
    {
        return $this->belongsTo(tbl_user::class, 'user_id');
    }

    public function reservation_type()
    {
        return $this->belongsTo(tbl_reservation_type::class, 'reservation_type_id');
    }
        
}
