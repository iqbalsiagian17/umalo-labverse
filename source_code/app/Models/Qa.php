<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qa extends Model
{
    use HasFactory;
    protected $table = 'qas';

    protected $fillable = [
        'pertanyaan',
        'jawaban',
    ];
}
