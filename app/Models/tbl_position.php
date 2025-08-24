<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_position extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'tbl_position';
    protected $primaryKey = 'id';
    protected $fillable = ['position_name', 'description', 'status'];
}
