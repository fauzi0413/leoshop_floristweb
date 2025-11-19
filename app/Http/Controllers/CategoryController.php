<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        Category::findOrFail($id)->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
