<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory;

    protected $table = 'cell';
    protected $fillable = ['block_id','nm_cell'];

    public function rak()
    {
        return $this->hasMany(Rak::class,'cell_id','id');
    }

    public function block()
    {
        return $this->belongsTo(Block::class,'block_id','id');
    }

}
