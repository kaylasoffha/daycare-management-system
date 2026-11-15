<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class jadwal_penjemputan extends Model
{
    use HasFactory;
    
    protected $table = 'jadwal_penjemputan';
    protected $primaryKey = 'id_penitipan';
    public $incrementing = false;

    protected $fillable = [
        'id_penitipan', 
        'id_anak',
        'tanggal',  
        'jam_keluar', 
        'penjemput', 
        'status'
    ];

    // Relasi ke tabel Anak (1 Jadwal dimiliki oleh 1 Anak)
    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak', 'id_anak');
    }
    // Relasi ke tabel Anak (1 Jadwal dimiliki oleh 1 Anak)
    public function jadwal_penitipan()
    {
        return $this->belongsTo(jadwal_penitipan::class, 'id_penitipan', 'id_penitipan');
    }
    

}
