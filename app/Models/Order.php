<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    public function user(){
        return $this->belongTo(User::class);
    }
    
    public function items(){
        return $this->hasMany(OrderItems::class);
    }
}