<?php 

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 't_ord_payments';


    protected $fillable = [
        'order_id',
        'status',
        'payment_proof',
        'is_viewed',
    ];

        const STATUS_UNPAID = 'unpaid';
        const STATUS_PENDING = 'pending';
        const STATUS_PAID = 'paid';
        const STATUS_FAILED = 'failed';
        const STATUS_REFUNDED = 'refunded';
        const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function setStatus($status)
    {
        $this->update([
            'status' => $status,
            "{$status}_at" => Carbon::now(),
        ]);
    }
}
