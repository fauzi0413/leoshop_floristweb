<x-layout title="Checkout">
  <section class="max-w-3xl mx-auto py-10">
    <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Checkout</h2>

    <form action="{{ route('order.process') }}" method="POST" class="space-y-6">
      @csrf

      <div>
        <label class="block mb-1 font-medium">Nama</label>
        <input type="text" name="name" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block mb-1 font-medium">Nomor WhatsApp</label>
        <input type="text" name="whatsapp" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block mb-1 font-medium">Alamat Lengkap</label>
        <textarea name="address" rows="3" class="w-full border rounded-lg p-2" required></textarea>
      </div>

      <div class="bg-gray-50 border rounded-lg p-4">
        <h4 class="font-semibold mb-2">Ringkasan Pesanan</h4>
        @foreach($cartItems as $item)
          <div class="flex justify-between border-b py-1 text-gray-700">
            <span>{{ $item->product->title }} (x{{ $item->quantity }})</span>
            <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
          </div>
        @endforeach
        <p class="text-right font-bold mt-3">
          Total: Rp {{ number_format($total, 0, ',', '.') }}
        </p>
      </div>

      <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-3 rounded-lg font-semibold">
        Buat Pesanan
      </button>
    </form>
  </section>
</x-layout>
