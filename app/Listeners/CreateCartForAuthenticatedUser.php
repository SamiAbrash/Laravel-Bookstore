<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use App\Models\Cart;

class CreateCartForAuthenticatedUser
{
    public function handle(Authenticated $event)
    {
        $user = $event->user;
        
        // Check if the user already has a cart
        if (!$user->cart) {
            Cart::create(['user_id' => $user->id]);
        }
    }
}
