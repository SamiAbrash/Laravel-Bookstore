<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handlePayment(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|max:3',
            'source' => 'required|string', // This will be the token from the frontend
            'description' => 'nullable|string',
        ]);

        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }        

        try {
            $charge = $this->paymentService->createCharge(
                $validatedData['amount'] * 100, // Convert to cents if needed
                $validatedData['currency'],
                $validatedData['source'],
                $validatedData['description'] ?? 'Book Purchase'
            );

            // Save payment details to the database
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'order_id' => $validatedData['order_id'],
                'amount' => $validatedData['amount'],
                'currency' => $validatedData['currency'],
                'payment_method' => 'stripe',
                'payment_status' => $charge->status,
                'transaction_id' => $charge->id,
                'description' => $validatedData['description'] ?? 'Book Purchase',
            ]);

            return response()->json(['status' => 'success', 'payment' => $payment]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
