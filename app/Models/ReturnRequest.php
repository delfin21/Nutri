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
        'admin_response',
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
