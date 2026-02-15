@csrf

<div class="mb-4">
    <label class="block font-medium">Kategori</label>
    <select name="category_id" class="w-full border rounded p-2" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id', $book->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block font-medium">Judul</label>
    <input type="text" name="title"
           value="{{ old('title', $book->title ?? '') }}"
           class="w-full border rounded p-2" required>
</div>

<div class="mb-4">
    <label class="block font-medium">Penulis</label>
    <input type="text" name="author"
           value="{{ old('author', $book->author ?? '') }}"
           class="w-full border rounded p-2" required>
</div>

<div class="mb-4">
    <label class="block font-medium">Penerbit</label>
    <input type="text" name="publisher"
           value="{{ old('publisher', $book->publisher ?? '') }}"
           class="w-full border rounded p-2">
</div>

<div class="mb-4">
    <label class="block font-medium">Tahun</label>
    <input type="number" name="year"
           value="{{ old('year', $book->year ?? '') }}"
           class="w-full border rounded p-2">
</div>

<div class="mb-4">
    <label class="block font-medium">ISBN</label>
    <input type="text" name="isbn"
           value="{{ old('isbn', $book->isbn ?? '') }}"
           class="w-full border rounded p-2" required>
</div>

<div class="mb-4">
    <label class="block font-medium">Jumlah Halaman</label>
    <input type="number" name="jumlah_halaman"
           value="{{ old('jumlah_halaman', $book->jumlah_halaman ?? '') }}"
           class="w-full border rounded p-2">
</div>

<div class="mb-4">
    <label class="block font-medium">Lokasi Buku</label>
    <input type="text" name="lokasi_buku"
           value="{{ old('lokasi_buku', $book->lokasi_buku ?? '') }}"
           class="w-full border rounded p-2" required>
</div>

<div class="mb-4">
    <label class="block font-medium">Stock</label>
    <input type="number" name="stock"
           value="{{ old('stock', $book->stock ?? 0) }}"
           class="w-full border rounded p-2" required>
</div>

<div class="mb-4">
    <label class="block font-medium">Deskripsi</label>
    <textarea name="description"
              class="w-full border rounded p-2">{{ old('description', $book->description ?? '') }}</textarea>
</div>

<div class="mb-4">
    <label class="block font-medium">Gambar</label>
    <input type="file" name="image" class="w-full border rounded p-2">
</div>

<div>
    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Simpan
    </button>
</div>
