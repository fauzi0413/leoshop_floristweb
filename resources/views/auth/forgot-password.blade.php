<x-guest-layout>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 via-white to-pink-100 px-4 py-10">

    <!-- Tombol Beranda (muncul di HP) -->
    <div class="absolute top-4 left-4 md:hidden z-30">
      <a href="{{ route('home') }}"
        class="inline-flex items-center gap-2 bg-white/90 text-pink-600 hover:bg-pink-600 hover:text-white font-semibold px-4 py-2 rounded-full shadow-md backdrop-blur-sm transition-all duration-300">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Beranda</span>
      </a>
    </div>

    <div class="w-full max-w-5xl overflow-hidden rounded-3xl border border-pink-100 bg-white/95 shadow-2xl backdrop-blur-sm">
      <div class="grid grid-cols-1 md:grid-cols-2">

        <!-- Panel kiri (gambar, hanya desktop) -->
        <div class="relative hidden md:flex items-center justify-center">
          <img
            src="https://images.unsplash.com/photo-1531058240690-006c446962d8?auto=format&fit=crop&q=80&w=800"
            alt="Ilustrasi"
            class="absolute inset-0 h-full w-full object-cover opacity-80"
            loading="lazy"
          />
          <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/60"></div>

          <div class="absolute top-5 left-5 z-20">
            <a href="{{ route('home') }}"
              class="inline-flex items-center gap-2 rounded-full bg-white/90 px-4 py-2 font-semibold text-pink-600 shadow-md backdrop-blur-sm transition-all duration-300 hover:bg-pink-600 hover:text-white">
              <i class="fa-solid fa-house"></i>
              <span>Beranda</span>
            </a>
          </div>

          <div class="relative z-10 text-center px-10">
            <h2 class="text-3xl font-bold text-white mb-3 drop-shadow-md">
              Lupa Password?
            </h2>
            <p class="text-gray-200 font-medium text-sm leading-relaxed">
              Masukkan email kamu, nanti kami kirim link untuk reset password ✉️
            </p>
          </div>
        </div>

        <!-- Form -->
        <div class="p-6 sm:p-10 md:p-12 flex flex-col justify-center">
          <div class="text-center mb-8">
            <a href="{{ route('home') }}"
              class="inline-flex items-center gap-2 justify-center text-pink-600 text-3xl font-extrabold">
              <i class="fa-solid fa-spa text-pink-500"></i> Leo Shop
            </a>
            <p class="text-gray-500 text-sm mt-2">
              Reset password akun kamu
            </p>
          </div>

          <div class="mb-4 text-sm text-gray-600">
            Jangan khawatir. Masukkan email kamu, nanti kami kirim link untuk reset password.
          </div>

          <!-- Status -->
          <x-auth-session-status class="mb-4 text-green-600 text-center font-medium" :status="session('status')" />

          <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
              <x-input-label for="email" value="Email" class="text-gray-800 font-bold tracking-wide" />
              <x-text-input
                id="email"
                class="block mt-2 w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl placeholder-gray-400 text-gray-800"
                type="email"
                name="email"
                :value="old('email')"
                required autofocus
                placeholder="contoh: emailkamu@gmail.com"
              />
              <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-600" />
            </div>

            <div class="pt-2">
              <button type="submit"
                class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                Kirim Link Reset Password
              </button>
            </div>

            <p class="text-center text-gray-500 text-sm pt-2">
              Ingat password?
              <a href="{{ route('login') }}" class="text-pink-600 hover:underline font-semibold">
                Kembali ke login
              </a>
            </p>
          </form>
        </div>

      </div>
    </div>
  </div>
</x-guest-layout>
