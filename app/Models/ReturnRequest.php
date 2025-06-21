<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = [
        'order_id',
        'buyer_id',
        'reason',
        'evidence_path',
        'status',
        'tracking_code',
        'resolution_type',
        'farmer_response',
        'responded_at',
        'admin_response',
        'farmer_evidence_path',

    ];

    protected $casts = [
        'evidence_path' => 'array',
        'farmer_evidence_path' => 'array',
    ];

    /**
     * The buyer who submitted the return request.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * The order associated with the return request.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
