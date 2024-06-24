<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validator->errors()], 400);
        }

        $cart = Cart::where('user_id', $user->id)->with('products')->first();

        // dd($cart->products);
        if (!$cart || $cart->products->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $totalPrice = 0;

        foreach ($cart->products as $product) {
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'shipping_address' => $request->input('shipping_address'),
                'status' => 'pending',
            ]);
            
            foreach ($cart->products as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                ]);
            }
        
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order placement failed'.$e->getMessage()], 500);
        }

        $cart->products()->detach();

        return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);
    }

    
    public function viewOrders(Request $request)
    {
        $user = $request->user();

        $orders = $user->orders()->with('products')->get();

        return response()->json(['orders' => $orders], 200);
    }

    public function viewOrder($id)
    {
        $order = Order::with('product')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json(['order' => $order], 200);
    }

    public function cancelOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order cannot be canceled'], 400);
        }

        $order->status = 'canceled';
        $order->save();

        return response()->json(['message' => 'Order canceled successfully'], 200);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status' => $request->input('status'),
        ]);

        return response()->json(['message' => 'Order status updated', 'order' => $order], 200);
    }

    public function allOrders()
    {
        $orders = Order::with('user', 'products')->get();

        return response()->json(['orders' => $orders], 200);
    }
}
