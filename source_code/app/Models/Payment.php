<?php 

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 't_payments';


    protected $fillable = [
        'order_id',
        'status',
        'payment_proof',
        'is_viewed_by_admin',
        'is_viewed_by_customer',
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

    public function statusMessage()
    {
        switch ($this->status) {
            case self::STATUS_UNPAID:
                return 'Belum Dibayar';
            case self::STATUS_PENDING:
                return 'Menunggu Konfirmasi Pembayaran';
            case self::STATUS_PAID:
                return 'Pembayaran Berhasil';
            case self::STATUS_FAILED:
                return 'Pembayaran Ditolak Admin';
            case self::STATUS_REFUNDED:
                return 'Pembayaran Dikembalikan';
            case self::STATUS_PARTIALLY_REFUNDED:
                return 'Sebagian Pembayaran Dikembalikan';
            default:
                return 'Status Tidak Diketahui';
        }
    }
}
