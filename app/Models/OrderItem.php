<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{ 
     use SoftDeletes;
     protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'price',
        'tax',
        'total_price',
    ];
    protected $dates = ['deleted_at'];

    //mendefinisikan relasi Many-to-One ke tabel order
    //banyak orderitem dimiliki oleh satu order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    //mendefinisikan relasi Many-to-One ke tabel item
    //banyak orderitem dimiliki oleh satu item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }   
}
