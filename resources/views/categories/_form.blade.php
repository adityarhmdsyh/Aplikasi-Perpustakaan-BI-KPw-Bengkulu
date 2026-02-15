<div class="space-y-4">

    <div>
        <label class="block mb-1">Nama Kategori</label>
        <input type="text"
               name="name"
               value="{{ old('name', $category->name ?? '') }}"
               class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block mb-1">Deskripsi</label>
        <textarea name="description"
                  class="w-full border rounded p-2"
                  rows="3">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

</div>
