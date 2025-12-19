<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 via-white to-pink-100 px-4 py-10">

        <!-- 🔙 Beranda (Mobile - seperti Login) -->
        <div class="absolute top-4 left-4 z-30 md:hidden">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 bg-white/90 text-pink-600
                      hover:bg-pink-600 hover:text-white
                      font-semibold px-4 py-2 rounded-full
                      shadow-md border border-pink-100 backdrop-blur-sm transition-all duration-300">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Beranda</span>
            </a>
        </div>

        <div class="flex flex-col md:flex-row bg-white/95 border border-pink-100 shadow-2xl rounded-3xl overflow-hidden max-w-5xl w-full backdrop-blur-sm">

            {{-- 🌷 Ilustrasi Kiri --}}
            <div class="hidden md:flex md:w-1/2 relative items-center justify-center">
                <img src="https://images.unsplash.com/photo-1531058240690-006c446962d8?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=800"
                     alt="Ilustrasi Florist"
                     class="absolute inset-0 w-full h-full object-cover opacity-80">

                <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/60"></div>

                <div class="absolute top-5 left-5 z-20">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 bg-white/90 hover:bg-pink-600 hover:text-white text-pink-600 font-semibold px-4 py-2 rounded-full shadow-md transition-all duration-300 backdrop-blur-sm">
                        <i class="fa-solid fa-house"></i>
                        <span>Beranda</span>
                    </a>
                </div>

                <div class="relative z-10 text-center px-8">
                    <h2 class="text-3xl font-bold text-white mb-3 drop-shadow-md">
                        Ciptakan Keindahan Bersama Kami
                    </h2>
                    <p class="text-gray-200 font-medium text-sm leading-relaxed">
                        Bergabunglah dengan Leo Shop dan temukan kemewahan dalam setiap rangkaian bunga 🌸
                    </p>
                </div>
            </div>

            {{-- 💐 Form Kanan --}}
            <div class="w-full md:w-1/2 p-6 sm:p-10 flex flex-col justify-center">
                <!-- Header -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 justify-center text-pink-600 text-3xl font-extrabold">
                        <i class="fa-solid fa-spa text-pink-500"></i> Leo Shop
                    </a>
                    <p class="text-gray-500 text-sm mt-2">
                        Buat akun baru dan nikmati kemudahan berbelanja bunga
                    </p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" autocomplete="off" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-800 font-bold tracking-wide" />
                        <x-text-input
                            id="name"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required autofocus
                            autocomplete="off"
                            class="block mt-1 w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl text-gray-800 placeholder-gray-400"
                            placeholder="contoh: Amalia Putri"
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-pink-600" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-800 font-bold tracking-wide" />
                        <x-text-input
                            id="email"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autocomplete="off"
                            class="block mt-1 w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl text-gray-800 placeholder-gray-400"
                            placeholder="contoh: amalia@florist.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-600" />
                    </div>

                    <!-- Password -->
                    <div x-data="{ show: false }">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-800 font-bold tracking-wide" />
                        <div class="relative mt-1">
                            <input
                                :type="show ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="block w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl text-gray-800 placeholder-gray-400 pr-11"
                                placeholder="masukkan password"
                            />
                            <button type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-pink-500 focus:outline-none">
                                <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-600" />
                    </div>

                    <!-- Confirm Password -->
                    <div x-data="{ showConfirm: false }">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-800 font-bold tracking-wide" />
                        <div class="relative mt-1">
                            <input
                                :type="showConfirm ? 'text' : 'password'"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="block w-full !bg-white border border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 rounded-xl text-gray-800 placeholder-gray-400 pr-11"
                                placeholder="ulangi password"
                            />
                            <button type="button"
                                    @click="showConfirm = !showConfirm"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-pink-500 focus:outline-none">
                                <i :class="showConfirm ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-pink-600" />
                    </div>

                    <!-- Tombol -->
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <p class="text-center text-gray-500 text-sm mt-8">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-pink-600 hover:underline font-semibold">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>

    </div>

    {{-- Pastikan Alpine.js aktif --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-guest-layout>
