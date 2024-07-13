<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Charge;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCharge($amount, $currency, $source, $description)
    {
        return Charge::create([
            'amount' => $amount,
            'currency' => $currency,
            'source' => $source,
            'description' => $description,
        ]);
    }
}
