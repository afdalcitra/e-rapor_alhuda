<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'nipy',
    'nama_lengkap',
    'email',
    'jabatan',
    'role',
    'status',
    'password'
])]
#[Hidden([
    'password',
    'remember_token'
])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isYayasan(): bool
    {
        return $this->role === 'yayasan';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function nilaiGurus()
    {
        return $this->hasMany(NilaiGuru::class, 'guru_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }
}