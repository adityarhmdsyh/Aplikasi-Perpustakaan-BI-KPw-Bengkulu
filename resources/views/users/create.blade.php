<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah User
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                {{-- ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Nama</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Email</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Password</label>
                        <input type="password"
                               name="password"
                               class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                    </div>

                    {{-- Role --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Role</label>
                        <select name="role"
                                class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                            <option value="admin">Admin</option>
                            <option value="user" selected>User</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                            <option value="active">Active</option>
                            <option value="inactive" selected>Inactive</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Alamat</label>
                        <textarea name="alamat"
                                  rows="3"
                                  class="w-full border rounded p-2 focus:ring focus:ring-blue-200">{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Foto Profile --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Foto Profile</label>
                        <input type="file"
                               name="foto_profile"
                               accept="image/*"
                               class="w-full border rounded p-2">
                    </div>

                    {{-- Foto KTP --}}
                    <div class="mb-6">
                        <label class="block font-medium mb-1">Foto KTP</label>
                        <input type="file"
                               name="foto_ktp"
                               accept="image/*"
                               class="w-full border rounded p-2">
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('users.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Batal
                        </a>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
