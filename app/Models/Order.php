<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order_code',
        'user_id',
        'subtotal',
        'tax',
        'grand_total',
        'status',
        'table_number',
        'payment_method',
        'note',
        'created_at', 
        'updated_at'
    ];
    protected $dates = ['deleted_at'];

    //mendefinisikan relasi Many-to-One ke tabel user
    //banyak order dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    //mendefinisikan relasi One-to-Many 
    //satu oder memiliki banyak orderitems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
