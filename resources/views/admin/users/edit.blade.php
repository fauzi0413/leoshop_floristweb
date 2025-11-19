<x-layout-admin title="Edit User">
  <section class="max-w-3xl mx-auto py-5">
    <a href="{{ route('admin.users.index') }}" 
       class="inline-block border border-pink-500 text-pink-600 hover:bg-pink-500 hover:text-white font-medium px-3 py-1 rounded-lg transition duration-200 mb-4">
      ← Kembali
    </a>

    <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
      @csrf @method('PUT')

      <div>
        <label class="block mb-1 font-semibold text-gray-700">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400" required>
      </div>

      <div>
        <label class="block mb-1 font-semibold text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border bg-gray-300 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 cursor-not-allowed" disabled>
      </div>

      <div>
        <label class="block mb-1 font-semibold text-gray-700">Role</label>
        <select name="role" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400">
          <option value="user" @selected($user->role === 'user')>User</option>
          <option value="admin" @selected($user->role === 'admin')>Admin</option>
        </select>
      </div>

      <div class="flex justify-end items-center pt-4">
        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-medium px-6 py-2 rounded-lg shadow">
          Update
        </button>
      </div>
    </form>
  </section>
</x-layout-admin>
