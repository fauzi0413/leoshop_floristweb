<x-layout-admin title="Tambah Produk">

<section class="max-w-3xl mx-auto py-5">

    <!-- Tombol Kembali -->
    <a href="{{ route('admin.show_product') }}"
        class="inline-block border border-pink-500 text-pink-600 hover:bg-pink-500 hover:text-white font-medium px-3 py-1 rounded-lg transition duration-200 mb-4">
        ← Kembali
    </a>

    <h1 class="text-3xl font-bold mb-6 text-gray-800">Tambah Produk Baru</h1>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Kategori -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700 flex items-center justify-between">
                Kategori Produk

                <!-- Tombol buka modal tambah kategori -->
                <button type="button"
                    onclick="openCategoryModal()"
                    class="text-sm bg-pink-500 hover:bg-pink-600 text-white px-2 py-1 rounded-md">
                    + Tambah
                </button>
            </label>

            <select name="category_id"
                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:outline-none">
                <option value="">Pilih kategori…</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            
        </div>

        <!-- Nama Produk -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700">Nama Produk</label>
            <input type="text" name="title"
                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:outline-none"
                placeholder="ex: Buket Boneka Custom" required>
        </div>

        <!-- Harga Produk -->
        <div x-data="{ 
                raw: '{{ old('price') ?? '' }}',
                display: '',
                formatRupiah(num) {
                    num = num.replace(/[^0-9]/g, '');
                    if (!num) return '';
                    return 'Rp ' + num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
            }"
            x-init="if(raw) display = formatRupiah(raw.toString())">

            <label class="block mb-1 font-semibold text-gray-700">Harga Produk</label>

            <input type="text"
                x-model="display"
                @input="
                    raw = $event.target.value.replace(/[^0-9]/g, '');
                    display = formatRupiah(raw);
                "
                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:outline-none"
                placeholder="Rp 0">

            <input type="hidden" name="price" :value="raw">
        </div>

        <!-- Deskripsi Produk -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700">Deskripsi Produk</label>
            <textarea name="description" rows="4"
                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:outline-none"
                placeholder="ex: Buket Boneka 5cm Custom"></textarea>
        </div>

        <!-- Upload Gambar -->
        <div
            x-data="{
                preview: null,
                handleDrop(event) {
                    event.preventDefault();
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = e => this.preview = e.target.result;
                        reader.readAsDataURL(file);
                        this.$refs.fileInput.files = event.dataTransfer.files;
                    }
                }
            }">

            <label class="block mb-1 font-semibold text-gray-700">Gambar Produk</label>

            <div @dragover.prevent @drop="handleDrop($event)" @click="$refs.fileInput.click()"
                class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-pink-400 rounded-lg cursor-pointer bg-pink-50 hover:bg-pink-100 transition overflow-hidden">

                <template x-if="!preview">
                    <div class="flex flex-col items-center text-center pt-5 pb-6 pointer-events-none">
                        <svg class="w-8 h-8 mb-3 text-pink-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold text-pink-600">Klik untuk upload</span> atau seret gambar ke sini
                        </p>
                    </div>
                </template>

                <template x-if="preview">
                    <div class="relative">
                        <img :src="preview" class="h-28 object-contain rounded-md mx-auto">

                        <button type="button" @click="preview = null; $refs.fileInput.value = '';"
                            class="absolute top-0 right-0 bg-red-600 text-white text-xs px-2 py-1 rounded-bl hover:bg-red-700 transition">
                            Hapus
                        </button>
                    </div>
                </template>
            </div>

            <input id="image" type="file" name="image" accept="image/*" class="hidden" x-ref="fileInput"
                @change="
                    const file = $event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => preview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                ">
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end items-center pt-4">
            <button type="submit"
                class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
        </div>

    </form>
</section>

<!-- ===================== -->
<!-- Modal Tambah Kategori -->
<!-- ===================== -->
<div id="categoryModal"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50">

    <div class="bg-white w-96 p-5 rounded-lg shadow-lg">

        <!-- Title -->
        <h2 class="text-xl font-bold mb-3 text-gray-800">Tambah Kategori Baru</h2>

        <!-- Form Tambah -->
        <form action="{{ route('admin.categories.store') }}" method="POST" class="mb-4">
            @csrf

            <input type="text" name="name"
                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:outline-none"
                placeholder="Nama kategori..." required>

            <div class="flex justify-end mt-4 gap-2">
                <button type="button" onclick="closeCategoryModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                    Simpan
                </button>
            </div>
        </form>

        <!-- Garis Pemisah -->
        <hr class="my-4 border-gray-300">

        <!-- Daftar Kategori -->
        <div class="text-sm">
            <p class="font-semibold text-gray-700 mb-2">Daftar Kategori:</p>

            <div class="space-y-1 max-h-48 overflow-y-auto pr-1">

                @foreach ($categories as $cat)
                    <div class="flex justify-between items-center bg-gray-100 px-2 py-1 rounded">

                        <span>{{ $cat->name }}</span>

                        <div class="flex gap-2">

                            <!-- Tombol Edit -->
                            <button type="button"
                                onclick="openEditCategoryModal({{ $cat->id }}, '{{ $cat->name }}')"
                                class="text-blue-600 hover:text-blue-800 text-sm">
                                Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST"
                                onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 text-sm">
                                    Hapus
                                </button>
                            </form>

                        </div>

                    </div>
                @endforeach

            </div>
        </div>

    </div>
</div>

<!-- ===================== -->
<!-- Modal Edit Kategori -->
<!-- ===================== -->
<div id="editCategoryModal"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50">

    <div class="bg-white w-96 p-5 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-3 text-gray-800">Edit Kategori</h2>

        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')

            <input type="text" id="editCategoryName" name="name"
                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                placeholder="Nama kategori..." required>

            <div class="flex justify-end mt-4 gap-2">
                <button type="button" onclick="closeEditCategoryModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ===================== -->
<!-- Script Modal & Delete -->
<!-- ===================== -->
<script>
function openCategoryModal() {
    document.getElementById('categoryModal').classList.remove('hidden');
}
function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function openEditCategoryModal(id, name) {
    document.getElementById('editCategoryModal').classList.remove('hidden');
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editCategoryForm').action =
        "/admin/categories/update/" + id;
}

function closeEditCategoryModal() {
    document.getElementById('editCategoryModal').classList.add('hidden');
}

function confirmDelete(event) {
    event.preventDefault();
    Swal.fire({
        title: "Hapus kategori?",
        text: "Kategori yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e11d48",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, hapus"
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-layout-admin>
