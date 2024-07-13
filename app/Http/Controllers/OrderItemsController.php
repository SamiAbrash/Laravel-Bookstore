<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItems;
use App\Rules\UserOwnsOrder;
use Illuminate\Support\Facades\Log;

class OrderItemsController extends Controller
{
    public function index()
    {
        $orderItems = OrderItems::all();
        if ($orderItems->count() == 0)
        {
            return response()->json([
                'message' => 'No order items found',
            ], 404);
        }
        return response()->json([
            'message' => 'Returning all order items',
            'orderItems' => $orderItems
        ], 200);
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $userId = Auth::id(); // Get authenticated user ID

            // Debug log for userId
            Log::info('User ID:', ['user_id' => $userId]);

            $orderItem = OrderItems::create([
                'book_id' => $validatedData['book_id'],
                'quantity' => $validatedData['quantity'],
                'price' => $validatedData['price'],
                'user_id' => $userId,
            ]);

            return response()->json([
                'message' => 'Order item created successfully',
                'orderItem' => $orderItem,
                'order' => $orderItem->order, // Return associated order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the order item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(OrderItems $orderItem)
    {
        return response()->json([
            'message' => 'Displaying item',
            'orderItem' => $orderItem->load('book'),
        ], 200);
    }

    public function update(Request $request, OrderItems $orderItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        try {
            $orderItem->quantity = $request->quantity;
            $orderItem->price = $request->price;

            if ($orderItem->save()) {
                return response()->json([
                    'message' => 'Order item updated successfully',
                    'orderItem' => $orderItem,
                ], 200);
            } else {
                Log::error('Failed to update order item', ['data' => $request->all()]);
                return response()->json([
                    'message' => 'Failed to update order item'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred while updating order item', ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while updating the order item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(OrderItems $orderItem)
    {
        try {
            if ($orderItem->delete()) {
                return response()->json([
                    'message' => 'Order item deleted successfully',
                ], 200);
            } else {
                Log::error('Failed to delete order item', ['orderItem' => $orderItem]);
                return response()->json([
                    'message' => 'Failed to delete order item'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred while deleting order item', ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while deleting the order item',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
