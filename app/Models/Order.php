<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'whatsapp',
        'address',
        'total_price',
        'payment_type',
        'transaction_status',
        'midtrans_order_id',
        'status',
        'payment_proof',
        'cancel_reason',
        'received_at',
        'return_reason',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
