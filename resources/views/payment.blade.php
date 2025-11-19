<x-layout title="Pembayaran">
  <section class="max-w-3xl mx-auto py-10">
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 border border-pink-600 text-pink-600 font-medium text-sm px-3 py-1.5 rounded-md hover:bg-pink-50 transition">
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

    <div class="bg-gray-50 border rounded-lg p-5 mb-6">
      <h4 class="font-semibold text-lg mb-3 text-gray-800">Detail Penerima</h4>
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
          <li> <strong>Alasan Anda:</strong> {{ $order->cancel_reason ? $order->cancel_reason : '-'}} </li>
        @endif

      </ul>
    </div>

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
    
    @if ($order->status === 'pending' || $order->status === 'pending_confirmation')
      <div class="bg-gray-50 border rounded-lg p-5 mt-8">
          <h4 class="font-semibold text-lg mb-3 text-gray-800">Metode Pembayaran</h4>
          <p class="text-gray-700 mb-4">
              Silakan lakukan transfer ke rekening berikut untuk menyelesaikan pembayaran Anda:
          </p>

          <div class="bg-white border rounded-lg p-5 shadow-sm">
              <div class="space-y-1">
              <p><strong>Bank:</strong> BCA</p>
              <p><strong>Atas Nama:</strong> Florist by Amalia</p>
              </div>

              <!-- Nomor Rekening + Tombol Salin -->
              <div class="flex items-center justify-between bg-pink-50 border border-pink-200 rounded-lg px-4 py-3 mt-3">
              <div>
                  <p class="font-semibold text-gray-700 text-sm">No. Rekening:</p>
                  <p id="bankNumber" class="text-lg font-bold text-gray-900 tracking-wide">1234567890</p>
              </div>
              <button id="copyBtn"
                  class="bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold px-4 py-2 rounded-md shadow transition">
                  <i class="fa-solid fa-copy mr-1"></i> Salin
              </button>
              </div>
          </div>

          <!-- Form Upload Bukti -->
          <div class="bg-white border rounded-lg p-5 shadow-sm mt-6">
              <h4 class="font-semibold mb-2 text-gray-800">Upload Bukti Pembayaran</h4>
              <p class="text-sm text-gray-600 mb-3">Format: JPG, PNG, atau PDF (maks 5MB).</p>

              <form action="{{ route('payment.upload_proof', $order->id) }}" 
                      method="POST" enctype="multipart/form-data" 
                      class="space-y-4 text-center">
                  @csrf

                  <div>
                  <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf"
                          class="w-full border border-gray-300 rounded-lg p-2 text-sm 
                                  file:mr-3 file:py-2 file:px-3 file:rounded-md 
                                  file:border-0 file:bg-pink-600 file:text-white hover:file:bg-pink-700">
                  @error('payment_proof')
                      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror
                  </div>

                  <!-- Tombol akan menyesuaikan otomatis -->
                  <button type="submit"
                          class="w-full sm:w-auto bg-pink-600 hover:bg-pink-700 text-white 
                              font-semibold px-5 py-2 rounded-lg shadow transition">
                  <i class="fa-solid fa-cloud-arrow-up mr-1"></i>
                  {{ $order->payment_proof ? 'Ganti Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
                  </button>

                  @if($order->payment_proof)
                  <h5 class="font-semibold text-gray-800 mb-3">Bukti Pembayaran</h5>
                    <div class="mt-6 bg-white border rounded-lg p-5 shadow-sm flex flex-col items-center justify-center text-center">

                      @php
                        $ext = pathinfo($order->payment_proof, PATHINFO_EXTENSION);
                      @endphp

                      @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                            alt="Bukti Pembayaran" 
                            class="rounded-lg border shadow-sm w-auto max-w-xs sm:max-w-sm mb-3">
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" 
                          target="_blank"
                          class="inline-flex items-center gap-1 text-pink-600 hover:text-pink-700 font-medium text-sm transition">
                          <i class="fa-solid fa-eye"></i> Lihat Bukti
                        </a>
                      @elseif(strtolower($ext) === 'pdf')
                        <p class="text-sm text-gray-700">
                          File PDF bukti pembayaran Anda:
                          <a href="{{ asset('storage/' . $order->payment_proof) }}" 
                            target="_blank" 
                            class="text-pink-600 hover:underline font-medium">
                            Lihat / Download PDF
                          </a>
                        </p>
                      @endif
                    </div>
                  @endif

              </form>
          </div>
          
          @if($order->status === 'pending' && !$order->payment_proof)
            <form id="cancelForm" action="{{ route('order.cancel', $order->id) }}" method="POST" class="mt-4 text-center">
              @csrf
              @method('PUT')

              <!-- hidden input untuk alasan pembatalan -->
              <input type="hidden" name="cancel_reason" id="cancelReasonInput">

              <button type="button"
                      id="cancelBtn"
                      class="inline-flex items-center gap-2 border border-red-600 text-red-600 font-medium text-sm px-4 py-2 rounded-md hover:bg-red-50 transition"> 
                <i class="fa-solid fa-ban mr-1"></i> Batalkan Pesanan
              </button>
            </form>
          @endif

      </div>
    @endif
     
    {{-- Jika barang sudah dikirim --}}
    @if ($order->status === 'shipped')
      <div class="bg-gray-50 border rounded-lg p-5 mt-8">
        <h4 class="font-semibold text-lg mb-3 text-gray-800">Bukti Pengiriman</h4>

        @if ($order->shipping_proof)
          @php $ext = pathinfo($order->shipping_proof, PATHINFO_EXTENSION); @endphp
          @if (in_array(strtolower($ext), ['jpg','jpeg','png']))
            <img src="{{ asset('storage/' . $order->shipping_proof) }}" 
                alt="Bukti Pengiriman"
                class="mx-auto block rounded-lg border w-full max-w-sm shadow-sm mb-4">
          @else
            <p class="text-sm text-gray-700">
              Bukti pengiriman: 
              <a href="{{ asset('storage/' . $order->shipping_proof) }}" 
                target="_blank"
                class="text-pink-600 hover:underline font-medium">
                Lihat File
              </a>
            </p>
          @endif
        @endif

        <div class="text-center mt-6 space-x-3">
          {{-- Tombol Pesanan Diterima --}}
          <form action="{{ route('order.confirm_received', $order->id) }}" method="POST" class="inline-block">
            @csrf
            <button type="submit" 
              class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition flex items-center justify-center">
              <i class="fa-solid fa-circle-check mr-2"></i> 
              Pesanan Diterima
            </button>
          </form>

          {{-- Tombol Ajukan Pengembalian --}}
          {{-- <form action="{{ route('order.return_request', $order->id) }}" method="POST" class="inline-block">
            @csrf
            <button type="submit" 
              class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition flex items-center justify-center">
              <i class="fa-solid fa-rotate-left mr-2"></i>
              Ajukan Pengembalian
            </button>
          </form> --}}
        </div>
      </div>
    @endif

    {{-- Jika sudah diterima --}}
    @if ($order->status === 'completed')
      <div class="bg-green-50 border rounded-lg p-5 mt-8">
        <h4 class="font-semibold text-lg mb-2 text-gray-800">Pesanan Telah Diterima</h4>
        <p class="text-gray-700">Terima kasih sudah berbelanja 💐</p>

        {{-- @php
          $receivedDate = \Carbon\Carbon::parse($order->updated_at);
          $canReturn = now()->diffInDays($receivedDate) <= 3;
        @endphp

        @if ($canReturn)
          <form action="{{ route('order.return_request', $order->id) }}" method="POST" class="mt-4 text-center">
            @csrf
            <button type="button" id="returnBtn"
                    class="inline-flex items-center gap-2 border border-rose-600 text-rose-600 font-medium text-sm px-4 py-2 rounded-md hover:bg-rose-50 transition">
              <i class="fa-solid fa-rotate-left"></i> Ajukan Pengembalian Barang
            </button>
          </form>
        @endif --}}
      </div>
    @endif

    <div class="text-center mt-8">
      <a href="{{ route('orders.index') }}" class="text-pink-600 hover:underline">
        Kembali ke Daftar Pesanan
      </a>
    </div>
  </section>
</x-layout>

<script>
document.getElementById('copyBtn').addEventListener('click', async function() {
  const bankNumber = document.getElementById('bankNumber').innerText.trim();
  try {
    await navigator.clipboard.writeText(bankNumber);
    this.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Disalin!';
    this.classList.add('bg-green-600');
    setTimeout(() => {
      this.innerHTML = '<i class="fa-solid fa-copy mr-1"></i> Salin';
      this.classList.remove('bg-green-600');
    }, 1500);
  } catch (e) {
    alert('Gagal menyalin nomor rekening, salin manual: ' + bankNumber);
  }
});

// 🟥 Tombol Batalkan Pesanan (SweetAlert)
const cancelBtn = document.getElementById('cancelBtn');

if (cancelBtn) {
  cancelBtn.addEventListener('click', async function() {

    const result = await Swal.fire({
      title: 'Batalkan Pesanan?',
      html: `
        <p class="text-gray-600 mb-3">Pilih alasan pembatalan pesanan Anda:</p>
        <div id="reasonList" class="text-left space-y-2">
          <label class="flex items-center gap-2">
            <input type="radio" name="reason" value="Ingin mengubah pesanan" class="accent-pink-600">
            Ingin mengubah pesanan
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="reason" value="Pesanan salah / tidak sesuai" class="accent-pink-600">
            Pesanan salah / tidak sesuai
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="reason" value="Menemukan harga lebih murah di tempat lain" class="accent-pink-600">
            Menemukan harga lebih murah di tempat lain
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="reason" value="Ingin memilih produk yang lain" class="accent-pink-600">
            Ingin memilih produk yang lain
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="reason" value="Tidak jadi membeli" class="accent-pink-600">
            Tidak jadi membeli
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="reason" value="other" class="accent-pink-600">
            Lainnya
          </label>
          <input type="text" id="otherInput" placeholder="Tulis alasan Anda..." 
                 class="mt-2 w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-2 focus:ring-pink-400 hidden">
        </div>
      `,
      showCancelButton: true,
      confirmButtonText: 'Batalkan Pesanan',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#6b7280',
      preConfirm: () => {
        const selected = document.querySelector('input[name="reason"]:checked');
        if (!selected) {
          Swal.showValidationMessage('Pilih salah satu alasan!');
          return false;
        }
        const value = selected.value === 'other'
          ? document.getElementById('otherInput').value.trim()
          : selected.value;

        if (selected.value === 'other' && !value) {
          Swal.showValidationMessage('Masukkan alasan pada kolom lainnya!');
          return false;
        }

        return value;
      },
      didOpen: () => {
        // toggle input jika "other" dipilih
        const radios = document.querySelectorAll('input[name="reason"]');
        const otherInput = document.getElementById('otherInput');

        radios.forEach(radio => {
          radio.addEventListener('change', function() {
            if (this.value === 'other') {
              otherInput.classList.remove('hidden');
              otherInput.focus();
            } else {
              otherInput.classList.add('hidden');
              otherInput.value = '';
            }
          });
        });
      }
    });

    if (!result.isConfirmed || !result.value) return;

    // Konfirmasi akhir sebelum submit
    const confirmCancel = await Swal.fire({
      title: 'Yakin ingin membatalkan?',
      text: 'Pesanan akan dibatalkan dan tidak dapat dikembalikan.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, batalkan',
      cancelButtonText: 'Tidak',
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#6b7280',
    });

    if (confirmCancel.isConfirmed) {
      document.getElementById('cancelReasonInput').value = result.value;
      document.getElementById('cancelForm').submit();
    }
  });
}

// 🟩 Tombol Ajukan Pengembalian Barang (SweetAlert)
const returnBtn = document.getElementById('returnBtn');
if (returnBtn) {
  returnBtn.addEventListener('click', async function() {
    const { value: reason } = await Swal.fire({
      title: 'Ajukan Pengembalian Barang',
      text: 'Masukkan alasan pengembalian:',
      input: 'text',
      inputPlaceholder: 'Contoh: Barang rusak saat diterima...',
      showCancelButton: true,
      confirmButtonText: 'Kirim Pengajuan',
      cancelButtonText: 'Batal',
      inputValidator: (value) => {
        if (!value.trim()) return 'Alasan tidak boleh kosong!';
      },
    });
    if (reason) {
      const form = this.closest('form');
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'reason';
      hiddenInput.value = reason;
      form.appendChild(hiddenInput);
      form.submit();
    }
  });
}
</script>
