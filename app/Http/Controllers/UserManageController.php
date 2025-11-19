<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManageController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     */
    public function index(Request $request)
    {
        // Ambil input pencarian (jika ada)
        $search = $request->input('search');

        // Query user dengan pencarian dan pagination
        $users = User::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // tampilkan 10 user per halaman

        // Kembalikan ke view dengan data dan keyword
        return view('admin.users.index', compact('users'));
    }


    /**
     * Menampilkan form tambah user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|string|in:admin,user',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Simpan user baru
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(), // ✅ langsung isi timestamp saat user dibuat
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User baru berhasil ditambahkan dan langsung diverifikasi.');
    }

    /**
     * Menampilkan form edit user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update data user
        $user->update([
            'name' => $data['name'],
            'role' => $data['role'],
            'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Hapus user dari sistem.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
