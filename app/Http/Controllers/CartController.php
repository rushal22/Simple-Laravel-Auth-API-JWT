<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            return response()->json(['message'=>'error occured!!', 'error'=>$validator->errors()], 400);
        }
    
        $user = $request->user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        
        $cartProduct = $cart->products()->where('product_id', $productId)->first();

        if ($cartProduct) {
            $cartProduct->pivot->quantity += $quantity;
            $cartProduct->pivot->save();
        } else {
            $cart->products()->attach($productId, ['quantity' => $quantity]);
        }

        return response()->json(['message' => 'Product added to cart!'], 200);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();
        $cart = $user->cart;

        if ($cart) {
            $productId = $request->input('product_id');
            $cart->products()->detach($productId);
        }

        return response()->json(['cart' => $cart ? $cart->load('products') : null], 200);
    }

    public function viewCart(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart;

        return response()->json(['cart' => $cart ? $cart->load('products') : null], 200);
    }
}
