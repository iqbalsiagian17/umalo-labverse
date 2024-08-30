<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'harga_total',
        'harga_setelah_nego',
        'status',
        'whatsapp_number'
    ];

    // Definisikan relasi dengan OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function statusHistories()
    {
    return $this->hasMany(OrderStatusHistory::class);
    }

    public function seen_by_users()
    {
        return $this->belongsToMany(User::class, 'order_user_seen', 'order_id', 'user_id')
                    ->withTimestamps();
    }

}
