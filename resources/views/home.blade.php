<x-layout title="Beranda">
  
  @auth
    @php
      $isAdmin = (auth()->user()->role ?? null) === 'admin';
      $userPending = (int) ($userPendingCount ?? 0);
      $adminPending = (int) ($adminPendingConfirmCount ?? 0);
    @endphp

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const isAdmin = @json($isAdmin);
        const userPending = @json($userPending);
        const adminPending = @json($adminPending);

        // ADMIN: ada pending confirmation
        if (isAdmin && adminPending > 0) {
          iziToast.show({
            title: `Ada ${adminPending} pesanan baru`,
            message: 'Status: pending confirmation',
            position: 'topRight',
            timeout: false,       // stay sampai di-close
            close: true,          // ada tombol X
            drag: false,
            maxWidth: 320,        // biar tidak besar
            buttons: [
              ['<button style="margin-left:8px;">Cek Pesanan</button>', (instance, toast) => {
                window.location.href = '/admin/orders';
              }]
            ]
          });
        }

        // USER: ada pending payment
        if (!isAdmin && userPending > 0) {
          iziToast.show({
            title: `Kamu punya ${userPending} pesanan pending`,
            message: 'Selesaikan pembayarannya ya.',
            position: 'topRight',
            timeout: false,
            close: true,
            drag: false,
            maxWidth: 320,
            buttons: [
              ['<button style="margin-left:8px;">Bayar Sekarang</button>', (instance, toast) => {
                window.location.href = '/orders';
              }]
            ]
          });
        }
      });
    </script>
  @endauth

  {{-- 🌸 Hero Section (Responsive) --}}
  <section
    class="relative min-h-[70vh] sm:min-h-[75vh] md:min-h-[85vh] flex items-center justify-center bg-cover bg-center"
    style="background-image: url('https://images.unsplash.com/photo-1487070183336-b863922373d4?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1170')"
  >
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-black/10"></div>

    <div class="relative z-10 text-center text-white px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
      <h1 class="text-3xl sm:text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
        Bouquet Cantik,<br class="hidden sm:block">Buat Momen Lebih Berkesan
      </h1>

      <p class="text-sm sm:text-base md:text-lg text-gray-200 mb-7 max-w-2xl mx-auto leading-relaxed">
        Leo Shop menyediakan bouquet bunga, bouquet uang, dan beragam bucket custom sesuai permintaan Anda.
        Hadiah istimewa untuk momen istimewa 💐💖
      </p>

      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="/shop"
          class="inline-flex items-center justify-center bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full shadow-md hover:shadow-lg transition duration-300">
          Lihat Produk Kami
        </a>
        <a href="#products"
          class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3 rounded-full border border-white/20 transition duration-300">
          Produk Populer
        </a>
      </div>
    </div>
  </section>


  {{-- 🛍 Produk Populer (Responsive Grid + Card lebih rapih) --}}
  <section id="products" class="py-16 sm:py-20 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-10 sm:mb-12 md:mb-14 text-gray-800">
        Produk Populer
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
        @forelse ($top_products as $item)
          @php $product = $item->product; @endphp

          <div
            class="group relative rounded-3xl overflow-hidden shadow-xl bg-gradient-to-b from-gray-900/40 to-black/80 hover:shadow-2xl transition duration-300"
          >
            <div class="absolute top-4 left-4 bg-pink-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg z-20">
              🔥 Best Seller
            </div>

            <!-- Ratio gambar biar konsisten -->
            <div class="relative aspect-[3/4]">
              <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->title }}"
                class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-90 transition duration-300"
                loading="lazy"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

              <!-- Konten -->
              <div class="absolute inset-0 flex flex-col justify-end p-5 sm:p-6 text-white">
                <p class="text-xl sm:text-2xl font-extrabold mb-1">
                  Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-200 line-clamp-2">
                  {{ $product->title }}
                </p>

                @auth
                  <form action="{{ route('cart.add', $product->id) }}" method="POST" class="relative z-20">
                    @csrf
                    <button type="submit"
                      class="mt-4 w-full text-center bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2.5 rounded-full shadow-md transition duration-300">
                      Pesan
                    </button>
                  </form>
                @else
                  <button type="button" onclick="showLoginAlert()"
                    class="mt-4 w-full text-center bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2.5 rounded-full shadow-md transition duration-300">
                    Pesan
                  </button>

                  <script>
                    function showLoginAlert() {
                      Swal.fire({
                        title: 'Login Diperlukan',
                        text: 'Anda harus login untuk menambahkan produk ke keranjang.',
                        icon: 'warning',
                        confirmButtonText: 'Login Sekarang',
                        confirmButtonColor: '#ec4899',
                        showCancelButton: true,
                        cancelButtonText: 'Batal',
                        cancelButtonColor: '#6b7280',
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
          </div>
        @empty
          <p class="text-gray-500 col-span-full text-center">Belum ada produk tersedia.</p>
        @endforelse
      </div>
    </div>
  </section>


  {{-- 🌼 Tentang Kami (Responsive layout + spacing rapih) --}}
  <section id="about" class="py-16 sm:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12 items-center">
        <div class="order-1 md:order-none">
          <img
            src="{{ url('assets/logo-leoshop-new.jpeg') }}"
            alt="Leo Shop"
            class="rounded-2xl w-full max-w-xs sm:max-w-sm mx-auto shadow-lg object-cover"
            loading="lazy"
          />
        </div>

        <div>
          <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 text-gray-800">
            Tentang Kami
          </h2>

          <p class="text-gray-600 mb-5 leading-relaxed text-sm sm:text-base">
            Leo Shop adalah tempat terbaik untuk Anda yang mencari beragam bouquet cantik dan unik — mulai dari bouquet bunga,
            bouquet uang, gift bucket, hingga rangkaian custom sesuai keinginan.
            Setiap pesanan dibuat dengan penuh perhatian, kreativitas, dan kualitas terbaik agar momen spesial Anda semakin berkesan 🌸✨
          </p>

          <ul class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6 text-gray-700 text-sm sm:text-base">
            <li class="flex gap-2"><span>✅</span><span>Menerima Bouquet Bunga & Bouquet Uang</span></li>
            <li class="flex gap-2"><span>✅</span><span>Bisa Request Desain Custom</span></li>
            <li class="flex gap-2"><span>✅</span><span>Bahan Berkualitas & Tahan Lama</span></li>
            <li class="flex gap-2"><span>✅</span><span>Bucket Terpercaya & Berpengalaman</span></li>
            <li class="flex gap-2"><span>✅</span><span>Pelayanan Cepat & Ramah</span></li>
          </ul>
        </div>
      </div>
    </div>
  </section>


  {{-- 📞 Kontak Kami (Responsive + tombol rapi + maps aman) --}}
  <section id="contact" class="bg-gray-50 py-16 sm:py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-10 sm:mb-12 text-gray-800">
        Hubungi Kami
      </h2>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
        <div class="space-y-4 text-gray-700">
          <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
            Siap membantu Anda membuat bucket terbaik untuk hadiah, wisuda, ulang tahun, anniversary, atau momen spesial lainnya.
          </p>

          <div class="space-y-2 text-sm sm:text-base">
            <p><strong>📞 Phone:</strong> 085705865801</p>
            <p><strong>📍 Alamat:</strong> Jl. Letkol Sosrosudiro, Dusun V, Pliken, Kec. Kembaran, Kabupaten Banyumas, Jawa Tengah 53182</p>
          </div>

          <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="https://www.instagram.com/leoshop.purwokerto"
              target="_blank"
              class="inline-flex items-center justify-center gap-2 bg-pink-500 hover:bg-pink-600 text-white px-5 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transition duration-200">
              <i class="fa-brands fa-instagram text-xl"></i>
              <span>@leoshop.purwokerto</span>
            </a>

            <a href="https://wa.me/6285705865801"
              target="_blank"
              class="inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transition duration-200">
              <i class="fa-brands fa-whatsapp text-xl"></i>
              <span>Chat via WhatsApp</span>
            </a>
          </div>
        </div>

        <div class="w-full">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.3101087765026!2d109.2859495!3d-7.430894799999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e65590052626921%3A0x9e843729c9021eef!2sLeo%20Shop!5e0!3m2!1sid!2sid!4v1763551170473!5m2!1sid!2sid"
            class="w-full h-72 sm:h-80 md:h-96 rounded-xl border-0 shadow-md"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
      </div>
    </div>
  </section>

</x-layout>
