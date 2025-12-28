<x-layout-admin title="Detail Pesanan #{{ $order->id }}">
  <section class="max-w-4xl mx-auto py-10">
    <div class="mb-6">
      <a href="{{ route('admin.orders.index') }}" 
         class="inline-flex items-center gap-1.5 border border-pink-600 text-pink-600 font-medium text-sm px-3 py-1.5 rounded-md hover:bg-pink-50 transition">
        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
      </a>
    </div>

    <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">
      Detail Pesanan #{{ $order->id }}
    </h2>

    @if (session('success'))
      <div class="mb-5 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    <!-- Detail Pelanggan -->
    <div class="bg-gray-50 border rounded-lg p-5 mb-6">
      <h4 class="font-semibold text-lg mb-3 text-gray-800">Detail Pelanggan</h4>
      <ul class="space-y-1 text-gray-700">
        <li><strong>Nama:</strong> {{ $order->name }}</li>
        <li><strong>Nomor WhatsApp:</strong> {{ $order->whatsapp }}</li>
        <li><strong>Alamat:</strong> {{ $order->address }}</li>
        <li class="flex items-center gap-2">
          <strong>Status:</strong>

          @php
            $statusColors = [
              'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
              'pending_confirmation' => 'bg-orange-100 text-orange-800 border-orange-300',
              'paid' => 'bg-green-100 text-green-800 border-green-300',
              'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
              'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
              'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
              'returned' => 'bg-rose-100 text-rose-800 border-rose-300',
              'cancelled' => 'bg-red-100 text-red-800 border-red-300',
              'rejected' => 'bg-red-100 text-red-800 border-red-200',
              'failed' => 'bg-red-100 text-red-800 border-red-300',
              'default' => 'bg-gray-100 text-gray-800 border-gray-300',
            ];

            // handle kalau status array atau null
            $statusValue = is_array($order->status) ? ($order->status[0] ?? 'unknown') : ($order->status ?? 'unknown');
          @endphp

          <span class="px-3 py-1.5 text-sm font-semibold rounded-md border {{ $statusColors[$statusValue] ?? $statusColors['default'] }}">
            {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
          </span>
        </li>
        @if ($order->status === 'cancelled')
          <li><strong>Alasan Pembatalan Pembeli:</strong> {{ $order->cancel_reason ?: '-' }}</li>
        @endif

        @if ($order->status === 'rejected')
          <li><strong>Alasan Penolakan Pesanan:</strong> {{ $order->reject_reason ?: '-' }}</li>
        @endif

        @if ($order->status === 'completed' && $order->received_at)
          <li>
            <strong>Waktu Pesanan Diterima:</strong>
            <span class="text-gray-700">
              {{ \Carbon\Carbon::parse($order->received_at)->translatedFormat('d F Y, H:i') }} WIB
            </span>
          </li>
        @endif

      </ul>
    </div>

    <!-- Produk Dipesan -->
    <div class="bg-white border rounded-lg p-5 mb-6">
      <h4 class="font-semibold text-lg mb-3 text-gray-800">Produk Dipesan</h4>
      <ul class="divide-y divide-gray-200">
        @foreach ($order->items as $item)
          <li class="flex justify-between py-2">
            <span>{{ $item->product->title }} (x{{ $item->quantity }})</span>
            <span>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
          </li>
        @endforeach
      </ul>
      <p class="text-right font-bold mt-4">
        Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
      </p>
    </div>

    <!-- Bukti Pembayaran -->
    <div class="bg-white border rounded-lg p-5 shadow-sm">
      <h4 class="font-semibold text-lg mb-3 text-gray-800">Bukti Pembayaran</h4>

      @if ($order->payment_proof)
        @php $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION); @endphp
        <div class="text-center">
          @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
            <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                 alt="Bukti Pembayaran" 
                 class="mx-auto block rounded-lg border w-full max-w-sm shadow-sm">
            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
               class="inline-block mt-3 text-pink-600 hover:underline text-sm font-medium">
              <i class="fa-solid fa-eye mr-1"></i> Lihat Bukti
            </a>
          @elseif (strtolower($ext) === 'pdf')
            <p class="text-sm text-gray-700">
              File PDF bukti pembayaran tersedia:
              <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-pink-600 hover:underline font-medium">
                Lihat / Download PDF
              </a>
            </p>
          @endif
        </div>
      @else
        <p class="text-gray-600 text-center italic">Belum ada bukti pembayaran yang diunggah oleh pelanggan.</p>
      @endif
    </div>

    <!-- Konfirmasi Pembayaran -->
    @if ($order->status === 'pending_confirmation')
      <div class="text-center mt-6 flex flex-col sm:flex-row gap-3 justify-center">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
          @csrf @method('PUT')
          <input type="hidden" name="status" value="processing">
          <button type="submit"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
            <i class="fa-solid fa-check mr-1"></i> Konfirmasi Pembayaran
          </button>
        </form>

        <form id="rejectForm" action="{{ route('admin.orders.update', $order) }}" method="POST">
          @csrf @method('PUT')
          <input type="hidden" name="status" value="rejected">
          <input type="hidden" name="reject_reason" id="rejectReasonInput">
          <button type="button" id="btnReject"
            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
            <i class="fa-solid fa-ban mr-1"></i> Tolak Pesanan
          </button>
        </form>
      </div>
    @endif

    <!-- Kirim Barang -->
    @if ($order->status === 'processing')
      <div class="mt-8 bg-gray-50 border rounded-lg p-5">
        <h4 class="font-semibold mb-3 text-gray-800">Kirim Barang</h4>
        <form action="{{ route('admin.orders.update', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
          @csrf @method('PUT')
          <input type="hidden" name="status" value="shipped">
          <div>
            <label class="font-medium text-gray-700 text-sm">Upload Bukti Pengiriman (resinya)</label>
            <input type="file" name="shipping_proof" accept=".jpg,.jpeg,.png,.pdf"
                   class="w-full border border-gray-300 rounded-lg p-2 text-sm 
                   file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:bg-pink-600 file:text-white hover:file:bg-pink-700">
          </div>
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
            <i class="fa-solid fa-truck mr-1"></i> Kirim Barang
          </button>
        </form>
      </div>
    @endif

    <!-- Bukti Pengiriman -->
    <div class="bg-white border rounded-lg p-5 shadow-sm mt-6">
      <h4 class="font-semibold text-lg mb-3 text-gray-800">Bukti Pengiriman</h4>

      @if ($order->shipping_proof)
        @php $ext = pathinfo($order->shipping_proof, PATHINFO_EXTENSION); @endphp
        <div class="text-center">
          @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
            <img src="{{ asset('storage/' . $order->shipping_proof) }}" 
                alt="Bukti Pengiriman" 
                class="mx-auto block rounded-lg border w-full max-w-sm shadow-sm">
            <a href="{{ asset('storage/' . $order->shipping_proof) }}" 
              target="_blank"
              class="inline-block mt-3 text-pink-600 hover:underline text-sm font-medium">
              <i class="fa-solid fa-eye mr-1"></i> Lihat Bukti Pengiriman
            </a>
          @elseif (strtolower($ext) === 'pdf')
            <p class="text-sm text-gray-700">
              File PDF bukti pengiriman tersedia:
              <a href="{{ asset('storage/' . $order->shipping_proof) }}" 
                target="_blank" 
                class="text-pink-600 hover:underline font-medium">
                Lihat / Download PDF
              </a>
            </p>
          @endif
        </div>
      @else
        <p class="text-gray-600 text-center italic">
          Belum ada bukti pengiriman yang diunggah oleh admin.
        </p>
      @endif
    </div>

    <div class="text-center mt-8">
      <a href="{{ route('admin.orders.index') }}" class="text-pink-600 hover:underline">Kembali ke Daftar Pesanan</a>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('btnReject');
      if (!btn) return;

      btn.addEventListener('click', async () => {
        const { value: reason } = await Swal.fire({
          title: 'Tolak Pesanan',
          input: 'textarea',
          inputLabel: 'Alasan penolakan admin (wajib)',
          inputPlaceholder: 'Contoh: Stok habis / bukti pembayaran tidak valid / alamat tidak lengkap ...',
          inputAttributes: { maxlength: 255 },
          showCancelButton: true,
          confirmButtonText: 'Tolak',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#dc2626',
          inputValidator: (value) => {
            if (!value || !value.trim()) return 'Alasan wajib diisi.';
            return null;
          }
        });

        if (reason) {
          document.getElementById('rejectReasonInput').value = reason.trim();
          document.getElementById('rejectForm').submit();
        }
      });
    });
  </script>

</x-layout-admin>
