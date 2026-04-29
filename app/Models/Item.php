<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

     //mendefinisikan relasi Many-to-One ke tabel categori
    //banyak items dimiliki oleh satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    //mendefinisikan relasi One-to-Many 
    //satu oder memiliki banyak orderitems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }   
    
}
