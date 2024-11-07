<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Order extends Model
{
    use HasFactory;

    protected $table = 't_orders';


    protected $fillable = [
        'user_id',
        'total',
        'status',
        'is_negotiated',
        'tracking_number',
        'shipping_service_id',
        'waiting_approval_at',
        'approved_at',
        'pending_payment_at',
        'confirmed_at',
        'processing_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancelled_by_admin_at',
        'cancelled_by_system_at',
        'is_viewed_by_admin',  
        'is_viewed_by_customer',  
        'invoice_number',
    ];

    const STATUS_WAITING_APPROVAL = 'waiting_approval';
    const STATUS_APPROVED = 'approved'; // Status approved yang ditambahkan

    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CANCELLED_BY_ADMIN = 'cancelled_by_admin';
    const STATUS_CANCELLED_BY_SYSTEM = 'cancelled_by_system';

    public function setStatus($status)
    {
        $this->update([
            'status' => $status,
            $status . '_at' => Carbon::now(),
            'is_viewed_by_customer' => false, // Atur ulang ke false setiap kali status berubah
        ]);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function shippingService()
    {
        return $this->belongsTo(ShippingService::class, 'shipping_service_id');
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, OrderItem::class, 'order_id', 'product_id', 'id', 'product_id');
    }

    public function statusMessage()
    {
        switch ($this->status) {
            case self::STATUS_WAITING_APPROVAL:
                return 'Menunggu Persetujuan';
            case self::STATUS_APPROVED:
                return 'Disetujui';
            case self::STATUS_PENDING_PAYMENT:
                return 'Menunggu Pembayaran';
            case self::STATUS_CONFIRMED:
                return 'Dikonfirmasi';
            case self::STATUS_PROCESSING:
                return 'Sedang Diproses';
            case self::STATUS_SHIPPED:
                return 'Dikirim';
            case self::STATUS_DELIVERED:
                return 'Diterima';
            case self::STATUS_CANCELLED:
                return 'Dibatalkan';
            case self::STATUS_CANCELLED_BY_ADMIN:
                return 'Dibatalkan oleh Admin';
            case self::STATUS_CANCELLED_BY_SYSTEM:
                return 'Dibatalkan oleh Sistem';
            default:
                return 'Status Tidak Diketahui';
        }
    }

    
}
