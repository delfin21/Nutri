<?php

// app/Models/StoreCredit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreCredit extends Model
{
    protected $fillable = ['buyer_id', 'amount', 'description'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}

