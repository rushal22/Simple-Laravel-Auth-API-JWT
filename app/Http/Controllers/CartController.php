<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'error occurred!!', 'error' => $validator->errors()], 400);
        }

        $user = $request->user();
        $cart = $user->cart;
        if (!$cart) {
            return response()->json(['message' => 'Cart not found!'], 404);
        }

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cartProduct = $cart->products()->where('product_id', $productId)->first();

        if ($cartProduct) {
            $cartProduct->pivot->quantity = $quantity;
            $cartProduct->pivot->save();
            return response()->json(['message' => 'Product quantity updated!'], 200);
        } else {
            return response()->json(['message' => 'Product not found in cart!'], 404);
        }
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

        if (!$cart) {
            return response()->json(['message' => 'Cart not found!'], 404);
        }
            
            // Debugging: Check if the cart and products are being accessed correctly
        if ($cart->products->isEmpty()) {
            return response()->json(['message' => 'No products found in cart!'], 404);
        }
                
        $subtotal = 0;
                
        foreach ($cart->products as $product) {
            $subtotal += $product->price * $product->pivot->quantity;
        }

        $deliverycharge = 100;

        $total = $subtotal + $deliverycharge;

        return response()->json(['cart' => $cart ? $cart->load('products') : null, 'subtotal' => $subtotal, 'deliverycharge'=>$deliverycharge, 'total'=>$total], 200);
    }

    public function getItemCount(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $itemCount = $cart->products()->count();
        } else {
            $itemCount = 0;
        }

        return response()->json(['item_count' => $itemCount]);
    }

}
