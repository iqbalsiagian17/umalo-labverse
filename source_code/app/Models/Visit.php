<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $table = 't_visits';
     
    protected $fillable = [
        'user_id',
        'visited_at',
        'ip_address',
    ];
}
