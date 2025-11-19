<x-layout-admin title="Dashboard">
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard Admin Florist') }}
    </h2>
  </x-slot>

  <div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
          <div>
            <h3 class="text-2xl font-bold text-pink-600">
              Selamat Datang, {{ Auth::user()->name }} 🌸
            </h3>
            <p class="text-gray-700">Kamu berhasil login sebagai admin.</p>
          </div>
          <a href="{{ route('home') }}"
            class="mt-4 md:mt-0 inline-flex items-center bg-gray-200 text-gray-800 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition">
            <i class="fa-solid fa-globe mr-2"></i> Lihat Website
          </a>
        </div>

        {{-- Statistik Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
          {{-- Jumlah Produk --}}
          <div class="bg-pink-100 p-6 rounded-lg shadow hover:shadow-md transition">
            <h4 class="font-semibold text-gray-700 mb-2">Jumlah Produk</h4>
            <p class="text-3xl font-bold text-pink-600">{{ $total_product }}</p>
          </div>

          {{-- Akun Aktif --}}
          <div class="bg-green-100 p-6 rounded-lg shadow hover:shadow-md transition">
            <h4 class="font-semibold text-gray-700 mb-2">Akun Aktif</h4>
            <p class="text-3xl font-bold text-green-600">{{ $total_user }}</p>
          </div>

          {{-- Pesanan Hari Ini --}}
          <div class="bg-yellow-100 p-6 rounded-lg shadow hover:shadow-md transition">
            <h4 class="font-semibold text-gray-700 mb-2">Pesanan Hari Ini</h4>
            <p class="text-3xl font-bold text-yellow-600">{{ $today_orders }}</p>
          </div>
          
        </div>

        {{-- Highlight tambahan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            
          {{-- Total Pesanan --}}
          <div class="bg-blue-100 p-6 rounded-lg shadow hover:shadow-md transition">
            <h4 class="font-semibold text-gray-700 mb-2">Total Pesanan</h4>
            <p class="text-3xl font-bold text-blue-600">{{ $total_order }}</p>
          </div>

          {{-- Pesanan Selesai --}}
          <div class="bg-emerald-100 p-6 rounded-lg shadow hover:shadow-md transition">
            <h4 class="font-semibold text-gray-700 mb-2">Total Pesanan Selesai</h4>
            <p class="text-3xl font-bold text-emerald-600">{{ $completed_orders }}</p>
          </div>

          {{-- Pesanan Dibatalkan --}}
          <div class="bg-red-100 p-6 rounded-lg shadow hover:shadow-md transition">
            <h4 class="font-semibold text-gray-700 mb-2">Total Pesanan Dibatalkan</h4>
            <p class="text-3xl font-bold text-red-600">{{ $cancelled_orders }}</p>
          </div>
          
        </div>

        {{-- Grafik Statistik --}}
        <div class="bg-gray-100 p-6 rounded-lg shadow mb-10">
        <h4 class="text-lg font-semibold text-gray-700 mb-2">Statistik Pesanan Mingguan</h4>
        <p class="text-sm text-gray-500 mb-4">
            Periode: {{ $chart_labels->first() }} – {{ $chart_labels->last() }}
        </p>
        <canvas id="ordersChart" height="100"></canvas>
        </div>

        {{-- Top 3 Produk Terlaris --}}
        @if($top_products->count() > 0)
        <div class="bg-white border border-gray-200 p-6 rounded-lg shadow text-center">
            <h4 class="text-lg font-semibold text-gray-700 mb-4">🌼 Top 3 Produk Paling Banyak Dipesan</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($top_products as $index => $item)
                    <div class="bg-pink-50 p-4 rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="text-4xl font-bold text-pink-500 mb-2">#{{ $index + 1 }}</div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-1">{{ $item->product->title }}</h5>
                        <p class="text-gray-600">Total dipesan: <strong>{{ $item->total_sold }}</strong> kali</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
      </div>
    </div>
  </div>

  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('ordersChart');
    const ordersChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: {!! json_encode($chart_labels) !!},
        datasets: [{
          label: 'Jumlah Pesanan',
          data: {!! json_encode($chart_data) !!},
          borderColor: '#ec4899',
          backgroundColor: 'rgba(236,72,153,0.15)',
          borderWidth: 2,
          tension: 0.3,
          fill: true,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } },
        },
        plugins: {
          legend: { display: false },
        }
      }
    });
  </script>
</x-layout-admin>
