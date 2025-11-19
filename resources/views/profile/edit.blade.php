<x-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-pink-600 leading-tight text-center tracking-wide">
            🌸 {{ __('Profil Kamu') }}
        </h2>
        <p class="text-center text-gray-500 text-sm mt-1">
            Kelola informasi akun, ubah kata sandi, atau hapus akunmu di sini 💐
        </p>
    </x-slot>

    <div class="py-16 bg-gradient-to-br from-pink-50 via-white to-pink-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            <!-- 🪞 Update Info -->
            <div class="p-8 bg-white border border-pink-100 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
                <h3 class="text-2xl font-bold text-pink-700 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-pink-500"></i> Ubah Informasi Profil
                </h3>
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- 🔐 Update Password -->
            <div class="p-8 bg-white border border-pink-100 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
                <h3 class="text-2xl font-bold text-pink-700 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-pink-500"></i> Ubah Password
                </h3>
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- ⚠️ Delete Account -->
            <div class="p-8 bg-gradient-to-br from-pink-50 to-white border border-pink-100 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
                <h3 class="text-2xl font-bold text-red-600 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-red-500"></i> Hapus Akun
                </h3>
                <p class="text-gray-600 text-sm mb-4">
                    Tindakan ini tidak dapat dibatalkan. Mohon pastikan kamu benar-benar yakin sebelum menghapus akun.
                </p>
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-layout>
