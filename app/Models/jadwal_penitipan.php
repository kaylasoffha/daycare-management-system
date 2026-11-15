<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class jadwal_penitipan extends Model
{
    use HasFactory;
    
    protected $table = 'jadwal_penitipan';
    protected $primaryKey = 'id_penitipan';
    public $incrementing = false;

    protected $fillable = [
        'id_penitipan', 
        'id_pengasuh',
        'id_pendaftaran', 
        'jam_masuk', 
        'jam_keluar', 
        'tanggal',
    ];

    public function pendaftaran_anak()
    {
        return $this->belongsTo(pendaftaran_anak::class, 'id_pendaftaran', 'id_pendaftaran');
    }
    
    public function pengasuh()
    {
        return $this->belongsTo(Pengasuh::class, 'id_pengasuh', 'id_pengasuh');
    }

    // Auto-generate ID Penitipan
    protected static function booted()
    {
        static::creating(function ($jadwal) {
            // Auto-generate id_penitipan (JP001, JP002, ...)
            if (empty($jadwal->id_penitipan)) {
                $latest = self::latest('id_penitipan')->first();
                $number = 1;

                if ($latest) {
                    preg_match('/(\d+)$/', $latest->id_penitipan, $matches);
                    $number = intval($matches[0]) + 1;
                }

                $jadwal->id_penitipan = 'JP' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }

            // **Set default jam_masuk & jam_keluar otomatis**
            $jadwal->jam_masuk = $jadwal->jam_masuk ?? '08:00:00';
            $jadwal->jam_keluar = $jadwal->jam_keluar ?? '16:00:00';
        });
    }
}
