<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Kategori
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 shadow rounded">

            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('categories._form')

                <div class="mt-4">
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
