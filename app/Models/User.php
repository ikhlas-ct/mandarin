<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function pelajar(): HasOne
    {
        return $this->hasOne(Pelajar::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPelajar(): bool
    {
        return $this->role === 'pelajar';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
