<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_vehicle';
    protected $primaryKey = 'id';
    protected $fillable = ['vehicle_name', 'vehicle_color', 'model', 'plate_number','capacity','date_acquired','vehicle_image','status'];
}
