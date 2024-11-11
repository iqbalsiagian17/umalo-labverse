<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 't_orders_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'is_negotiated',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function isNegotiable()
    {
        return $this->product->negotiable;
    }

    public function completedOrderCount()
    {
        return $this->hasMany(OrderItem::class, 'product_id')
                    ->whereHas('t_orders', function($query) {
                        $query->where('status', 'completed');
                    })
                    ->count();
    }
}
