<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;

    protected $table = 'gudang';
    protected $fillable = ['nm_gudang'];

    public function block()
    {
        return $this->hasMany(Block::class,'gudang_id','id');
    }

}
