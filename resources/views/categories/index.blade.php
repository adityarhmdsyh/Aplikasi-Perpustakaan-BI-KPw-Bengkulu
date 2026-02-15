<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Kategori
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Button --}}
            <div class="mb-4 flex justify-between">
                <a href="{{ route('categories.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded">
                    + Tambah Kategori
                </a>

                {{-- Search --}}
                <form method="GET" action="{{ route('categories.index') }}">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kategori..."
                           class="border rounded p-2">
                </form>
            </div>

            {{-- Alert --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow rounded">
                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">No</th>
                            <th class="p-2 border">Nama</th>
                            <th class="p-2 border">Deskripsi</th>
                            <th class="p-2 border">Jumlah Buku</th>
                            <th class="p-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="p-2 border">
                                    {{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                                </td>
                                <td class="p-2 border">{{ $category->name }}</td>
                                <td class="p-2 border">{{ $category->description ?? '-' }}</td>
                                <td class="p-2 border">
                                    {{ $category->books()->count() }}
                                </td>
                                <td class="p-2 border space-x-2">
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                       class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Yakin hapus kategori ini?')">
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
                                <td colspan="5" class="text-center p-4">
                                    Belum ada kategori
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
