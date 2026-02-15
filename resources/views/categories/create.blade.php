<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Kategori
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white p-6 shadow rounded">

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                @include('categories._form')

                <div class="mt-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
