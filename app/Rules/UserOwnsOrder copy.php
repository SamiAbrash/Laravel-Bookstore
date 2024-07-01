<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class UserOwnsOrder implements Rule
{
    protected $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function passes($attribute, $value)
    {
        $order = Order::find($this->orderId);
        return $order && $order->user_id == Auth::id();
    }

    public function message()
    {
        return 'The selected order does not belong to the authenticated user.';
    }
}
