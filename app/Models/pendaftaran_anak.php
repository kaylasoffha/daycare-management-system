<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pendaftaran_anak extends Model
{
    use HasFactory;
    protected $table = 'pendaftaran_anak';
    protected $primaryKey = 'id_pendaftaran';
    public $incrementing = false;
    protected $fillable = ['id_pendaftaran', 'id_anak','id_wali', 'tgl_pendaftaran'];

    

    // Menghubungkan ke model Wali
    public function wali()
    {
        return $this->belongsTo(Wali::class, 'id_wali', 'id_wali');
    }

    // Menghubungkan ke model anak
    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak', 'id_anak');
    }

    // Event untuk menetapkan id_wali secara otomatis
    protected static function booted()
    {
        static::creating(function ($pendaftaran_anak) {
            if (empty($pendaftaran_anak->id_pendaftaran)) {
                $latest = self::latest('id_pendaftaran')->first(); // Ambil data terakhir berdasarkan id_wali
                $number = 1;

                if ($latest) {
                    // Ambil angka terakhir dari id_wali yang sudah ada
                    preg_match('/(\d+)$/', $latest->id_pendaftaran, $matches);
                    $number = intval($matches[0]) + 1;
                }

                // Formatkan id_wali dengan huruf 'PA' dan angka 2 digit (hingga w100)
                $pendaftaran_anak->id_pendaftaran = 'PA' . str_pad($number, 2, '0', STR_PAD_LEFT);
            }
        });

        
    }

    

}


