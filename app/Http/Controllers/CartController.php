<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /** ➕ Tambah produk ke keranjang */
    public function add($id)
    {
        $product = Product::findOrFail($id);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk menambahkan ke keranjang.');
        }

        $userId = Auth::id();

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        return redirect()->route('shop')->with('success', 'Produk berhasil ditambahkan ke keranjang!');

    }

    /** ➖ Kurangi / hapus item dari keranjang */
    public function remove($id)
    {
        $userId = Auth::id();

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $id)
            ->first();

        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
        }

        return redirect()->route('shop')->with('success', 'Keranjang berhasil diperbarui!');
    }

    /** 🧺 Tampilkan isi keranjang (halaman penuh) */
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        return view('cart.index', compact('cartItems', 'total'));
    }

    /** 🧾 Checkout ke halaman order (bukan langsung WA) */
    public function checkout()
    {
        return redirect()->route('order.checkout');
    }
}
