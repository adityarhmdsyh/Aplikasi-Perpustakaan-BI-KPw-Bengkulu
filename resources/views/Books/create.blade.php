<x-app-layout>
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Tambah Buku</h2>

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @include('books._form')
        </form>
    </div>
</x-app-layout>
