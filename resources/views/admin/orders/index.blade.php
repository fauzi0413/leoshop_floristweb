<x-layout-admin title="Kelola Pesanan">
  <section class="max-w-6xl mx-auto min-h-screen px-4 sm:px-6 py-10 flex flex-col">

    <!-- Header + Filter -->
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <h1 class="flex items-center gap-2 text-2xl sm:text-3xl font-extrabold text-gray-800">
        <i class="fa-solid fa-receipt text-pink-500"></i>
        Kelola Pesanan
      </h1>

      <!-- Filter Status -->
      <form method="GET" action="{{ route('admin.orders.index') }}" class="w-full md:w-auto">
        <select name="status"
                onchange="this.form.submit()"
                class="w-full md:w-auto rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                       focus:border-pink-400 focus:ring-2 focus:ring-pink-400">
          <option value="">Semua Status</option>
          @foreach ($statuses as $status)
            <option value="{{ $status }}" {{ $selectedStatus == $status ? 'selected' : '' }}>
              {{ ucfirst($status) }}
            </option>
          @endforeach
        </select>
      </form>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
      <div class="mb-6 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <i class="fa-solid fa-circle-check text-green-500"></i>
        {{ session('success') }}
      </div>
    @endif

    <!-- Tabel Pesanan -->
    <div class="overflow-x-auto rounded-2xl border border-gray-200 bg-white shadow-sm">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-pink-50">
          <tr class="uppercase text-xs font-semibold text-pink-700">
            <th class="px-4 py-3 text-left">ID Pesanan</th>
            <th class="px-4 py-3 text-left">Pelanggan</th>
            <th class="px-4 py-3 text-left">Total</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Tanggal</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
          @forelse($orders as $order)
            @php
              $badgeColors = [
                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'pending_confirmation' => 'bg-orange-100 text-orange-800 border-orange-200',
                'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                'paid' => 'bg-green-100 text-green-800 border-green-200',
                'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'returned' => 'bg-rose-100 text-rose-800 border-rose-200',
                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                'failed' => 'bg-red-100 text-red-800 border-red-200',
                'default' => 'bg-gray-100 text-gray-800 border-gray-200',
              ];

              $statusValue = is_array($order->status)
                ? ($order->status[0] ?? 'unknown')
                : ($order->status ?? 'unknown');
            @endphp

            <tr class="hover:bg-pink-50/60 transition">
              <td class="px-4 py-3 font-medium text-gray-800 whitespace-nowrap">#{{ $order->id }}</td>
              <td class="px-4 py-3 text-gray-700">{{ $order->name }}</td>
              <td class="px-4 py-3 font-semibold text-pink-600 whitespace-nowrap">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
              </td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-semibold
                             {{ $badgeColors[$statusValue] ?? $badgeColors['default'] }}">
                  {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
                </span>
              </td>
              <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $order->created_at->format('d M Y') }}</td>
              <td class="px-4 py-3 text-center">
                <a href="{{ route('admin.orders.show', $order) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-full border border-blue-200 px-3 py-1.5
                          font-semibold text-blue-600 hover:bg-blue-50 hover:text-blue-800 transition">
                  <i class="fa-solid fa-eye"></i>
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-8 text-center text-gray-500">
                <i class="fa-regular fa-face-sad-tear mr-2 text-gray-400"></i>
                Belum ada pesanan ditemukan.
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
      {{ $orders->appends(['status' => request('status')])->links('pagination::tailwind') }}
    </div>

  </section>

  <!-- Font Awesome & SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
