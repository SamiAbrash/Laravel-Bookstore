<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Log;

class OrderItemsObserver
{

    public function creating(OrderItems $orderItem)
    {
        if (!$orderItem->order_id) {
            // If order_id is not set, create a new Order
            // Debug log for orderItem
            Log::info('Creating Order:', ['user_id' => $orderItem->user_id]);

            $order = Order::create([
                'user_id' => $orderItem->user_id,
                'status' => 'pending', // Set initial status
            ]);

            // Associate the Order with the OrderItems
            $orderItem->order_id = $order->id;
        }
    }

    public function created(OrderItems $orderItem)
    {
        $this->updateOrderTotalAmount($orderItem);
    }

    public function updated(OrderItems $orderItem)
    {
        $this->updateOrderTotalAmount($orderItem);
    }

    protected function updateOrderTotalAmount(OrderItems $orderItem)
    {
        $order = $orderItem->order()->first();

        if ($order) {
            $totalAmount = $order->items()->sum(\DB::raw('quantity * price'));
            $order->total_amount = $totalAmount;
            $order->save();
        }
    }
}
