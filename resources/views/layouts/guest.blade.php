<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Leo Shop{{ isset($title) && $title ? ' | ' . $title : '' }}</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/4151/4151022.png" type="image/png">
</head>

<body class="min-h-screen flex flex-col justify-between bg-gradient-to-br from-pink-50 via-white to-pink-100 text-gray-800 antialiased">

    <!-- 🌸 Konten Utama -->
    <main class="flex flex-col items-center justify-center flex-grow px-6 py-10">
        <div class="w-full mx-auto">
            {{ $slot }}
        </div>
    </main>

    <!-- 🌷 Footer -->
    <footer class="text-center text-gray-500 text-sm py-6 border-t border-pink-100 bg-white/40 backdrop-blur-sm shadow-inner">
        <p>
            © {{ date('Y') }} 
            <span class="font-semibold text-pink-600">Leo Shop</span> 🌸
            <br>
            <span class="text-xs text-gray-400">Dibuat dengan ❤️ untuk keindahan setiap momen</span>
        </p>
    </footer>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
