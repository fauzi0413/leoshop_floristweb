<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductManageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = \App\Models\Product::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('admin.product.index', compact('products'));
    }

    /**
     * Tampilkan form tambah produk.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.show_product')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    /**
     * Update produk.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama kalau ada
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }

            // Upload gambar baru
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        $product->update($data);

        return redirect()->route('admin.show_product')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy(Product $product)
    {
        // Jika produk punya gambar, hapus dari storage
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        // Hapus produk dari database
        $product->delete();

        return back()->with('success', 'Produk dan gambar berhasil dihapus.');
    }
}
