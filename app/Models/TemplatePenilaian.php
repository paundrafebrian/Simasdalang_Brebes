<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplatePenilaian extends Model
{
    use HasFactory;

    protected $table = 'template_penilaians';

    protected $fillable = [
        'nama_template',
        'file',
    ];
}
