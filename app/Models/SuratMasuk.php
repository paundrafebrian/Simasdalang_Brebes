<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    // Tambahkan 'user_id' ke fillable
    protected $fillable = ['no_surat', 'tanggal', 'asal_pengirim', 'file_pdf', 'user_id', 'status'];

    // (Opsional) Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
