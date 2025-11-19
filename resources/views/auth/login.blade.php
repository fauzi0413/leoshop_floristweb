<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 via-white to-pink-100 px-4">
        <div class="flex flex-col md:flex-row bg-white/95 border border-pink-100 shadow-2xl rounded-3xl overflow-hidden max-w-5xl w-full backdrop-blur-sm">

            {{-- 🌸 Ilustrasi Kiri --}}
            <div class="hidden md:flex md:w-1/2 relative items-center justify-center">
                <img src="https://images.unsplash.com/photo-1531058240690-006c446962d8?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=800"
                    alt="Ilustrasi Florist"
                    class="absolute inset-0 w-full h-full object-cover opacity-80">

                {{-- Siluet Hitam Transparan --}}
                <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/60"></div>

                {{-- 🌸 Tombol Home di Pojok Kiri Atas --}}
                <div class="absolute top-5 left-5 z-20">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 bg-white/90 hover:bg-pink-600 hover:text-white text-pink-600 font-semibold px-4 py-2 rounded-full shadow-md transition-all duration-300 backdrop-blur-sm">
                        <i class="fa-solid fa-house"></i>
                        <span>Beranda</span>
                    </a>
                </div>

                {{-- Teks di Atas Siluet --}}
                <div class="relative z-10 text-center px-8">
                    <h2 class="text-3xl font-bold text-white mb-3 drop-shadow-md">
                        Keindahan Dalam Setiap Rangkaian
                    </h2>
                    <p class="text-gray-200 font-medium text-sm leading-relaxed">
                        Bunga segar pilihan untuk setiap momen berharga Anda 🌸
                    </p>
                </div>
            </div>

            {{-- 💐 Form Kanan --}}
            <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
                <!-- Header -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center gap-2 justify-center text-pink-600 text-3xl font-extrabold">
                        <i class="fa-solid fa-spa text-pink-500"></i> Leo Shop
                    </a>
                    <p class="text-gray-500 text-sm mt-2">Masuk ke akun admin atau pengguna kamu</p>
                </div>

                <!-- Status -->
                <x-auth-session-status 
                    class="mb-4 text-green-600 text-center font-medium" 
                    :status="session('status')" />

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                            class="block mt-1 w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl placeholder-gray-400 text-gray-800"
                            placeholder="contoh: admin@florist.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-600" />
                    </div>

                    <!-- Password -->
                    <div x-data="{ show: false }" class="relative">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-800 font-bold tracking-wide" />
                        
                        <input 
                            :type="show ? 'text' : 'password'"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            class="block mt-1 w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl text-gray-800 placeholder-gray-400 pr-10"
                            placeholder="••••••••"
                        />

                        <!-- Tombol mata -->
                        <button 
                            type="button"
                            @click="show = !show"
                            class="absolute right-3 top-9 text-gray-400 hover:text-pink-500 focus:outline-none"
                        >
                            <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="transition-all duration-200"></i>
                        </button>

                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-600" />
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                name="remember">
                            <span class="ml-2 text-gray-600">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-pink-600 hover:text-pink-700 font-medium">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Tombol -->
                    <div class="pt-4">
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
</x-guest-layout>
