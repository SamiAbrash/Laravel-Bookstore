<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItems;
use App\Rules\UserOwnsOrder;

class OrderItemsController extends Controller
{
    public function index(OrderItems $orderItem)
    {
        return response()->json($orderItem->load('book'), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'exists:orders,id', new UserOwnsOrder($request->order_id)],
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        $orderItem = new OrderItems();
        $orderItem->order_id = $request->order_id;
        $orderItem->medicine_id = $request->medicine_id;
        $orderItem->quantity = $request->quantity;
        $orderItem->price = $request->price;
        $orderItem->save();

        return response()->json($orderItem, 201);
    }

    public function show(OrderItems $orderItem)
    {
        return response()->json([
            'message' => 'displaying item',
            'order-item' => $orderItem->load('medicine'),
            'status' => 200,
        ]);
    }

    public function update(Request $request, OrderItems $orderItem)
    {
        $orderItem->quantity = $request->quantity;
        $orderItem->price = $request->price;
        $orderItem->save();

        return response()->json([
            'message' => 'updated successfully',
            'order-item' => $orderItem,
            'status' => 200,
        ]);
    }

    public function destroy(OrderItems $orderItem)
    {
        $orderItem->delete();
        return response()->json([
            'message' => 'deleted successfully',
            'status' => 200,
        ]); 
    }
}
