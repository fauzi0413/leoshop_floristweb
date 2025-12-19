<x-guest-layout>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 via-white to-pink-100 px-4 py-10">
    <!-- 🔙 Tombol Beranda (Mobile Only) -->
    <div class="absolute top-4 left-4 md:hidden z-30">
        <a href="{{ route('home') }}"
        class="inline-flex items-center gap-2 bg-white/90 text-pink-600 hover:bg-pink-600 hover:text-white font-semibold px-4 py-2 rounded-full shadow-md backdrop-blur-sm transition-all duration-300">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Beranda</span>
        </a>
    </div>

    <div class="w-full max-w-5xl overflow-hidden rounded-3xl border border-pink-100 bg-white/95 shadow-2xl backdrop-blur-sm">
      <div class="grid grid-cols-1 md:grid-cols-2">

        {{-- 🌸 Ilustrasi (Desktop only) --}}
        <div class="relative hidden md:flex items-center justify-center">
          <img
            src="https://images.unsplash.com/photo-1531058240690-006c446962d8?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=800"
            alt="Ilustrasi Florist"
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
              Keindahan Dalam Setiap Rangkaian
            </h2>
            <p class="text-gray-200 font-medium text-sm leading-relaxed">
              Bunga segar pilihan untuk setiap momen berharga Anda 🌸
            </p>
          </div>
        </div>

        {{-- 💐 Form --}}
        <div class="p-6 sm:p-10 md:p-12 flex flex-col justify-center">
          <!-- Header -->
          <div class="text-center mb-8">
            <a href="{{ route('home') }}"
              class="inline-flex items-center gap-2 justify-center text-pink-600 text-3xl font-extrabold">
              <i class="fa-solid fa-spa text-pink-500"></i> Leo Shop
            </a>
            <p class="text-gray-500 text-sm mt-2">
              Masuk ke akun admin atau pengguna kamu
            </p>
          </div>

          <!-- Status -->
          <x-auth-session-status
            class="mb-4 text-green-600 text-center font-medium"
            :status="session('status')" />

          <!-- Form -->
          <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
              <x-input-label for="email" :value="__('Email')" class="text-gray-800 font-bold tracking-wide" />
              <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required autofocus autocomplete="off"
                class="block mt-2 w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl placeholder-gray-400 text-gray-800"
                placeholder="contoh: admin@florist.com"
              />
              <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-600" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
              <x-input-label for="password" :value="__('Password')" class="text-gray-800 font-bold tracking-wide" />

              <div class="relative mt-2">
                <input
                  :type="show ? 'text' : 'password'"
                  id="password"
                  name="password"
                  required
                  autocomplete="current-password"
                  class="block w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl text-gray-800 placeholder-gray-400 pr-11"
                  placeholder="••••••••"
                />

                <button
                  type="button"
                  @click="show = !show"
                  class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-pink-500 focus:outline-none"
                  aria-label="Toggle password visibility"
                >
                  <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="transition-all duration-200"></i>
                </button>
              </div>

              <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-600" />
            </div>

            <!-- Remember & Forgot -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
              <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox"
                  class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                  name="remember">
                <span class="ml-2 text-gray-600">Ingat saya</span>
              </label>

              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                  class="text-pink-600 hover:text-pink-700 font-medium">
                  Lupa password?
                </a>
              @endif
            </div>

            <!-- Button -->
            <div class="pt-2">
              <button type="submit"
                class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                Masuk
              </button>
            </div>
          </form>

          <!-- Footer -->
          <p class="text-center text-gray-500 text-sm mt-8">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-pink-600 hover:underline font-semibold">
              Daftar sekarang
            </a>
          </p>
        </div>

      </div>
    </div>
  </div>
</x-guest-layout>
