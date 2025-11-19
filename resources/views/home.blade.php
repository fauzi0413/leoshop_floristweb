<x-layout title="Beranda">

    {{-- 🌸 Hero Section --}}
    <section class="relative h-[85vh] flex items-center justify-center bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1487070183336-b863922373d4?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1170')">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-transparent"></div>

        <div class="relative z-10 text-center text-white px-4">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4 leading-tight">
                Bouquet Cantik,<br>Buat Momen Lebih Berkesan
            </h1>
            <p class="text-lg text-gray-200 mb-6 max-w-2xl mx-auto">
                Leo Shop menyediakan bouquet bunga, bouquet uang, dan beragam bucket custom sesuai permintaan Anda. 
                Hadiah istimewa untuk momen istimewa 💐💖
            </p>
            <a href="/shop"
                class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full shadow-md hover:shadow-lg transition duration-300">
                Lihat Produk Kami
            </a>
        </div>
    </section>

    {{-- 🛍 Produk Populer --}}
    <section id="products" class="py-24 bg-gray-50">
        <h2 class="text-4xl font-bold text-center mb-14 text-gray-800">Produk Populer</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10 max-w-7xl mx-auto px-6">

            @forelse ($top_products as $item)
                @php
                    $product = $item->product;
                @endphp

                <div
                    class="relative h-[520px] bg-gradient-to-b from-gray-800/60 to-black/80 rounded-3xl overflow-hidden shadow-2xl hover:scale-105 transition-transform duration-500">

                    <div class="absolute top-4 left-4 bg-pink-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg z-20">
                        🔥 Best Seller
                    </div>

                    <!-- Gambar Produk -->
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                        class="absolute inset-0 w-full h-full object-cover opacity-70">

                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

                    <!-- Konten -->
                    <div class="relative z-10 flex flex-col justify-end h-full p-8 text-white">
                        <div>
                            <p class="text-3xl font-extrabold mb-1">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-300">{{ $product->title }}</p> 
                        </div>

                        <!-- Tombol Tambah Keranjang -->
                        @auth
                            <!-- Jika sudah login → form normal -->
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="relative z-20">
                                @csrf
                                <button type="submit"
                                    class="mt-6 w-full text-center bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 rounded-full shadow-md transition duration-300">
                                    Pesan
                                </button>
                            </form>
                        @else
                            <!-- Jika belum login → SweetAlert muncul -->
                            <button type="button" onclick="showLoginAlert()"
                                class="mt-6 w-full text-center bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 rounded-full shadow-md transition duration-300">
                                Pesan
                            </button>

                            <script>
                                function showLoginAlert() {
                                    Swal.fire({
                                        title: 'Login Diperlukan',
                                        text: 'Anda harus login untuk menambahkan produk ke keranjang.',
                                        icon: 'warning',
                                        confirmButtonText: 'Login Sekarang',
                                        confirmButtonColor: '#ec4899', // warna pink Tailwind: bg-pink-600
                                        showCancelButton: true,
                                        cancelButtonText: 'Batal',
                                        cancelButtonColor: '#6b7280', // gray-500
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "{{ route('login') }}";
                                        }
                                    });
                                }
                            </script>
                        @endauth


                    </div>
                </div>

            @empty
                <p class="text-gray-500 col-span-4 text-center">Belum ada produk tersedia.</p>
            @endforelse

        </div>

    </section>

    {{-- 🌼 Tentang Kami --}}
    <section id="about" class="py-20 bg-white max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-10 px-4">
        {{-- <img src="https://images.unsplash.com/photo-1642751652611-bb9a7cad58a3?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1171" class="rounded-2xl w-full md:w-1/2 shadow-lg"> --}}
        <img src="{{url('assets/logo leo shop.jpeg')}}" class="rounded-2xl w-full md:w-auto max-w-xs md:max-w-sm shadow-lg mx-auto">
        
        <div class="md:w-1/2">
            <h2 class="text-4xl font-bold mb-4 text-gray-800">Tentang Kami</h2>
            <p class="text-gray-600 mb-4 leading-relaxed">
                Leo Shop adalah tempat terbaik untuk Anda yang mencari beragam bouquet cantik dan unik — mulai dari bouquet bunga, 
                bouquet uang, gift bucket, hingga rangkaian custom sesuai keinginan. 
                Setiap pesanan dibuat dengan penuh perhatian, kreativitas, dan kualitas terbaik agar momen spesial Anda semakin berkesan 🌸✨
            </p>

            <ul class="list-disc ml-6 text-gray-700 space-y-1">
                <li>Menerima Bouquet Bunga & Bouquet Uang</li>
                <li>Bisa Request Desain Custom</li>
                <li>Bahan Berkualitas & Tahan Lama</li>
                <li>Tempat Bucket Terpercaya & Berpengalaman</li>
                <li>Pelayanan Cepat, Ramah, dan Profesional</li>
            </ul>

        </div>
    </section>

    {{-- 📞 Kontak Kami --}}
    <section id="contact" class="bg-gray-50 py-20">
        <div class="max-w-5xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Hubungi Kami</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div class="space-y-4 text-gray-700">
                    
                    <p class="text-gray-700">
                        Siap membantu Anda membuat bucket terbaik untuk hadiah, wisuda, ulang tahun, anniversary, atau momen spesial lainnya.
                    </p>

                    <p><strong>📞 Phone:</strong> 085705865801</p>

                    <p><strong>📍 Alamat:</strong> Jl. Letkol Sosrosudiro, Dusun V, Pliken, Kec. Kembaran, Kabupaten Banyumas, Jawa Tengah 53182</p>

                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Instagram -->
                        <div class="mt-4">
                            <a href="https://www.instagram.com/leosh0p"
                                target="_blank"
                                class="inline-flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white px-5 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transition duration-200">
                                <i class="fa-brands fa-instagram text-xl"></i>
                                @leosh0p
                            </a>
                        </div>

                        <!-- WhatsApp -->
                        <div class="mt-4">
                            <a href="https://wa.me/6285705865801"
                                target="_blank"
                                class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transition duration-200">
                                <i class="fa-brands fa-whatsapp text-xl"></i>
                                Chat via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Baru --> 
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.3101087765026!2d109.2859495!3d-7.430894799999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e65590052626921%3A0x9e843729c9021eef!2sLeo%20Shop!5e0!3m2!1sid!2sid!4v1763551170473!5m2!1sid!2sid"
                    class="w-full h-80 rounded-xl border-0 shadow-md">
                </iframe>
            </div>
        </div>
    </section>

</x-layout>
