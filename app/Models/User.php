<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'isactive',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'password' => 'hashed',
            'isactive' => 'boolean',
        ];
    }

    public function role(): BelongsTo 
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
    
    public function isAdmin(): bool 
    {
        return $this->role && $this->role->name === 'admin';
    }
}