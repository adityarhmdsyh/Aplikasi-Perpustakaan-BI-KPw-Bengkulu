<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Buku
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Tombol Tambah --}}
            <div class="mb-4">
                <a href="{{ route('books.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Tambah Buku
                </a>
            </div>

            {{-- Alert --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            <form method="GET" action="{{ route('books.index') }}" class="mb-4">
    <div class="flex flex-wrap gap-3">

        {{-- Search --}}
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari judul, penulis, ISBN..."
               class="border rounded p-2 w-64">

        {{-- Filter Kategori --}}
        <select name="category_id" class="border rounded p-2">
            <option value="">-- Semua Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        {{-- Button --}}
        <button class="bg-gray-800 text-white px-4 py-2 rounded">
            Filter
        </button>

        <a href="{{ route('books.index') }}"
           class="bg-gray-400 text-white px-4 py-2 rounded">
            Reset
        </a>

    </div>
</form>


            {{-- Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <table class="min-w-full border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 border">No</th>
                                <th class="p-2 border">Judul</th>
                                <th class="p-2 border">Kategori</th>
                                <th class="p-2 border">Penulis</th>
                                <th class="p-2 border">Stock</th>
                                <th class="p-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td class="p-2 border">
                                        {{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}
                                    </td>
                                    <td class="p-2 border">{{ $book->title }}</td>
                                    <td class="p-2 border">{{ $book->category->name ?? '-' }}</td>
                                    <td class="p-2 border">{{ $book->author }}</td>
                                    <td class="p-2 border">{{ $book->stock }}</td>
                                    <td class="p-2 border space-x-2">

                                        <a href="{{ route('books.edit', $book->id) }}"
                                           class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('books.destroy', $book->id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin hapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                Hapus
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-4">
                                        Belum ada data buku
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $books->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
