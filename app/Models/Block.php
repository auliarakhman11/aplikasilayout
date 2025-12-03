<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $table = 'block';
    protected $fillable = ['gudang_id','nm_block'];

    public function cell()
    {
        return $this->hasMany(Cell::class,'block_id','id')->orderBy('id','DESC');
    }


}
