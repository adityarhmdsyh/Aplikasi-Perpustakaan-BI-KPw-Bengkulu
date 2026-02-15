<x-app-layout>
    <x-slot name="header">
        Manajemen App Review
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">

        @if(session('success'))
            <div class="mb-4 bg-green-100 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('app_reviews.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
            + Tambah Review
        </a>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">User</th>
                    <th class="border p-2">Rating</th>
                    <th class="border p-2">Review</th>
                    <th class="border p-2">Anonymous</th>
                    <th class="border p-2">Show</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td class="border p-2">
                        {{ $review->is_anonymous ? 'Anonymous' : $review->user->name }}
                    </td>
                    <td class="border p-2">
                        ⭐ {{ $review->rating }}/5
                    </td>
                    <td class="border p-2">
                        {{ $review->review }}
                    </td>
                    <td class="border p-2">
                        {{ $review->is_anonymous ? 'Yes' : 'No' }}
                    </td>
                    <td class="border p-2">
                        {{ $review->is_show ? 'Visible' : 'Hidden' }}
                    </td>
                    <td class="border p-2">
                        <a href="{{ route('app_reviews.edit',$review->id) }}"
                           class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                           Edit
                        </a>

                        <form action="{{ route('app_reviews.destroy',$review->id) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>
