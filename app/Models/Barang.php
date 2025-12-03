<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = ['kode_barang','satuan_id','nm_barang','kali_pak','kali_kg'];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class,'satuan_id','id');
    }
}
