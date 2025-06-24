<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'phone',
    'business_name',
    'bio',
    'payout_method',
    'payout_account',
    'payout_name', 
    'payout_bank', 
    'payout_verified', 
    'profile_photo',
    'business_photo',
    'payout_method_secondary',
    'payout_account_secondary',
    'payout_name_secondary',
    'secondary_bank_name', 
    'is_banned',
    'is_permanently_banned',
    'banned_until',
    'ban_reason',
    'street',
    'barangay',
    'city',
    'province',
    'zip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'is_permanently_banned' => 'boolean',
            'banned_until' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    public function getBusinessPhotoUrlAttribute()
    {
        return $this->business_photo
            ? asset('storage/business_photos/' . $this->business_photo)
            : asset('img/avatar.jpg');
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'buyer_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'farmer_id'); 
    }

    public function sendPasswordResetNotification($token)
    {
        if ($this->role === 'admin') {
            $this->notify(new AdminResetPasswordNotification($token));
        } else {
            $this->notify(new ResetPassword($token));
        }
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    public function isCurrentlyBanned(): bool
    {
        if (!$this->is_banned) return false;
        if ($this->is_permanently_banned) return true;
        return $this->banned_until && now()->lt($this->banned_until);
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class, 'buyer_id');
    }
}
