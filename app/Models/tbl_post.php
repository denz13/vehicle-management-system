<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_post';
    
    protected $primaryKey = 'id';
    
    public $timestamps = true;

    protected $fillable = [
        'announcement_title',
        'description',
        'status',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    // Accessor for formatted status
    public function getStatusTextAttribute()
    {
        return ucfirst($this->status);
    }
    
    // Scope for active posts
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    // Scope for inactive posts
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
