<x-layout>
  <section class="flex flex-col items-center justify-center min-h-[70vh] text-center">
    @if ($status === 'success')
      <div class="text-green-600 text-5xl mb-4">🎉</div>
      <h1 class="text-3xl font-bold text-green-600 mb-2">Pembayaran Berhasil!</h1>
      <p class="text-gray-700 mb-6">Terima kasih telah berbelanja di <b>Florist by Amalia</b>.</p>

    @elseif ($status === 'pending')
      <div class="text-yellow-500 text-5xl mb-4">⌛</div>
      <h1 class="text-3xl font-bold text-yellow-500 mb-2">Menunggu Pembayaran</h1>
      <p class="text-gray-700 mb-6">Transaksi kamu masih diproses. Silakan cek kembali nanti ya 🌸</p>

    @elseif ($status === 'failed')
      <div class="text-red-600 text-5xl mb-4">❌</div>
      <h1 class="text-3xl font-bold text-red-600 mb-2">Pembayaran Gagal</h1>
      <p class="text-gray-700 mb-6">Terjadi kesalahan saat memproses pembayaran. Coba lagi nanti ya.</p>
    @endif

    <a href="{{ route('shop') }}" class="mt-6 bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg shadow">
      Kembali ke Toko
    </a>
  </section>
</x-layout>
