<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_scan_qrcode_reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_scan_qrcode_reservation';
    protected $primaryKey = 'id';
    protected $fillable = ['reserve_vehicle_id', 'workstate', 'logtime'];

    public function reservation()
    {
        return $this->belongsTo(tbl_reserve_vehicle::class, 'reserve_vehicle_id');
    }
}
