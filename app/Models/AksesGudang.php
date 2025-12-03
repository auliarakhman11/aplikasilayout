<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesGudang extends Model
{
    use HasFactory;

    protected $table = 'akses_gudang';
    protected $fillable = ['gudang_id','user_id'];

}
