<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_reservation_type extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_reservation_type';
    protected $primaryKey = 'id';
    protected $fillable = ['reservation_name', 'description', 'status'];
}
