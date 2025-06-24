<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmerPayout extends Model
{
    use HasFactory;

protected $fillable = [
    'farmer_id',
    'order_ids',
    'amount',
    'method',           // ✅ This must match exactly
    'account_number',   // ✅ Fix this name (was: payout_account)
    'account_name',
    'released_at',
];

    protected $casts = [
        'order_ids' => 'array',
        'released_at' => 'datetime',
    ];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    // Optional: if you want to easily fetch related orders
    public function orders()
    {
        return Order::whereIn('id', $this->order_ids)->get();
    }
}
