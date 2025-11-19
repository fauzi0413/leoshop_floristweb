<x-layout-admin title="Kelola Pesanan">
  <section class="max-w-6xl mx-auto py-10 min-h-screen flex flex-col justify-start">

    <!-- Header + Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
      <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-receipt text-pink-500"></i>
        Kelola Pesanan
      </h1>

        <!-- Filter Status -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-3">
        <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 bg-white shadow-sm">
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
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-green-500"></i>
        {{ session('success') }}
      </div>
    @endif

    <!-- Tabel Pesanan -->
    <div class="overflow-x-auto rounded-xl shadow-md border border-gray-200 bg-white">
      <table class="min-w-full divide-y divide-gray-200 text-left">
        <thead class="bg-pink-50">
          <tr class="text-pink-700 uppercase text-sm font-semibold">
            <th class="px-4 py-3">ID Pesanan</th>
            <th class="px-4 py-3">Pelanggan</th>
            <th class="px-4 py-3">Total</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
          @forelse($orders as $order)
            <tr class="hover:bg-pink-50 transition duration-200">
              <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->id }}</td>
              <td class="px-4 py-3 text-gray-700">{{ $order->name }}</td>
              <td class="px-4 py-3 text-pink-600 font-semibold">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
              </td>
              <td class="px-4 py-3">
                <!-- Status Badge -->
                @php
                    $badgeColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'pending_confirmation' => 'bg-orange-100 text-orange-800 border-orange-300',
                        'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'paid' => 'bg-green-100 text-green-800 border-green-300',
                        'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                        'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'returned' => 'bg-rose-100 text-rose-800 border-rose-300',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                        'failed' => 'bg-red-100 text-red-800 border-red-300',
                        'default' => 'bg-gray-100 text-gray-800 border-gray-300',
                    ];
                     // handle kalau status array atau null
                    $statusValue = is_array($order->status) ? ($order->status[0] ?? 'unknown') : ($order->status ?? 'unknown');
                @endphp

                <span class="px-3 py-1.5 text-sm font-semibold rounded-md border {{ $badgeColors[$statusValue] ?? $badgeColors['default'] }}">
                    {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
                </span>
              </td>
              <td class="px-4 py-3 text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
              <td class="px-4 py-3 text-center">
                <a href="{{ route('admin.orders.show', $order) }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium transition-all duration-150 flex items-center justify-center gap-1">
                  <i class="fa-solid fa-eye"></i> Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-6 text-gray-500 font-medium">
                <i class="fa-regular fa-face-sad-tear text-gray-400 mr-2"></i> Belum ada pesanan ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $orders->appends(['status' => request('status')])->links('pagination::tailwind') }}
    </div>
  </section>

  <!-- Font Awesome & SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Popup Success -->
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
