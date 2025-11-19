@php
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

$cartItems = Auth::check()
    ? Cart::with('product')->where('user_id', Auth::id())->get()
    : collect();
$total = $cartItems->sum(fn($item) => $item->price * $item->quantity);
@endphp

<div x-data="{ cartOpen: false }">
    <!-- Tombol Floating Cart -->
    <button 
        @click="cartOpen = true" class="fixed bottom-6 right-6 bg-pink-600 hover:bg-pink-700 text-white rounded-full shadow-lg w-14 h-14 flex items-center justify-center z-50">
        <i class="fa-solid fa-cart-shopping text-2xl"></i>

        @if($cartItems->count() > 0)
            <span class="cart-badge absolute -top-1.5 -right-1.5 bg-white text-pink-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-pink-600 shadow-sm">
            {{ $cartItems->count() }}
        </span>
        @endif
    </button>

    <!-- Popup Cart -->
    <div 
        x-show="cartOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="fixed inset-0 z-50 flex justify-end"
        style="display: none;"
    >
        <div @click="cartOpen = false" class="bg-black/40 absolute inset-0 md:hidden"></div>

        <div class="relative w-full md:w-96 bg-white h-full shadow-xl border-l border-gray-200 flex flex-col">
            <!-- Header -->
            <div class="flex justify-between items-center px-5 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fa-solid fa-basket-shopping text-pink-500 mr-2"></i> Keranjang Saya
                </h2>
                <button @click="cartOpen = false" class="text-gray-400 hover:text-pink-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- List Item -->
            <div class="flex-1 overflow-y-auto p-5">
                @if($cartItems->isNotEmpty())
                    @foreach($cartItems as $item)
                        <div class="flex items-center gap-4 mb-4 border-b pb-3">
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 class="w-14 h-14 object-cover rounded-md border">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm">
                                    {{ $item->product->title }}
                                </p>
                                <p class="text-pink-600 text-sm font-medium">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                                <div class="flex items-center mt-1 gap-2">
                                    <form action="{{ route('cart.add', $item->product_id) }}" method="POST">@csrf
                                        <button type="submit" class="text-gray-600 hover:text-pink-600">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                        </button>
                                    </form>

                                    <span class="text-gray-700 text-sm">{{ $item->quantity }}</span>

                                    <form action="{{ route('cart.remove', $item->product_id) }}" method="POST">@csrf
                                        <button type="submit" class="text-gray-600 hover:text-pink-600">
                                            <i class="fa-solid fa-minus text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-gray-500 mt-10">Keranjang masih kosong 🛍️</p>
                @endif
            </div>

            <!-- Footer -->
            @if($cartItems->isNotEmpty())
                <div class="border-t p-5 space-y-3">
                    <p class="flex justify-between text-gray-700 font-medium">
                        <span>Total:</span>
                        <span class="text-pink-600 font-bold">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </p>

                    @auth
                        <a href="{{ route('order.process') }}"
                           class="block w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 rounded-lg shadow text-center">
                            <i class="fa-solid fa-credit-card mr-2"></i> Checkout Pesanan
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="w-full bg-gray-400 text-white font-semibold py-2 rounded-lg shadow text-center block">
                           Login untuk Checkout
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>
