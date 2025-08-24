<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_department extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_department';
    protected $primaryKey = 'id';
    protected $fillable = ['department_name', 'description', 'status'];
}
