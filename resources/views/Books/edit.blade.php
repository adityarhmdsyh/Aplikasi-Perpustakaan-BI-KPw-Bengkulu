<x-app-layout>
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Edit Buku</h2>

        <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('books._form')
        </form>
    </div>
</x-app-layout>
