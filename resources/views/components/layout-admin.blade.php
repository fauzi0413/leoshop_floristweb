<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leo Shop Admin{{ isset($title) && $title ? ' | ' . $title : '' }}</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Navbar Admin -->
    <nav class="bg-gray-900 text-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 h-16 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('admin.index') }}" class="text-2xl font-bold text-pink-400">Leo Shop Admin</a>

            <!-- Nav Links -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('admin.index') }}" class="hover:text-pink-400 transition duration-150">Dashboard</a>
                <a href="{{ route('admin.show_product') }}" class="hover:text-pink-400 transition duration-150">Kelola Produk</a>
                <a href="{{ route('admin.orders.index') }}" class="hover:text-pink-400 transition duration-150">Kelola Pesanan</a>
                <a href="{{ route('admin.show_user') }}" class="hover:text-pink-400 transition duration-150">Kelola User</a>

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
            </div>
        </div>
    </nav>

    <!-- Isi Halaman -->
    <main class="max-w-7xl mx-auto">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="text-center text-gray-500 pb-6">
        © 2025 Leo Shop — Admin Panel
    </footer>
</body>

@vite('resources/js/app.js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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


</html>
