<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materai extends Model
{
    use HasFactory;

    protected $table = 'materai';

    protected $fillable = ['image'];
}
