<x-app-layout>
    <x-slot name="header">Tambah Borrow</x-slot>

    <div class="py-6 max-w-4xl mx-auto bg-white p-6 shadow rounded">

        <form action="{{ route('borrows.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label>User</label>
                <select name="user_id" class="w-full border p-2 rounded">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Borrow Date</label>
                <input type="date" name="borrow_date" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label>Due Date</label>
                <input type="date" name="original_due_date" class="w-full border p-2 rounded">
            </div>

            <hr class="my-4">

            <h3 class="font-bold mb-2">Pilih Buku</h3>

            @foreach ($books as $book)
                <div class="flex items-center gap-4 mb-2">
                    <input type="number" name="books[{{ $book->id }}]" min="0" max="{{ $book->stock }}"
                        placeholder="0" class="w-20 border rounded p-1">

                    <span>
                        {{ $book->title }} (Stock: {{ $book->stock }})
                    </span>
                </div>
            @endforeach

            <button class="bg-blue-600 text-white px-4 py-2 rounded mt-4">
                Simpan
            </button>

        </form>
    </div>
</x-app-layout>
