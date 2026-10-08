<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Courier extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = true;
    protected $fillable = [
        'nama',
        'no_telp',
        'password'
    ];
}
