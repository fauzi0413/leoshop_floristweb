<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leo Shop{{ isset($title) && $title ? ' | ' . $title : '' }}</title>     

  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
  
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>
<body class="font-sans text-gray-800">

  <!-- Navbar -->
  <header class="bg-pink-100/90 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto flex justify-between items-center px-5 py-2.5">
      <a href="/" class="font-bold text-xl text-pink-700 flex items-center gap-1">🌸 <span>Leo Shop</span></a>

      <div class="flex items-center gap-6 text-gray-700 text-sm font-medium">
        <a href="/" class="hover:text-pink-700 transition">Beranda</a>
        <a href="/shop" class="hover:text-pink-700 transition">Produk</a>
        <a href="/#about" data-target="about" class="scroll-trigger hover:text-pink-700 transition">Tentang</a>
        <a href="/#contact" data-target="contact" class="scroll-trigger hover:text-pink-700 transition">Kontak</a>
        @guest
          <div class="flex items-center gap-2">
            <a href="/register" class="outline outline-1 outline-pink-600 text-pink-700 hover:bg-pink-600 hover:text-white px-3 py-1.5 rounded-md font-semibold text-sm transition duration-300">Daftar</a>
            <a href="/login" class="bg-pink-600 hover:bg-pink-700 text-white px-3 py-1.5 rounded-md font-semibold text-sm transition duration-300">Masuk</a>
          </div>
        @endguest

        @auth

          <div class="relative">
            <button data-profile-btn class="flex items-center gap-2 bg-pink-600 hover:bg-pink-800 border border-pink-200 text-white px-3 py-1.5 rounded-full shadow-sm transition duration-300">
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                    alt="Profile" 
                    class="w-7 h-7 rounded-full object-cover border border-pink-300">
                <span class="font-semibold text-sm">{{ Auth::user()->name }}</span>
                <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
            </button>

            <div data-dropdown class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
              {{-- Jika user admin, tampilkan link dashboard --}}
              @if (Auth::user()->role === 'admin')
                  <a href="{{ route('admin.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50">
                      <i class="fa-solid fa-gauge-high text-pink-600 w-4 text-center"></i>
                      <span>Dashboard Admin</span>
                  </a>
                  <hr class="border-gray-200 my-1">
              @endif

              {{-- Link ke Pengaturan --}}
              <a href="/profile" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-pink-50">
                  <i class="fa-solid fa-gear text-pink-600 w-4 text-center"></i>
                  <span>Pengaturan</span>
              </a>

              {{-- Tombol Logout --}}
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-pink-50">
                      <i class="fa-solid fa-right-from-bracket text-pink-600 w-4 text-center"></i>
                      <span>Logout</span>
                  </button>
              </form>
            </div>

          </div>

        @endauth

      </div>
    </nav>
  </header>

  <main>
    {{ $slot }}
  </main>

  <footer class="bg-white text-gray-900 text-center py-8">
    <p>
      © {{ date('Y') }} 
      <span class="font-semibold text-pink-600">Leo Shop</span> 🌸<br>
      <span class="text-xs text-gray-400">Dibuat dengan ❤️ untuk keindahan setiap momen</span>
    </p>
  </footer>

  <!-- 🔥 Script diletakkan DI SINI agar eksekusi setelah slot dimuat -->
  <script>
  document.addEventListener("DOMContentLoaded", () => {
      const links = document.querySelectorAll(".scroll-trigger");

      links.forEach(link => {
          link.addEventListener("click", function (e) {
              e.preventDefault();
              const targetId = this.dataset.target;
              const currentPath = window.location.pathname;

              // Jika bukan di home → redirect ke home + hash
              if (currentPath !== "/") {
                  window.location.href = `/#${targetId}`;
                  return;
              }

              // Kalau di home → langsung scroll
              const section = document.getElementById(targetId);
              if (section) {
                  section.scrollIntoView({ behavior: "smooth", block: "start" });
              }
          });
      });

      // Kalau datang dari halaman lain (misal /shop → /#about)
      if (window.location.hash) {
          const hash = window.location.hash.substring(1);
          const section = document.getElementById(hash);
          if (section) {
              setTimeout(() => {
                  section.scrollIntoView({ behavior: "smooth", block: "start" });
              }, 800); // sedikit delay agar konten sudah siap
          }
      }
  });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const profileBtn = document.querySelector('[data-profile-btn]');
      const dropdown = document.querySelector('[data-dropdown]');
      if (!profileBtn || !dropdown) return;

      profileBtn.addEventListener('click', () => {
        dropdown.classList.toggle('hidden');
      });

      window.addEventListener('click', e => {
        if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
      });
    });
  </script>
  
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
      const checkoutBtn = document.getElementById('checkout-btn');

      checkoutBtn.addEventListener('click', function () {
          fetch("{{ secure_url(route('cart.checkout.midtrans', [], false)) }}", {
              method: "POST",
              headers: {
                  "X-CSRF-TOKEN": "{{ csrf_token() }}",
                  "Content-Type": "application/json",
              },
              body: JSON.stringify({})
          })
          .then(response => response.json())
          .then(data => {
              if (data.snap_token) {
                  // ✅ Tampilkan popup Midtrans
                  window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                      saveOrder(result);
                      window.location.href = "{{ route('payment.status', 'success') }}";
                    },
                    onPending: function(result) {
                      saveOrder(result);
                      window.location.href = "{{ route('payment.status', 'pending') }}";
                    },
                    onError: function(result) {
                      saveOrder(result);
                      window.location.href = "{{ route('payment.status', 'failed') }}";
                    },
                    onClose: function() {
                      alert("Kamu menutup popup sebelum menyelesaikan pembayaran!");
                    }
                  });
                } else {
                  alert("Gagal mendapatkan token pembayaran.");
              }
          })
          .catch(error => console.error("Error:", error));
      });

      // Fungsi untuk simpan order ke server
      function saveOrder(result) {
          fetch("{{ route('midtrans.callback') }}", {
              method: "POST",
              headers: {
                  "X-CSRF-TOKEN": "{{ csrf_token() }}",
                  "Content-Type": "application/json",
              },
              body: JSON.stringify(result)
          })
          .then(res => res.json())
          .then(data => {
              console.log("🧾 Order tersimpan:", data);
              alert("Pesanan kamu sudah diproses!");
              window.location.href = "/shop"; // redirect ke halaman shop
          })
          .catch(err => console.error("Error saving order:", err));
      }
  });
  </script>

</body>
</html>
