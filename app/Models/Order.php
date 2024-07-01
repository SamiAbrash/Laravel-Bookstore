<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
    ];

    public function user(){
        return $this->belongTo(User::class);
    }
    
    public function orderItems(){
        return $this->hasMany(OrderItems::class);
    }
}
