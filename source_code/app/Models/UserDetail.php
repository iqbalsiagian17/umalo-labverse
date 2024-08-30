<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'user_detail';

    protected $fillable = [
        'user_id',
        'no_telepone',
        'perusahaan',
        'lahir',
        'jenis_kelamin',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
