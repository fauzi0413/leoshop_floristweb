<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;

class AdminController extends Controller
{
    /**
     * Tampilkan semua produk.
     */
    public function index()
    {
        $total_product = Product::count();
        $total_order = Order::count();
        $total_user = User::count();

        // Tambahan highlight
        $today_orders = Order::whereDate('created_at', today())->count();
        $completed_orders = Order::where('status', 'completed')->count();
        $cancelled_orders = Order::where('status', 'cancelled')->count(); 

        // Statistik 7 hari terakhir untuk grafik
        $ordersByDay = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(6))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        $chart_labels = $ordersByDay->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chart_data = $ordersByDay->pluck('count');

        // Produk paling banyak dipesan (hanya order yang sudah melewati pending_confirmation)
        $top_products = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['pending', 'pending_confirmation']);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product:id,title')
            ->take(3)
            ->get();

            
        return view('admin.dashboard', compact(
            'total_product',
            'total_order',
            'total_user',
            'today_orders',
            'completed_orders',
            'cancelled_orders', 
            'chart_labels',
            'chart_data',
            'top_products',
        ));
}

}
