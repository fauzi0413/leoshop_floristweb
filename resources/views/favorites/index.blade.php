<x-layout title="Favorit Saya">
  <section class="max-w-6xl mx-auto py-10 min-h-screen">
    <h1 class="text-4xl font-bold mb-10 text-center text-gray-800">
      ❤️ Produk Favorit Saya
    </h1>

    @if($favorites->count() > 0)
      <div id="favorites-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
        @foreach($favorites as $fav)
          @php $product = $fav->product; @endphp
          @if($product)
            <div id="fav-item-{{ $product->id }}">
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
      <p class="text-center text-gray-500 mt-10">
        <i class="fa-regular fa-heart mr-2"></i>
        Belum ada produk favorit. Yuk tambahkan dengan menekan ❤️ di produk!
      </p>
    @endif

    <!-- Tombol Keranjang -->
    <x-cart-popup />

  </section>

  <!-- Script -->
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

      // Toggle UI status
      if (data.status === 'added') {
        btn.classList.remove('bg-white/20');
        btn.classList.add('bg-pink-600');
        icon.classList.remove('text-pink-300');
        icon.classList.add('text-white');

        Swal.fire({
          toast: true,
          icon: 'success',
          title: 'Ditambahkan ke favorit 💖',
          position: 'top-end',
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true
        });
      } 
      else if (data.status === 'removed') {
        btn.classList.remove('bg-pink-600');
        btn.classList.add('bg-white/20');
        icon.classList.remove('text-white');
        icon.classList.add('text-pink-300');

        // Hapus dari tampilan favorit
        if (item) {
          item.classList.add('opacity-0', 'scale-95', 'transition', 'duration-300');
          setTimeout(() => item.remove(), 300);

          // Jika tidak ada item tersisa
          const grid = document.getElementById('favorites-grid');
          if (grid && grid.children.length === 1) {
            setTimeout(() => {
              grid.innerHTML = `
                <p class="col-span-full text-center text-gray-500 mt-10">
                  <i class="fa-regular fa-heart mr-2"></i>
                  Belum ada produk favorit. Yuk tambahkan dengan menekan ❤️ di produk!
                </p>`;
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

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-layout>
