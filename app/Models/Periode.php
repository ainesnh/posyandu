<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    protected $table = 'periode';
    protected $primaryKey = 'periode_id';

    protected $fillable = [
        'name',
        'startdate',
        'enddate',
    ];

    public function klasifikasiRisiko(): HasMany
    {
        return $this->hasMany(KlasifikasiRisiko::class, 'periode_id', 'periode_id');
    }
}