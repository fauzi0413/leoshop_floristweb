<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return response()->json(['error' => 'You must login first'], 401);
        }

        // Ambil data cart dari session
        $cart = session('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        try {
            DB::beginTransaction();

            // ✅ Buat data order baru
            $order = Order::create([
                'user_id'           => Auth::id(),
                'name'              => Auth::user()->name ?? 'Guest',
                'whatsapp'          => $request->input('whatsapp', '-'),
                'address'           => $request->input('address', '-'),
                'total_price'       => $total,
                'payment_type'      => 'manual',
                'transaction_status'=> 'success', // langsung sukses
                'midtrans_order_id' => null,
                'status'            => 'paid',
            ]);

            // ✅ Simpan item ke order_items
            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $item['quantity'],
                ]);
            }

            // ✅ Kosongkan cart session
            session()->forget('cart');

            DB::commit();

            return response()->json([
                'message' => 'Order successfully created!',
                'order_id' => $order->id,
                'total' => $total
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create order',
                'detail' => $e->getMessage()
            ], 500);
        }
    }

    public function success($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('orders.success', compact('order'));
    }
}
