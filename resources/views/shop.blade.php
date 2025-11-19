<x-layout title="Produk Kami">

<section class="max-w-7xl mx-auto py-10">

    <!-- Judul Halaman -->
    <h1 class="text-4xl font-bold mb-10 text-center text-gray-800">Produk Kami</h1>

    <!-- Alert Notifikasi -->
    @if (session('success'))
        <div class="max-w-xl mx-auto mb-6 px-4 py-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-xl mx-auto mb-6 px-4 py-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-center">
            {{ session('error') }}
        </div>
    @endif

    <!-- GRID LAYOUT 2 KOLOM -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

        <!-- ========================= -->
        <!--    SIDEBAR KATEGORI       -->
        <!-- ========================= -->
        <aside class="bg-white shadow rounded-lg p-5 h-fit sticky top-10">

            <h2 class="text-xl font-semibold mb-4">Filter Kategori</h2>

            <form action="{{ route('shop') }}" method="GET" class="space-y-2">

                <!-- Dropdown (Mobile) -->
                <div class="md:hidden mb-4">
                    <select name="category" 
                            onchange="this.form.submit()"
                            class="w-full border border-gray-300 rounded-lg p-2">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" 
                                {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- List Kategori (Desktop) -->
                <ul class="hidden md:block space-y-2">

                    <li>
                        <a href="{{ route('shop') }}"
                           class="block py-1 px-2 rounded 
                           {{ request('category') ? '' : 'bg-pink-100 text-pink-700 font-semibold' }}">
                            Semua Produk
                        </a>
                    </li>

                    @foreach ($categories as $cat)
                    <li>
                        <a href="{{ route('shop', ['category' => $cat->id]) }}"
                           class="block py-1 px-2 rounded
                           {{ request('category') == $cat->id 
                               ? 'bg-pink-100 text-pink-700 font-semibold' 
                               : 'hover:bg-gray-100' }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                    @endforeach

                </ul>

                <!-- Simpan search jika ada -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>

        </aside>

        <!-- ========================= -->
        <!--      KOLOM PRODUK         -->
        <!-- ========================= -->
        <div class="md:col-span-3">

            <!-- SEARCH + TOMBOL FAVORIT & PESANAN -->
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-10">

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">

                    <!-- Form Search -->
                    <form id="searchForm" method="GET" action="{{ route('shop') }}" 
                        class="flex items-center gap-3">

                        <div class="relative">
                            <input type="text" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari produk..."
                                   id="searchInput"
                                   class="w-full sm:w-80 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400">

                            <!-- Jika sudah filter kategori, tetap disertakan -->
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif

                            <!-- Tombol Reset -->
                            <button type="button" id="resetSearch"
                                class="{{ request('search') ? '' : 'hidden' }} absolute right-3 top-3 text-gray-400 hover:text-pink-500">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <button type="submit"
                                class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg shadow">
                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari
                        </button>
                    </form>

                    <!-- Favorite & Orders -->
                    @auth
                        <a href="{{ route('favorites.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white rounded-full hover:bg-pink-700 shadow-md transition">
                            <i class="fa-solid fa-heart"></i> My Favorite
                        </a>

                        <a href="{{ route('orders.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white rounded-full hover:bg-pink-700 shadow-md transition">
                            <i class="fa-solid fa-box"></i> My Orders
                        </a>
                    @endauth

                </div>
            </div>

            <!-- GRID PRODUK -->
            @if($products->count() > 0)

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
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
                <p class="text-center text-gray-500 mt-10">
                    Produk tidak ditemukan.
                </p>
            @endif

            <!-- Pagination -->
            <div class="mt-10">
                {{ $products->appends(request()->query())->links('pagination::tailwind') }}
            </div>

        </div>

    </div> <!-- END GRID -->

    <!-- Cart Floating Button -->
    <x-cart-popup />

</section>

</x-layout>
<script>
    // Tombol Reset Search
    document.getElementById('resetSearch').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchForm').submit();
    });
</script>