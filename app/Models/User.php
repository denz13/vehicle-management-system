<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'position_id',
        'date_of_birth',
        'gender',
        'photo',
        'contact_number',
        'address',
        'active',
    ];

    public function department()
    {
        return $this->belongsTo(tbl_department::class, 'department_id');
    }

    public function position()
    {
        return $this->belongsTo(tbl_position::class, 'position_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that appends to returned entities.
     *
     * @var array
     */
    protected $appends = ['photo_url'];

    /**
     * The getter that return accessible URL for user photo.
     *
     * @var array
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo !== null) {
            return url('storage/' . $this->photo);
        } else {
            return url('media-example/no-image.png');
        }
    }
}
