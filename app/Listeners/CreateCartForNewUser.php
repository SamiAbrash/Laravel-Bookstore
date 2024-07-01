<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Cart;

class CreateCartForNewUser
{
    public function handle(Registered $event)
    {
        $user = $event->user;
        Cart::create(['user_id' => $user->id]);
    }
}
