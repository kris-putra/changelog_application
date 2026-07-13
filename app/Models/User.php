<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username','email','password','role_id','profile_name'
    ];

    protected $hidden = [
        'password','remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->username) {
                $model->username = strtolower(str_replace(' ', '', $model->username));
            }
        });
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function displayName(): string
    {
        return $this->profile_name ?? $this->username;
    }

    public function isAdmin(): bool
    {
        return $this->role?->slug === 'administrator';
    }

    public function isOperator(): bool
    {
        return $this->role?->slug === 'operator';
    }

    public function isUser(): bool
    {
        return $this->role?->slug === 'user';
    }
}