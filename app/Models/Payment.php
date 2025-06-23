<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'intent_id',
        'reference_id',
        'method',
        'amount',
        'status',
        'buyer_id',
        'order_ids',
        'is_test',
        'response_payload',
        'is_verified',
    ];

    protected $casts = [
        'order_ids' => 'array',
        'is_verified' => 'boolean', 
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function getOrdersAttribute()
    {
        $orderIds = json_decode($this->order_ids, true) ?: [];
        return Order::whereIn('id', $orderIds)->get();
    }
}
