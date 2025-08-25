<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class tbl_chat_messages extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_chat_messages';

    protected $fillable = [
        'from_user_id',
        'message',
        'to_user_id',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
