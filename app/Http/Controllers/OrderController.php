<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /** 
     * Tampilkan halaman checkout 
     */
    public function checkout()
    {
        $userId = Auth::id();

        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Keranjang kamu masih kosong!');
        }

        $total = $cartItems->sum(fn($i) => $i->price * $i->quantity);

        return view('checkout', compact('cartItems', 'total'));
    }

    /** 
     * Simpan order ke database lalu arahkan ke halaman pembayaran
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $userId = Auth::id();

        $cartItems = Cart::with('product')->where('user_id', $userId)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Keranjang kosong!');
        }

        // Hitung total
        $total = $cartItems->sum(fn($i) => $i->price * $i->quantity);

        // 1️⃣ Buat order
        $order = Order::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'whatsapp' => $validated['whatsapp'],
            'address' => $validated['address'],
            'total_price' => $total,
            'status' => 'pending', // menunggu pembayaran
            'payment_type' => 'manual', // bisa diubah ke midtrans/qris
        ]);

        // 2️⃣ Pindahkan semua item dari cart ke order_items
        foreach ($cartItems as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
            ]);
        }

        // 3️⃣ Kosongkan cart setelah checkout
        Cart::where('user_id', $userId)->delete();

        // 4️⃣ Arahkan ke halaman pembayaran
        return redirect()->route('payment.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /** 
     * Halaman pembayaran user 
     */
    public function paymentPage($id)
    {
        $order = Order::with('items.product')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('payment', compact('order'));
    }

    /** 
     * Halaman daftar pesanan user 
     */
    public function myOrders()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /** 
     * Admin update status pesanan
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'shipping_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $status = $validated['status'];

        // 🔹 Jika admin mengunggah bukti pengiriman
        if ($request->hasFile('shipping_proof')) {
            $path = $request->file('shipping_proof')->store('shipping_proofs', 'public');
            $order->shipping_proof = $path;
            $status = 'shipped'; // otomatis ubah status ke dikirim
            $order->shipped_at = now(); // simpan waktu pengiriman
        }

        // 🔹 Jika status diterima oleh pembeli
        if ($status === 'completed') {
            $order->received_at = now(); // waktu diterima, untuk batas retur 3 hari
        }

        // 🔹 Jika status pengembalian barang (retur)
        if ($status === 'returned') {
            $order->return_requested_at = now();
        }

        $order->status = $status;
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . ucfirst(str_replace('_', ' ', $status)) . '.');
    }

    public function uploadProof(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120' // max 5MB
        ]);

        // simpan file
        $file = $request->file('payment_proof');
        $path = $file->store('payments', 'public'); // storage/app/public/payments/...

        // update order
        $order->update([
            'payment_proof' => $path,
            'status' => 'pending_confirmation' // opsional: ubah status supaya admin tahu ada bukti
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil di-upload. Terima kasih!');
    }

    public function cancel(Request $request, Order $order)
    {
         $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

        // Hanya pemilik pesanan yang boleh batalkan
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Cek apakah status masih pending dan belum ada bukti pembayaran
        if ($order->status !== 'pending' || $order->payment_proof) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->cancel_reason,
        ]);

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function confirmReceived(Order $order)
    {
        if ($order->status === 'shipped') {
            $order->update(['status' => 'completed', 'received_at' => now()]);
            return back()->with('success', 'Pesanan berhasil dikonfirmasi sebagai diterima.');
        }
        return back()->with('error', 'Pesanan tidak dapat dikonfirmasi.');
    }

    public function returnRequest(Request $request, Order $order)
    {
        if (now()->diffInDays($order->updated_at) <= 3) {
            $order->update([
                'status' => 'returned',
                'return_reason' => $request->reason,
            ]);
            return back()->with('success', 'Pengajuan pengembalian telah dikirim.');
        }
        return back()->with('error', 'Batas waktu pengembalian telah lewat.');
    }

    
    /** 
     * Admin controller 
     */
    public function index(Request $request)
    {
         // Ambil semua status unik dari tabel orders
        $statuses = Order::select('status')
            ->distinct()
            ->pluck('status')
            ->toArray();

        // Ambil status aktif dari filter (kalau ada)
        $selectedStatus = $request->query('status');

        // Query data pesanan
        $orders = Order::when($selectedStatus, function ($query) use ($selectedStatus) {
            $query->where('status', $selectedStatus);
        })->latest()->paginate(10);

        return view('admin.orders.index', compact('orders', 'statuses', 'selectedStatus'));
    }


    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }
    
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

}
