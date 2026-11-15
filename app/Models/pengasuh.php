<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pengasuh extends Model
{
    use HasFactory;
    protected $table = 'pengasuh';
    protected $primaryKey = 'id_pengasuh';
    public $incrementing = false;
    protected $fillable = ['id_pengasuh', 'nama_pengasuh', 'no_telepon', 'alamat', 'jenis_kelamin'];

    // Relasi ke JadwalPenjemputan (1 Pengasuh bisa memiliki banyak Jadwal)
    public function jadwal_penjemputan()
    {
        return $this->hasMany(jadwal_penjemputan::class, 'id_pengasuh', 'id_pengasuh');
    }

    protected static function booted()
    {
        static::creating(function ($pengasuh) {
            if (empty($pengasuh->id_pengasuh)) {
                $latest = self::latest('id_pengasuh')->first(); // Ambil data terakhir berdasarkan id_pengasuh
                $number = 1;

                if ($latest) {
                    // Ambil angka terakhir dari id_pengasuh yang sudah ada
                    preg_match('/(\d+)$/', $latest->id_pengasuh, $matches);
                    $number = intval($matches[0]) + 1;
                }

                // Formatkan id_pengasuh dengan huruf 'w' dan angka 2 digit (hingga w100)
                $pengasuh->id_pengasuh = 'p' . str_pad($number, 1, '0', STR_PAD_LEFT);
            }
        });

        
    }

    
}
