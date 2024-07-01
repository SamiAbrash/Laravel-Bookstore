<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy("created_at", "desc");
        return response()->json([
            'message' => 'displaying order',
            'orders' => $orders, 
            'status' => 200
        ]);
    }

    public function store(Request $request)
    {
        $order = new Order();
        $order->user_id = Auth::id();
        $order->total_price = $request->total_price;
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'success',
            'order' => $order,
            'status' => 200,
        ]);
    }

    public function show(Order $order)
    {
        return response()->json([
            'message' => 'success',
            'order'=> $order,
            'status'=> 200
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $order->total_price = $request->total_price;
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'updated successfully',
            'order' => $order,
            'status' => 200,
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json([
            'message' => 'deleted successfully'
        ]);
    }
}
