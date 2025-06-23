<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'buyer_id',
        'product_id',
        'farmer_id',
        'quantity',
        'price',
        'total_price',
        'status',
        'payment_status',
        'buyer_phone',
        'buyer_address',
        'buyer_city',
        'buyer_region',
        'buyer_postal_code',
        'conversation_id',
    ];

    protected $attributes = [
        'status' => 'Pending',
    ];

    public function getPaymentStatusLabelAttribute()
    {
        return match ($this->payment_status) {
            'paid'    => '✅ Paid',
            'failed'  => '❌ Failed',
            'pending' => '⏳ Pending',
            default   => '⚠ Unknown',
        };
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function alreadyRated()
    {
        return $this->product
            ? $this->product->reviews
                ->where('user_id', auth()->id())
                ->where('order_id', $this->id)
                ->isNotEmpty()
            : false;
    }

    public function getSummaryForNotification()
    {
        return sprintf(
            'Order %s by %s (%d x ₱%.2f = ₱%.2f)',
            $this->order_code,
            optional($this->buyer)->name ?? 'Unknown Buyer',
            $this->quantity,
            $this->price,
            $this->total_price
        );
    }

    public function rating()
    {
        return $this->hasOne(\App\Models\Rating::class);
    }

    public static function generateStructuredOrderCode($farmer)
    {
        $prefix = 'ORD';
        $provinceCode = strtoupper(substr($farmer->province ?? 'UNK', 0, 3));
        $dateCode = now()->format('ymd');
        $farmerCode = 'F' . $farmer->id;

        $dailyCount = self::where('farmer_id', $farmer->id)
            ->whereDate('created_at', now())
            ->count() + 1;

        $sequence = str_pad($dailyCount, 4, '0', STR_PAD_LEFT);
        return "{$prefix}-{$provinceCode}-{$dateCode}-{$farmerCode}-{$sequence}";
    }

    public function returnRequest()
    {
        return $this->hasOne(ReturnRequest::class);
    }
}
