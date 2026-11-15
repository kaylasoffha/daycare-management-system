<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Wali extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda
    protected $table = 'orangtua_wali'; // Ganti jika nama tabel Anda berbeda

    // Tentukan kolom primer yang digunakan
    protected $primaryKey = 'id_wali'; // Menggunakan 'id_wali' sebagai primary key

    // Tentukan apakah kolom primer menggunakan auto-increment
    public $incrementing = false; // Karena ID Anda berbentuk string, bukan auto-increment integer

    // Tentukan tipe data kolom primer jika bukan integer
    protected $keyType = 'string'; // Tipe data key adalah string, bukan integer

    // Tentukan kolom yang bisa diisi secara massal
    protected $fillable = ['id_wali', 'nama_wali', 'no_telepon', 'email', 'alamat', 'hubungan'];

    // Relasi dengan model Anak
    public function anak()
    {
        return $this->hasMany(Anak::class, 'id_wali', 'id_wali');
    }

    // Event untuk menetapkan id_wali secara otomatis
    protected static function booted()
    {
        static::creating(function ($wali) {
            if (empty($wali->id_wali)) {
                $latest = self::latest('id_wali')->first(); // Ambil data terakhir berdasarkan id_wali
                $number = 1;

                if ($latest) {
                    // Ambil angka terakhir dari id_wali yang sudah ada
                    preg_match('/(\d+)$/', $latest->id_wali, $matches);
                    $number = intval($matches[0]) + 1;
                }

                // Formatkan id_wali dengan huruf 'w' dan angka 2 digit (hingga w100)
                $wali->id_wali = 'w' . str_pad($number, 2, '0', STR_PAD_LEFT);
            }
        });

        
    }

     // Menghitung umur anak
     public function setUmurAttribute()
     {
         $this->attributes['umur'] = Carbon::parse($this->attributes['tanggal_lahir'])->age;
     }
}
