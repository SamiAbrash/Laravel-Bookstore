<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItems;

class CartItemsController extends Controller
{
    public function index()
    {
        return CartItems::all(); 
    }

    public function store(CartItems $cartItem, Request $request)
    {
        $cartItem = new CartItems();
        $cartItem->cart_id = $request->cart_id;
        $cartItem->medicine_id = $request->medicine_id;
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'message' => 'Item added successfully',
            'cart-items' => $cartItem,
            'status' => 200,
        ]);
    }

    public function show(CartItems $cartItem)
    {
        return response()->json([
            'message' => 'item found',
            'cart-item' => $cartItem,
            'status' => 200,
        ]);
    }

    public function destroy(CartItems $cartItem)
    {
        $cartItem->delete();
        return response()->json([
            'message' => 'item deleted successfullly',
            'cart-item' => $cartItem,
            'status' => 200,
        ]);
    }
}
