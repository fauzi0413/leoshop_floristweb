<x-layout title="Pesanan Saya">
  <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-screen">
    <h2 class="text-2xl sm:text-3xl font-bold mb-6 sm:mb-8 text-center text-gray-800">
      Pesanan Saya
    </h2>

    <!-- Alert -->
    @if(session('success'))
      <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        {{ session('error') }}
      </div>
    @endif

    @if($orders->count() > 0)

      <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <!-- Wrapper scroll -->
        <div class="overflow-x-auto">
          <table class="min-w-[720px] w-full divide-y divide-gray-200">
            <thead class="bg-pink-600 text-white sticky top-0">
              <tr>
                <th class="py-3 px-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wide">No</th>
                <th class="py-3 px-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wide">Tanggal</th>
                <th class="py-3 px-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wide">Total</th>
                <th class="py-3 px-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wide">Status</th>
                <th class="py-3 px-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wide">Aksi</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 text-gray-700 text-sm">
              @foreach($orders as $order)
                @php
                  $statusClasses = [
                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'pending_confirmation' => 'bg-orange-100 text-orange-800 border-orange-200',
                    'paid' => 'bg-green-100 text-green-800 border-green-200',
                    'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                    'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'returned' => 'bg-rose-100 text-rose-800 border-rose-200',
                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                    'rejected' => 'bg-red-100 text-red-800 border-red-200',
                    'failed' => 'bg-red-100 text-red-800 border-red-200',
                    'default' => 'bg-gray-100 text-gray-800 border-gray-200',
                  ];

                  $statusValue = is_array($order->status)
                      ? ($order->status[0] ?? 'unknown')
                      : ($order->status ?? 'unknown');

                  $statusText = ucfirst(str_replace('_', ' ', $statusValue));
                @endphp

                <tr class="hover:bg-pink-50/60 transition">
                  <td class="py-3 px-4 whitespace-nowrap">
                    {{ $loop->iteration }}
                  </td>

                  <td class="py-3 px-4 whitespace-nowrap text-gray-600">
                    {{ $order->created_at->format('d M Y, H:i') }}
                  </td>

                  <td class="py-3 px-4 whitespace-nowrap font-semibold text-pink-600">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                  </td>

                  <td class="py-3 px-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full border {{ $statusClasses[$statusValue] ?? $statusClasses['default'] }}">
                      {{ $statusText }}
                    </span>
                  </td>

                  <td class="py-3 px-4 whitespace-nowrap">
                    <a href="{{ route('payment.show', $order->id) }}"
                       class="inline-flex items-center gap-2 text-pink-700 hover:text-white hover:bg-pink-600 border border-pink-200 px-3 py-1.5 rounded-full text-xs font-semibold transition">
                      <span>Lihat Detail</span>
                      <span aria-hidden="true">→</span>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Optional: hint scroll untuk mobile -->
        <div class="sm:hidden px-4 py-3 text-xs text-gray-500 border-t border-gray-100">
          Geser tabel ke kanan untuk melihat kolom lainnya.
        </div>
      </div>

    @else
      <div class="text-center text-gray-500 mt-16">
        <i class="fa-regular fa-box-open text-5xl mb-4 text-gray-400"></i>
        <p class="text-base sm:text-lg font-medium">Belum ada pesanan.</p>
        <a href="{{ route('shop') }}"
           class="inline-flex items-center justify-center mt-4 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
          Belanja Sekarang
        </a>
      </div>
    @endif
  </section>

  <!-- Font Awesome (kalau sudah ada global di layout, ini bisa dihapus) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
</x-layout>
