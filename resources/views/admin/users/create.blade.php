<x-layout-admin title="Tambah User">
  <section class="max-w-3xl mx-auto py-10">
    <!-- Tombol Kembali -->
    <a href="{{ route('admin.users.index') }}" 
       class="inline-flex items-center gap-2 border border-pink-500 text-pink-600 hover:bg-pink-500 hover:text-white font-medium px-4 py-2 rounded-lg transition-all duration-200 mb-6 shadow-sm">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    <!-- Judul -->
    <h1 class="text-3xl font-extrabold mb-8 text-gray-800 ">
      Tambah User Baru
    </h1>

    <!-- Form -->
    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6" autocomplete="off">
      @csrf

      <!-- Nama -->
      <div>
        <label class="block mb-1 font-semibold text-gray-700">Nama</label>
        <input type="text" name="name" 
               placeholder="Masukkan nama lengkap"
               value="{{ old('name') }}"
               class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 placeholder-gray-400"
               required>
      </div>

      <!-- Email -->
      <div>
        <label class="block mb-1 font-semibold text-gray-700">Email</label>
        <input type="email" name="email"
               placeholder="Masukkan email pengguna"
               value="{{ old('email') }}"
               autocomplete="off" autocapitalize="off" spellcheck="false"
               class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 placeholder-gray-400"
               required>
      </div>

      <!-- Role -->
      <div>
        <label class="block mb-1 font-semibold text-gray-700">Role</label>
        <select name="role" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 text-gray-700" required>
          <option value="" disabled selected>-- Pilih Role --</option>
          <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
          <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
      </div>

      <!-- Password -->
      <div x-data="{ show: false }" class="relative">
        <label class="block mb-1 font-semibold text-gray-700">Password</label>
        <input :type="show ? 'text' : 'password'"
               name="password"
               placeholder="Masukkan password"
               autocomplete="new-password"
               class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 placeholder-gray-400 pr-10"
               required>

        <!-- Tombol Mata -->
        <button type="button"
                @click="show = !show"
                class="absolute right-3 top-9 text-gray-400 hover:text-pink-500 transition-all duration-200">
          <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
        </button>
      </div>

      <!-- Konfirmasi Password -->
      <div x-data="{ show: false }" class="relative">
        <label class="block mb-1 font-semibold text-gray-700">Konfirmasi Password</label>
        <input :type="show ? 'text' : 'password'"
              name="password_confirmation"
              placeholder="Ulangi password"
              autocomplete="new-password"
              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 placeholder-gray-400 pr-10"
              required>

        <!-- Tombol Mata -->
        <button type="button"
                @click="show = !show"
                class="absolute right-3 top-9 text-gray-400 hover:text-pink-500 transition-all duration-200">
          <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
        </button>
      </div>

      <!-- Tombol Simpan -->
      <div class="flex justify-end items-center pt-4">
        <button type="submit" 
                class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> Simpan
        </button>
      </div>
    </form>
  </section>

  <!-- Font Awesome & Alpine.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-layout-admin>
