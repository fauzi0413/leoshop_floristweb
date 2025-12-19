<x-layout-admin title="Daftar Produk">
  <section class="max-w-6xl mx-auto min-h-screen px-4 sm:px-6 py-10 flex flex-col">

    <!-- Header -->
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <h1 class="flex items-center gap-2 text-2xl sm:text-3xl font-extrabold text-gray-800">
        <i class="fa-solid fa-box text-pink-500"></i>
        Daftar Produk
      </h1>

      <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center md:w-auto">
        <!-- Search -->
        <form id="searchForm"
              method="GET"
              action="{{ route('admin.products.index') }}"
              class="flex w-full items-center gap-3 sm:w-auto">

          <div class="relative w-full sm:w-64">
            <input
              id="searchInput"
              type="text"
              name="search"
              value="{{ request('search') }}"
              placeholder="Cari produk berdasarkan nama..."
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                     placeholder-gray-400 focus:border-pink-400 focus:ring-2 focus:ring-pink-400"
            >

            <button
              type="button"
              id="resetSearch"
              title="Hapus pencarian"
              class="{{ request('search') ? '' : 'hidden' }}
                     absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500 transition">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>

          <button
            type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white
                   shadow hover:bg-pink-700 transition">
            <i class="fa-solid fa-magnifying-glass"></i>
            Cari
          </button>
        </form>

        <!-- Add -->
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-pink-600 px-4 py-2
                  text-sm font-semibold text-white shadow hover:bg-pink-700 transition whitespace-nowrap">
          <i class="fa-solid fa-plus"></i>
          Tambah Produk
        </a>
      </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
      <div class="mb-6 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <i class="fa-solid fa-circle-check text-green-500"></i>
        {{ session('success') }}
      </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto rounded-2xl border border-gray-200 bg-white shadow-sm">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-pink-50">
          <tr class="uppercase text-xs font-semibold text-pink-700">
            <th class="px-4 py-3 text-left">Nama Produk</th>
            <th class="px-4 py-3 text-left">Harga</th>
            <th class="px-4 py-3 text-center">Gambar</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
          @forelse($products as $product)
            <tr class="hover:bg-pink-50/60 transition">
              <td class="px-4 py-3 font-medium text-gray-800">
                {{ $product->title }}
              </td>

              <td class="px-4 py-3 font-semibold text-pink-600 whitespace-nowrap">
                Rp {{ number_format($product->price, 0, ',', '.') }}
              </td>

              <td class="px-4 py-3 text-center">
                <img
                  src="{{ asset('storage/' . $product->image) }}"
                  alt="Gambar Produk"
                  class="mx-auto h-16 w-16 rounded-lg border border-gray-200 object-cover shadow-sm"
                >
              </td>

              <td class="px-4 py-3">
                <div class="flex justify-center gap-4">
                  <a href="{{ route('admin.products.edit', $product) }}"
                     class="inline-flex items-center gap-1 font-medium text-blue-600 hover:text-blue-800 transition">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit
                  </a>

                  <form action="{{ route('admin.products.destroy', $product) }}"
                        method="POST"
                        class="delete-form">
                    @csrf
                    @method('DELETE')

                    <button type="button"
                            class="delete-button inline-flex items-center gap-1 font-medium text-red-600 hover:text-red-800 transition">
                      <i class="fa-solid fa-trash-can"></i>
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="py-8 text-center text-gray-500">
                <i class="fa-regular fa-face-sad-tear mr-2 text-gray-400"></i>
                Belum ada produk yang ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="sm:hidden px-4 py-3 text-xs text-gray-500 border-t border-gray-100">
      Geser tabel ke kanan untuk melihat kolom lainnya.
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
      {{ $products->appends(['search' => request('search')])->links('pagination::tailwind') }}
    </div>

  </section>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Delete confirm
    document.querySelectorAll('.delete-button').forEach(button => {
      button.addEventListener('click', () => {
        const form = button.closest('.delete-form');
        Swal.fire({
          title: 'Yakin ingin menghapus produk ini?',
          text: 'Data produk akan dihapus secara permanen!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#e11d48',
          cancelButtonColor: '#6b7280',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true,
        }).then(result => {
          if (result.isConfirmed) form.submit();
        });
      });
    });

    // Search reset
    const searchInput = document.getElementById('searchInput');
    const resetBtn = document.getElementById('resetSearch');
    const searchForm = document.getElementById('searchForm');

    searchInput.addEventListener('input', () => {
      resetBtn.classList.toggle('hidden', searchInput.value.trim() === '');
    });

    resetBtn.addEventListener('click', () => {
      searchInput.value = '';
      resetBtn.classList.add('hidden');
      searchForm.submit();
    });
  </script>

  @if(session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      });
    </script>
  @endif

</x-layout-admin>
