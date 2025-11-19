<x-layout-admin title="Daftar User">
  <section class="max-w-6xl mx-auto py-10 min-h-screen flex flex-col justify-start">

    <!-- Header + Pencarian -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

      <!-- Kiri: Judul -->
      <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-users text-pink-500"></i>
        Daftar User
      </h1>

      <!-- Kanan: Search + Tambah -->
      <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
        <form id="searchForm" method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-3 w-full sm:w-auto">
          <div class="relative flex items-center gap-2">
            <input 
              type="text" 
              name="search" 
              value="{{ request('search') }}" 
              placeholder="Cari user berdasarkan nama atau email..." 
              id="searchInput"
              class="w-full sm:w-64 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 placeholder-gray-400"
            >

            <!-- Tombol Reset -->
            <button type="button" 
                    id="resetSearch"
                    class="{{ request('search') ? '' : 'hidden' }} absolute right-2 text-gray-400 hover:text-pink-500 transition"
                    title="Hapus pencarian">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>

          <button type="submit" 
                  class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg shadow transition duration-200 flex items-center gap-2">
            <i class="fa-solid fa-magnifying-glass"></i> Cari
          </button>
        </form>

        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200 whitespace-nowrap">
          <i class="fa-solid fa-user-plus"></i> Tambah User
        </a>
      </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-green-500"></i>
        {{ session('success') }}
      </div>
    @endif

    <!-- Tabel User -->
    <div class="overflow-x-auto rounded-xl shadow-md border border-gray-200 bg-white">
      <table class="min-w-full divide-y divide-gray-200 text-left">
        <thead class="bg-pink-50">
          <tr class="text-pink-700 uppercase text-sm font-semibold">
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Role</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100 bg-white">
          @forelse($users as $user)
            <tr class="hover:bg-pink-50 transition duration-200">
              <td class="px-4 py-3 font-medium text-gray-800">{{ $user->name }}</td>
              <td class="px-4 py-3 text-gray-700">{{ $user->email }}</td>
              <td class="px-4 py-3 text-pink-600 font-semibold capitalize">{{ $user->role }}</td>
              <td class="px-4 py-3 text-center">
                <div class="flex justify-center items-center gap-3">
                  <a href="{{ route('admin.users.edit', $user) }}" 
                     class="text-blue-600 hover:text-blue-800 font-medium transition-all duration-150 flex items-center gap-1">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                  </a>

                  <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline delete-form">
                    @csrf @method('DELETE')
                    <button type="button" 
                            class="text-red-600 hover:text-red-800 font-medium transition-all duration-150 delete-button flex items-center gap-1">
                      <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center py-6 text-gray-500 font-medium">
                <i class="fa-regular fa-face-sad-tear text-gray-400 mr-2"></i> Belum ada user yang terdaftar.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $users->appends(['search' => request('search')])->links('pagination::tailwind') }}
    </div>
  </section>

  <!-- Font Awesome & SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Konfirmasi Hapus -->
  <script>
    document.querySelectorAll('.delete-button').forEach(button => {
      button.addEventListener('click', function() {
        const form = this.closest('.delete-form');
        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: "Data user ini akan dihapus secara permanen!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#e11d48',
          cancelButtonColor: '#6b7280',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          customClass: {
            confirmButton: 'rounded-md px-4 py-2',
            cancelButton: 'rounded-md px-4 py-2'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // Search reset handler
    const searchInput = document.getElementById('searchInput');
    const resetBtn = document.getElementById('resetSearch');
    const searchForm = document.getElementById('searchForm');

    searchInput.addEventListener('input', () => {
      if (searchInput.value.trim() !== '') {
        resetBtn.classList.remove('hidden');
      } else {
        resetBtn.classList.add('hidden');
      }
    });

    resetBtn.addEventListener('click', () => {
      searchInput.value = '';
      resetBtn.classList.add('hidden');
      searchForm.submit();
    });
  </script>
</x-layout-admin>
