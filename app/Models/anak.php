<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;


class Anak extends Model
{
    use HasFactory;

    protected $table = 'anak'; 
    protected $primaryKey = 'id_anak';
    public $incrementing = false;
    protected $keyType = 'string'; // ID bertipe string
    protected $fillable = ['id_anak', 'nama_anak', 'tanggal_lahir', 'catatan', 'umur', 'id_wali', 'jenis_kelamin'];

    // Menghubungkan ke model Wali
    public function wali()
    {
        return $this->belongsTo(Wali::class, 'id_wali', 'id_wali');
    }

    // Menghubungkan ke model Wali
    public function jadwal_penitipan()
    {
        return $this->belongsTo(jadwal_penitipan::class, 'id_penitipan', 'id_penitipan');
    }



    protected static function booted()
{
    static::creating(function ($anak) {
        if (empty($anak->id_anak)) {
            $latestId = Anak::max('id_anak');

            // Ambil angka terakhir dari id_anak yang sudah ada
            $number = 1;
            if ($latestId) {
                preg_match('/\d+/', $latestId, $matches);
                if (!empty($matches)) {
                    $number = intval($matches[0]) + 1;
                }
            }

            // Formatkan id_anak dengan huruf 'a' dan angka 2 digit
            $anak->id_anak = 'a' . str_pad($number, 2, '0', STR_PAD_LEFT);
        }
    });

    static::saving(function ($anak) {
        $anak->umur = Carbon::parse($anak->tanggal_lahir)->age;
    });
}

    // Menghitung umur berdasarkan tanggal_lahir
    public static function boot()
    {
        parent::boot();

        static::saving(function ($anak) {
            $anak->umur = Carbon::parse($anak->tanggal_lahir)->age;
        });
    }
}



