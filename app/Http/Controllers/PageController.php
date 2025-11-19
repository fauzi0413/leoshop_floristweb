<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class PageController extends Controller
{
    public function home()
    {
        // 1. Ambil best seller (max 4) + relasi product
        $bestSellers = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['pending', 'pending_confirmation']);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product:id,title,image,price')
            ->take(4)
            ->get();

        // 2. Bangun collection top_products dari best seller
        $top_products = collect();

        foreach ($bestSellers as $item) {
            if ($item->product) {
                $top_products->push((object)[
                    'product'      => $item->product,
                    'total_sold'   => $item->total_sold,
                    'is_best_seller' => true,
                ]);
            }
        }

        // 3. Kalau masih kurang dari 4 → tambah produk terbaru
        if ($top_products->count() < 4) {
            $needed = 4 - $top_products->count();

            $additionalProducts = Product::whereNotIn(
                    'id',
                    $top_products->pluck('product.id') // hindari duplikat
                )
                ->latest()
                ->take($needed)
                ->get();

            foreach ($additionalProducts as $product) {
                $top_products->push((object)[
                    'product'        => $product,
                    'total_sold'     => 0,
                    'is_best_seller' => false,
                ]);
            }
        }
        
        return view('home', compact('top_products'));
    }

    public function shop(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $products = Product::query()

            // FILTER PENCARIAN
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })

            // FILTER KATEGORI
            ->when($category, function ($query, $category) {
                $query->where('category_id', $category);
            })

            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // FAVORITE ID USER
        $favoriteIds = Auth::check()
            ? Auth::user()->favorites()->pluck('product_id')->toArray()
            : [];

        // SEMUA KATEGORI
        $categories = Category::whereHas('products')
            ->orderBy('name', 'asc')
            ->get();

        return view('shop', compact('products', 'favoriteIds', 'categories'));
    }

    // Tampilkan daftar produk favorit user
    public function favorites()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat daftar favorit Anda.');
        }

        $favorites = Favorite::with('product')->where('user_id', $user->id)->get();

        return view('favorites.index', compact('favorites'));
    }

    // Tambah / hapus produk dari favorit (toggle)
    public function toggleFavorite($productId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }

        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json(['status' => 'added']);
        }
    }

}
