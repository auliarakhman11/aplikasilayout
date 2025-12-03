<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';
    
    protected $fillable = ['kd_gabungan','gudang_id','shift_id','mitra_id','barang_id','block_id','cell_id','rak_id','pallet_id','debit_box','debit_pak','debit_kg','kredit_box','kredit_pak','kredit_kg','checker','ket_checker','user_cehcker_id','status','user_id','tgl','tgl_exp','void'];

    public function barang()
    {
        return $this->belongsTo(Barang::class,'barang_id','id');
    }

    public function block()
    {
        return $this->belongsTo(Block::class,'block_id','id');
    }

    public function cell()
    {
        return $this->belongsTo(Cell::class,'cell_id','id');
    }

    public function rak()
    {
        return $this->belongsTo(Rak::class,'rak_id','id');
    }

    public function pallet()
    {
        return $this->belongsTo(Pallet::class,'pallet_id','id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class,'mitra_id','id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class,'shift_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function userChecker()
    {
        return $this->belongsTo(User::class,'user_cehcker_id','id');
    }

}
