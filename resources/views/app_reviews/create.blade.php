<x-app-layout>
    <x-slot name="header">
        Tambah Review
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto bg-white p-6 shadow rounded">

        <form action="{{ route('app_reviews.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label>Rating (1-5)</label>
                <input type="number" name="rating" min="1" max="5"
                       class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label>Review</label>
                <textarea name="review"
                          class="w-full border p-2 rounded"></textarea>
            </div>

            <div class="mb-4">
                <label>
                    <input type="checkbox" name="is_anonymous">
                    Kirim sebagai Anonymous
                </label>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>

        </form>

    </div>
</x-app-layout>
