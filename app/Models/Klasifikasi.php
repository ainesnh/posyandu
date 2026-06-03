<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel secara eksplisit
    protected $table = 'klasifikasi';

    // 2. Tentukan primary key dari tabel
    protected $primaryKey = 'klasifikasi_id';

    // 3. Daftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'periode_id',
        'rw',
        'rt',
        'rumah_diperiksa',
        'rumah_positif',
        'kontainer_diperiksa',
        'kontainer_positif',
        'transdate',
        'risiko',
        'note',
        'created_by',
        'updated_by'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->user()->user_id;
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->user_id;
            }
        });
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'periode_id');
    }
}