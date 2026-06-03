<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;

class Role extends Model
{
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'name',
        'description',
    ];

    /* Relasi ke tabel User  */
    public function users(): HasMany 
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
