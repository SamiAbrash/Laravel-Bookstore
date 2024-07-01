<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $cart = new Cart();
        $cart->user_id = $request->user_id;
        $cart->save();

        return response()->json([
            'message' => 'cart created successfully',
            'cart' => $cart,
            'status' => 200,
        ]);
    }

    public function show(Cart $cart)
    {
        return response()->json([
            'message'=> 'cart found',
            'cart' => $cart,
            'status'=> 200, 
        ]);
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return response()->json([
            'message'=> 'cart deleted successfully',
            'status' => 200,
        ]);
    }
}
