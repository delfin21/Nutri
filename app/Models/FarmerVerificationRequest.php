<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerVerificationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_path',
        'status',
        'submitted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function farmer()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
