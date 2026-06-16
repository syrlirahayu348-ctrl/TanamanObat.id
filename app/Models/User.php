<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function canManagePlants(): bool
    {
        return in_array($this->role, ['admin', 'editor']);
    }

    public function getRoleBadgeAttribute(): string
    {
        return match($this->role) {
            'admin'  => '<span class="badge badge-admin">Admin</span>',
            'editor' => '<span class="badge badge-editor">Editor</span>',
            default  => '<span class="badge badge-user">User</span>',
        };
    }

    // Relations
    public function plants()
    {
        return $this->hasMany(Plant::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritePlants()
    {
        return $this->belongsToMany(Plant::class, 'favorites')->withTimestamps();
    }
}
