<x-layout title="Produk Kami">

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Judul -->
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-8 sm:mb-10 text-center text-gray-800">
        Produk Kami
    </h1>

    <!-- Alert -->
    @if (session('success'))
        <div class="max-w-xl mx-auto mb-6 px-4 py-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-center text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-xl mx-auto mb-6 px-4 py-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-center text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Layout utama -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-10">

        <!-- ================= SIDEBAR ================= -->
        <aside class="bg-white shadow-sm rounded-xl p-5 h-fit lg:sticky lg:top-24">

            <h2 class="text-lg font-semibold mb-4 text-gray-800">
                Filter Kategori
            </h2>

            <form action="{{ route('shop') }}" method="GET" class="space-y-2">

                <!-- Dropdown Mobile -->
                <div class="lg:hidden mb-4">
                    <select name="category"
                            onchange="this.form.submit()"
                            class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-pink-400">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- List Desktop -->
                <ul class="hidden lg:block space-y-1 text-sm">
                    <li>
                        <a href="{{ route('shop') }}"
                           class="block px-3 py-2 rounded-lg transition
                           {{ request('category') ? 'hover:bg-gray-100' : 'bg-pink-100 text-pink-700 font-semibold' }}">
                            Semua Produk
                        </a>
                    </li>

                    @foreach ($categories as $cat)
                        <li>
                            <a href="{{ route('shop', ['category' => $cat->id]) }}"
                               class="block px-3 py-2 rounded-lg transition
                               {{ request('category') == $cat->id
                                   ? 'bg-pink-100 text-pink-700 font-semibold'
                                   : 'hover:bg-gray-100' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </aside>

        <!-- ================= PRODUK ================= -->
        <div class="lg:col-span-3">

            <!-- Search & Action -->
            <div class="flex flex-col gap-4 mb-8">

                <!-- Search -->
                <form id="searchForm" method="GET" action="{{ route('shop') }}"
                      class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

                    <div class="relative flex-1">
                        <input type="text"
                               name="search"
                               id="searchInput"
                               value="{{ request('search') }}"
                               placeholder="Cari produk..."
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm focus:ring-2 focus:ring-pink-400">

                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <button type="button" id="resetSearch"
                            class="{{ request('search') ? '' : 'hidden' }} absolute right-3 top-2.5 text-gray-400 hover:text-pink-500">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <button type="submit"
                        class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg shadow text-sm font-medium transition">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari
                    </button>
                </form>

                <!-- Favorite & Orders -->
                @auth
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('favorites.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-pink-600 text-white rounded-full hover:bg-pink-700 shadow-sm transition text-sm">
                        <i class="fa-solid fa-heart"></i> My Favorite
                    </a>

                    <a href="{{ route('orders.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-pink-600 text-white rounded-full hover:bg-pink-700 shadow-sm transition text-sm">
                        <i class="fa-solid fa-box"></i> My Orders
                    </a>
                </div>
                @endauth
            </div>

            <!-- Grid Produk -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($products as $product)
                        <x-product-card
                            :title="$product->title"
                            :image="asset('storage/' . $product->image)"
                            :price="$product->price"
                            :id="$product->id"
                            :isFavorite="in_array($product->id, $favoriteIds)"
                        />
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 mt-10 text-sm">
                    Produk tidak ditemukan.
                </p>
            @endif

            <!-- Pagination -->
            <div class="mt-10">
                {{ $products->appends(request()->query())->links('pagination::tailwind') }}
            </div>

        </div>
    </div>

    <!-- Cart Floating -->
    <x-cart-popup />

</section>

</x-layout>

<script>
document.getElementById('resetSearch')?.addEventListener('click', function () {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchForm').submit();
});
</script>
