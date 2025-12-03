<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    use HasFactory;

    protected $table = 'rak';
    protected $fillable = ['cell_id','nm_rak'];

    public function pallet()
    {
        return $this->hasMany(Pallet::class,'rak_id','id');
    }


}
