@props(['title', 'image', 'price' => null, 'id' => null, 'description' => null, 'isFavorite' => false])

<div
class="relative h-[420px] bg-gradient-to-b from-gray-800/60 to-black/80 rounded-3xl overflow-hidden shadow-2xl hover:scale-105 transition-transform duration-500">

<!-- Gambar Produk -->
<img 
    src="{{ $image }}" 
    alt="{{ $title }}"
    class="absolute inset-0 w-full h-full object-cover opacity-70"
>

<!-- Overlay Gradient -->
<div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

<!-- Konten -->
<div class="relative z-10 flex flex-col justify-end h-full p-8 text-white">
    
    <!-- Info Produk -->
    <div>
    @if($price)
        <p class="text-3xl font-extrabold mb-1">
        Rp {{ number_format($price, 0, ',', '.') }}
        </p>
    @endif
    <p class="text-sm text-gray-300">{{ $title }}</p>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-6 flex items-center justify-around gap-3">

        {{-- ❤️ Tombol Favorite --}}
        @auth
            <button type="button"
                id="fav-btn-{{ $id }}"
                onclick="toggleFavoriteServer({{ $id }})"
                class="{{ $isFavorite ? 'bg-pink-600' : 'bg-white/20' }}
                    w-11 h-11 flex items-center justify-center rounded-full shadow-md transition duration-300 hover:scale-110"
                title="{{ $isFavorite ? 'Hapus dari Favorit' : 'Tambah ke Favorit' }}">
                <i class="fa-solid fa-heart text-lg {{ $isFavorite ? 'text-white' : 'text-pink-300' }}"></i>
            </button>
        @else
            <button type="button"
                onclick="showLoginAlert()"
                class="bg-white/20 w-11 h-11 flex items-center justify-center rounded-full shadow-md transition duration-300 hover:scale-110"
                title="Tambah ke Favorit">
                <i class="fa-solid fa-heart text-lg text-pink-300"></i>
            </button>
        @endauth


        {{-- 👁️ Tombol Detail (tidak perlu login) --}}
        <button type="button"
            onclick="openModal({{ $id }})"
            class="bg-white/20 hover:bg-blue-600 text-white w-11 h-11 flex items-center justify-center rounded-full shadow-md transition duration-300 hover:scale-110"
            title="Lihat Detail">
            <i class="fa-solid fa-eye text-lg"></i>
        </button>


        {{-- 🛒 Tombol Keranjang --}}
        @auth
            <form method="POST" action="{{ route('cart.add', $id) }}">
                @csrf
                <button type="submit"
                class="bg-pink-600 hover:bg-pink-700 text-white w-11 h-11 flex items-center justify-center rounded-full shadow-md transition duration-300 hover:scale-110"
                title="Tambah ke Keranjang">
                    <i class="fa-solid fa-cart-plus text-lg"></i>
                </button>
            </form>
        @else
            <button type="button"
                onclick="showLoginAlert()"
                class="bg-pink-600 hover:bg-pink-700 text-white w-11 h-11 flex items-center justify-center rounded-full shadow-md transition duration-300 hover:scale-110"
                title="Tambah ke Keranjang">
                <i class="fa-solid fa-cart-plus text-lg"></i>
            </button>
        @endauth

    </div>
</div>
</div>

<!-- 🔹 Popup Detail Produk -->
<div id="modal-{{ $id }}" 
    class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
<div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden animate-fadeIn">
    <div class="relative">
    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-64 object-cover">
    <button onclick="closeModal({{ $id }})"
            class="absolute top-3 right-3 bg-white/80 hover:bg-pink-600 hover:text-white rounded-full p-2 transition">
        <i class="fa-solid fa-xmark text-lg"></i>
    </button>
    </div>
    <div class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $title }}</h2>
    @if($price)
        <p class="text-pink-600 font-extrabold text-lg mb-3">
        Rp {{ number_format($price, 0, ',', '.') }}
        </p>
    @endif
    <p class="text-gray-600 text-sm leading-relaxed">
        {{ $description ?? 'Bunga segar berkualitas tinggi yang dirangkai dengan penuh cinta untuk momen spesial Anda.' }}
    </p>
    </div>
</div>
</div>

<!-- 🔸 Script Modal + Favorite -->
<script>
function openModal(id) {
    const modal = document.getElementById(`modal-${id}`);
    modal?.classList.remove('hidden');
    modal?.classList.add('flex');
}

function closeModal(id) {
    const modal = document.getElementById(`modal-${id}`);
    modal?.classList.add('hidden');
    modal?.classList.remove('flex');
}

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
            text: 'Silakan login terlebih dahulu untuk menyimpan produk favorit Anda 💖',
            confirmButtonColor: '#e91e63',
            confirmButtonText: 'OK',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#aaa'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/login'; // arahkan ke halaman login
            }
        });
        return;
    }

    const data = await response.json();
    //   console.log('Favorite Response:', data);

  // Tunggu sejenak supaya ikon SVG sempat dirender
  setTimeout(() => {
    const btn = document.getElementById(`fav-btn-${id}`);
    // if (!btn) {
    //   console.error(`❌ Tidak ditemukan tombol dengan ID fav-btn-${id}`);
    //   return;
    // }

    // Cari SVG, I, PATH di dalam tombol
    const icon = btn.querySelector('svg, i, path');

    // if (!icon) {
    //   console.error(`⚠️ Tidak ditemukan elemen ikon dalam tombol ID ${id}`);
    //   console.log('Tombol yang ditemukan:', btn.outerHTML);
    //   return;
    // }

    // Toggle class & fill warna
    if (data.status === 'added') {
      btn.classList.remove('bg-white/20');
      btn.classList.add('bg-pink-600');
      icon.classList.remove('text-pink-300');
      icon.classList.add('text-white');
      icon.style.fill = '#fff';

      Swal.fire({
        toast: true,
        icon: 'success',
        title: 'Ditambahkan ke favorit 💖',
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
    } else {
      btn.classList.remove('bg-pink-600');
      btn.classList.add('bg-white/20');
      icon.classList.remove('text-white');
      icon.classList.add('text-pink-300');
      icon.style.fill = '#f9a8d4';

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
  }, 100); // ⏱️ delay 100ms
}
 
function showLoginAlert() {
    Swal.fire({
        title: 'Login Diperlukan',
        text: 'Anda harus login untuk menggunakan fitur ini.',
        icon: 'warning',
        confirmButtonText: 'Login Sekarang',
        confirmButtonColor: '#ec4899', // pink-600
        showCancelButton: true,
        cancelButtonText: 'Batal',
        cancelButtonColor: '#6b7280' // gray-500
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('login') }}";
        }
    });
}
</script>


<!-- ✨ Animasi Halus -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.25s ease-out;
}
</style>
