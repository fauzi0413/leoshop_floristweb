<x-layout title="Halaman Tidak Ditemukan">
    <section class="flex flex-col items-center justify-center min-h-[80vh] text-center px-6 pb-16 bg-gray-50">
        <!-- Icon / Illustration -->
        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486803.png" 
             alt="404 Not Found" 
             class="w-52 md:w-56 mb-6 opacity-90">

        <!-- Text -->
        <h1 class="text-6xl font-extrabold text-pink-600 mb-2">404</h1>
        <h2 class="text-2xl font-semibold text-gray-800 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-gray-600 mb-8 max-w-md leading-relaxed">
            Maaf, halaman yang kamu cari tidak tersedia. Mungkin sudah dihapus atau alamatnya salah.
        </p>

        <!-- Buttons -->
        <div class="flex flex-wrap justify-center gap-4">
            
            <!-- Tombol Beranda -->
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-300">
                <i class="fa-solid fa-house"></i>
                Kembali ke Beranda
            </a>

            <!-- Tombol Produk -->
            <a href="{{ route('shop') }}" 
               class="inline-flex items-center gap-2 border border-pink-500 bg-white text-pink-600 hover:bg-pink-600 hover:text-white px-6 py-3 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-300">
                <i class="fa-solid fa-store"></i>
                Lihat Produk
            </a>
        </div>
    </section>
</x-layout>
