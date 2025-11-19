<x-layout title="Pembayaran Midtrans">
  <div class="max-w-3xl mx-auto py-20 text-center">
    <h1 class="text-3xl font-bold mb-6">Proses Pembayaran</h1>
    <p>Total Pembayaran: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></p>

    <button id="pay-button" 
            class="mt-8 bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg shadow">
      Bayar Sekarang 💳
    </button>
  </div>

  <script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
  </script>

  <script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
      window.snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){ console.log('✅ Pembayaran sukses:', result); },
        onPending: function(result){ console.log('🕒 Menunggu pembayaran:', result); },
        onError: function(result){ console.error('❌ Error:', result); },
        onClose: function(){ alert('Kamu menutup popup tanpa menyelesaikan pembayaran.'); }
      });
    });
  </script>
</x-layout>
