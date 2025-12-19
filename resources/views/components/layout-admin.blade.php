<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leo Shop Admin{{ isset($title) && $title ? ' | ' . $title : '' }}</title>

  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

  <style>
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="min-h-screen bg-gray-50 text-gray-800 flex flex-col">

  <!-- Navbar Admin -->
  <nav class="bg-gray-900 text-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="h-16 flex items-center justify-between">

        <!-- Logo -->
        <a href="{{ route('admin.index') }}" class="text-lg sm:text-xl font-bold text-pink-400">
          Leo Shop Admin
        </a>

        <!-- Desktop Nav Links -->
        <div class="hidden md:flex items-center gap-6">
          <a href="{{ route('admin.index') }}" class="text-sm font-medium hover:text-pink-400 transition">Dashboard</a>
          <a href="{{ route('admin.show_product') }}" class="text-sm font-medium hover:text-pink-400 transition">Kelola Produk</a>
          <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium hover:text-pink-400 transition">Kelola Pesanan</a>
          <a href="{{ route('admin.show_user') }}" class="text-sm font-medium hover:text-pink-400 transition">Kelola User</a>

          <!-- Profile -->
          <div class="relative">
            <button
              type="button"
              data-profile-btn
              class="flex items-center gap-2 bg-pink-600 hover:bg-pink-700 border border-pink-200 text-white px-3 py-1.5 rounded-full shadow-sm transition"
            >
              <img
                src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                alt="Profile"
                class="w-7 h-7 rounded-full object-cover border border-pink-300"
              >
              <span class="font-semibold text-sm">{{ Auth::user()->name }}</span>
              <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
            </button>

            <div
              data-dropdown
              class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden"
            >
              @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-pink-50">
                  <i class="fa-solid fa-gauge-high text-pink-600 w-4 text-center"></i>
                  <span>Dashboard Admin</span>
                </a>
                <hr class="border-gray-200">
              @endif

              <a href="/profile" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-pink-50">
                <i class="fa-solid fa-gear text-pink-600 w-4 text-center"></i>
                <span>Pengaturan</span>
              </a>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-pink-50">
                  <i class="fa-solid fa-right-from-bracket text-pink-600 w-4 text-center"></i>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Mobile Toggle -->
        <button
          type="button"
          class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-white/10 transition"
          aria-label="Toggle menu"
          data-mobile-toggle
        >
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div class="md:hidden pb-4 hidden" data-mobile-menu>
        <div class="mt-2 rounded-2xl border border-white/10 bg-white/5 p-3 space-y-1">
          <a href="{{ route('admin.index') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Dashboard</a>
          <a href="{{ route('admin.show_product') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Kelola Produk</a>
          <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Kelola Pesanan</a>
          <a href="{{ route('admin.show_user') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Kelola User</a>

          <div class="pt-2 mt-2 border-t border-white/10">
            <div class="flex items-center gap-2 px-3 py-2">
              <img
                src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                alt="Profile"
                class="w-8 h-8 rounded-full object-cover border border-pink-300"
              >
              <div class="text-sm">
                <div class="font-semibold">{{ Auth::user()->name }}</div>
                <div class="text-xs text-white/70">Admin Panel</div>
              </div>
            </div>

            <a href="/profile" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">
              <i class="fa-solid fa-gear mr-2 text-pink-300"></i> Pengaturan
            </a>

            <form method="POST" action="{{ route('logout') }}" class="px-1">
              @csrf
              <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-white/10">
                <i class="fa-solid fa-right-from-bracket mr-2 text-pink-300"></i> Logout
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </nav>

  <!-- Isi Halaman -->
  <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
    {{ $slot }}
  </main>

  <!-- Footer -->
  <footer class="text-center text-gray-500 py-6 text-sm">
    © 2025 Leo Shop — Admin Panel
  </footer>

  @vite('resources/js/app.js')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Profile dropdown (desktop)
      const profileBtn = document.querySelector('[data-profile-btn]');
      const dropdown = document.querySelector('[data-dropdown]');

      if (profileBtn && dropdown) {
        profileBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
          if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
          }
        });
      }

      // Mobile menu
      const toggle = document.querySelector('[data-mobile-toggle]');
      const menu = document.querySelector('[data-mobile-menu]');

      if (toggle && menu) {
        toggle.addEventListener('click', () => {
          menu.classList.toggle('hidden');
          const icon = toggle.querySelector('i');
          if (icon) icon.classList.toggle('fa-bars'), icon.classList.toggle('fa-xmark');
        });
      }
    });
  </script>

  @if(session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#e11d48'
      });
    </script>
  @endif

</body>
</html>
