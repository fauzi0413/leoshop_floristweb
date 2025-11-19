<x-layout title="Pesanan Saya">
  <section class="max-w-5xl mx-auto py-10 min-h-screen">
    <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">
      Pesanan Saya
    </h2>

    <!-- Alert -->
    @if(session('success'))
      <div class="mb-5 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="mb-5 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
      </div>
    @endif

    @if($orders->count() > 0)
      <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-pink-600 text-white">
            <tr>
              <th class="py-3 px-4 text-left text-sm font-semibold uppercase">No</th>
              <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Tanggal</th>
              <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Total</th>
              <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Status</th>
              <th class="py-3 px-4 text-left text-sm font-semibold uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-gray-700">
            @foreach($orders as $order)
              <tr class="hover:bg-pink-50 transition">
                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                <td class="py-3 px-4">{{ $order->created_at->format('d M Y, H:i') }}</td>
                <td class="py-3 px-4 font-semibold text-pink-600">
                  Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </td>
                <td class="py-3 px-4">
                  @php
                    $statusClasses = [
                      'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                      'pending_confirmation' => 'bg-orange-100 text-orange-800 border-orange-300',
                      'paid' => 'bg-green-100 text-green-800 border-green-300',
                      'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                      'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                      'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                      'returned' => 'bg-rose-100 text-rose-800 border-rose-300',
                      'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                      'failed' => 'bg-red-100 text-red-800 border-red-300',
                      'default' => 'bg-gray-100 text-gray-800 border-gray-300',
                    ];

                    // Ambil status aman (kalau array ambil index 0, kalau null fallback 'unknown')
                    $statusValue = is_array($order->status)
                        ? ($order->status[0] ?? 'unknown')
                        : ($order->status ?? 'unknown');
                  @endphp

                  <span class="px-3 py-1.5 text-sm font-semibold rounded-md border {{ $statusClasses[$statusValue] ?? $statusClasses['default'] }}">
                    {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
                  </span>
                </td>
                <td class="py-3 px-4">
                  <a href="{{ route('payment.show', $order->id) }}"
                     class="text-pink-600 hover:text-pink-700 font-medium">
                    Lihat Detail →
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="text-center text-gray-500 mt-16">
        <i class="fa-regular fa-box-open text-5xl mb-4 text-gray-400"></i>
        <p class="text-lg font-medium">Belum ada pesanan.</p>
        <a href="{{ route('shop') }}"
           class="inline-block mt-4 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2 rounded-lg shadow">
          Belanja Sekarang
        </a>
      </div>
    @endif
  </section>

  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
</x-layout>
