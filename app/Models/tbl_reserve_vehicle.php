<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\tbl_reserve_vehicle_passenger;
use App\Models\tbl_vehicle;
use App\Models\User;
use App\Models\tbl_reservation_type;

class tbl_reserve_vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_reserve_vehicle';
    protected $primaryKey = 'id';
    protected $fillable = ['vehicle_id', 'user_id', 'requested_name', 'destination', 'longitude','latitude','driver','driver_user_id','start_datetime','end_datetime','reason','remarks','reservation_type_id','qrcode','status'];

    public function vehicle()
    {
        return $this->belongsTo(tbl_vehicle::class, 'vehicle_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reservation_type()
    {
        return $this->belongsTo(tbl_reservation_type::class, 'reservation_type_id');
    }
    
    public function passengers()
    {
        return $this->hasMany(tbl_reserve_vehicle_passenger::class, 'reserve_vehicle_id');
    }
    
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }
    
    public function scanRecords()
    {
        return $this->hasMany(\App\Models\tbl_scan_qrcode_reservation::class, 'reserve_vehicle_id');
    }
        
}
