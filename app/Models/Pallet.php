<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pallet extends Model
{
    use HasFactory;

    protected $table = 'pallet';
    protected $fillable = ['id', 'rak_id', 'nm_pallet'];
}
