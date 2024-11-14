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
        'negotiation_total',
        'status',
        'is_negotiated',
        'negotiation_status',
        'tracking_number',
        'shipping_service_id',
        'waiting_approval_at',
        'approved_at',
        'pending_payment_at',
        'confirmed_at',
        'processing_at',
        'shipped_at',
        'delivered_at',
        'negotiation_pending_at',
        'negotiation_approved_at',
        'negotiation_rejected_at',
        'negotiation_in_progress_at',
        'negotiation_finished_at',
        'cancelled_at',
        'cancelled_by_admin_at',
        'cancelled_by_system_at',
        'is_viewed_by_admin',  
        'is_viewed_by_customer',  
        'invoice_number',
    ];

    const STATUS_WAITING_APPROVAL = 'waiting_approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CANCELLED_BY_ADMIN = 'cancelled_by_admin';
    const STATUS_CANCELLED_BY_SYSTEM = 'cancelled_by_system';

    // Negotiation statuses
    const STATUS_NEGOTIATION_PENDING = 'negotiation_pending';
    const STATUS_NEGOTIATION_APPROVED = 'negotiation_approved';
    const STATUS_NEGOTIATION_REJECTED = 'negotiation_rejected';
    const STATUS_NEGOTIATION_IN_PROGRESS = 'negotiation_in_progress';
    const STATUS_NEGOTIATION_FINISHED = 'negotiation_finished';

    public function setStatus($status)
    {
        $this->update([
            'status' => $status,
            $status . '_at' => Carbon::now(),
            'is_viewed_by_customer' => false,
        ]);
    }

    public function startNegotiation()
    {
        $this->update([
            'negotiation_status' => self::STATUS_NEGOTIATION_PENDING,
            'negotiated_pending_at' => Carbon::now(),
            'is_viewed_by_customer' => false,
        ]);
    }

    public function approveNegotiation()
    {
        $this->update([
            'negotiation_status' => self::STATUS_NEGOTIATION_APPROVED,
            'negotiated_approved_at' => Carbon::now(),
            'is_viewed_by_customer' => false,
        ]);
    }

    public function rejectNegotiation()
    {
        $this->update([
            'negotiation_status' => self::STATUS_NEGOTIATION_REJECTED,
            'negotiated_rejected_at' => Carbon::now(),
            'is_viewed_by_customer' => false,
        ]);
    }

    public function finalizeNegotiation($newTotal)
    {
        $this->update([
            'total' => $newTotal,
            'status' => self::STATUS_PENDING_PAYMENT,
            'pending_payment_at' => Carbon::now(),
            'negotiation_status' => self::STATUS_NEGOTIATION_FINISHED,
            'negotiation_completed_at' => Carbon::now(),
            'is_viewed_by_customer' => false,
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
            case self::STATUS_NEGOTIATION_PENDING:
                return 'Menunggu Persetujuan Negoisasi';
            case self::STATUS_NEGOTIATION_APPROVED:
                return 'Negoisasi Disetujui';
            case self::STATUS_NEGOTIATION_REJECTED:
                return 'Negoisasi Ditolak';
            case self::STATUS_NEGOTIATION_IN_PROGRESS:
                return 'Negoisasi Sedang Berlangsung';
            default:
                return 'Status Tidak Diketahui';
        }
    }
}
