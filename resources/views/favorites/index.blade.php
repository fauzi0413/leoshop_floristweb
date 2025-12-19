<x-layout title="Favorit Saya">
  <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-screen">

    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-6 sm:mb-10 text-center text-gray-800">
      <span class="mr-1">❤️</span> Produk Favorit Saya
    </h1>

    @if($favorites->count() > 0)
      <div id="favorites-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-8 lg:gap-10">
        @foreach($favorites as $fav)
          @php $product = $fav->product; @endphp
          @if($product)
            <div id="fav-item-{{ $product->id }}" class="transition">
              <x-product-card
                :title="$product->title"
                :image="asset('storage/' . $product->image)"
                :price="$product->price"
                :id="$product->id"
                :description="$product->description ?? null"
                :isFavorite="true"
              />
            </div>
          @endif
        @endforeach
      </div>
    @else
      <div class="max-w-xl mx-auto mt-10 bg-white border border-gray-200 rounded-2xl shadow-sm p-8 text-center">
        <div class="text-4xl mb-3 text-pink-600">
          <i class="fa-regular fa-heart"></i>
        </div>
        <p class="text-gray-600 text-sm sm:text-base">
          Belum ada produk favorit. Yuk tambahkan dengan menekan ❤️ di produk!
        </p>
        <a href="{{ route('shop') }}"
           class="inline-flex items-center justify-center mt-5 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
          Cari Produk
        </a>
      </div>
    @endif

    <!-- Tombol Keranjang -->
    <x-cart-popup />

  </section>

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // ❤️ Toggle Favorite
    async function toggleFavoriteServer(id) {
      const response = await fetch(`/favorite/${id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      });

      if (response.status === 401) {
        Swal.fire({
          icon: 'warning',
          title: 'Login Diperlukan!',
          text: 'Silakan login terlebih dahulu untuk menyimpan favorit.',
          confirmButtonColor: '#e91e63',
          confirmButtonText: 'OK'
        }).then(result => {
          if (result.isConfirmed) window.location.href = '/login';
        });
        return;
      }

      const data = await response.json();
      const btn = document.getElementById(`fav-btn-${id}`);
      const icon = btn?.querySelector('i');
      const item = document.getElementById(`fav-item-${id}`);

      if (data.status === 'added') {
        btn?.classList.remove('bg-white/20');
        btn?.classList.add('bg-pink-600');
        icon?.classList.remove('text-pink-300');
        icon?.classList.add('text-white');

        Swal.fire({
          toast: true,
          icon: 'success',
          title: 'Ditambahkan ke favorit 💖',
          position: 'top-end',
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true
        });

      } else if (data.status === 'removed') {
        btn?.classList.remove('bg-pink-600');
        btn?.classList.add('bg-white/20');
        icon?.classList.remove('text-white');
        icon?.classList.add('text-pink-300');

        // Hapus dari tampilan favorit
        if (item) {
          item.classList.add('opacity-0', 'scale-95');
          item.classList.add('transition', 'duration-300');
          setTimeout(() => item.remove(), 300);

          // Jika tidak ada item tersisa, tampilkan empty state
          const grid = document.getElementById('favorites-grid');
          if (grid && grid.children.length === 1) {
            setTimeout(() => {
              grid.outerHTML = `
                <div class="max-w-xl mx-auto mt-10 bg-white border border-gray-200 rounded-2xl shadow-sm p-8 text-center">
                  <div class="text-4xl mb-3 text-pink-600">
                    <i class="fa-regular fa-heart"></i>
                  </div>
                  <p class="text-gray-600 text-sm sm:text-base">
                    Belum ada produk favorit. Yuk tambahkan dengan menekan ❤️ di produk!
                  </p>
                  <a href="{{ route('shop') }}"
                     class="inline-flex items-center justify-center mt-5 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
                    Cari Produk
                  </a>
                </div>
              `;
            }, 400);
          }
        }

        Swal.fire({
          toast: true,
          icon: 'info',
          title: 'Dihapus dari favorit 💔',
          position: 'top-end',
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true
        });
      }
    }

    // 🛒 Tambah ke Keranjang tanpa reload
    async function addToCart(id) {
      const response = await fetch(`/cart/add/${id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      });

      if (response.status === 401) {
        Swal.fire({
          icon: 'warning',
          title: 'Login Diperlukan!',
          text: 'Silakan login terlebih dahulu untuk menambah ke keranjang.',
          confirmButtonColor: '#e91e63',
          confirmButtonText: 'OK'
        }).then(() => window.location.href = '/login');
        return;
      }

      const data = await response.json();
      if (data.status === 'success') {
        Swal.fire({
          toast: true,
          icon: 'success',
          title: 'Produk ditambahkan ke keranjang 🛒',
          position: 'top-end',
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true
        });
      }
    }
  </script>
</x-layout>
