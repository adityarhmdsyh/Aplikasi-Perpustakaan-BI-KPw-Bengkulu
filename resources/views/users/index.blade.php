<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen User
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-500 text-white p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                + Tambah User
            </a>

            <div class="mt-6 bg-white shadow rounded p-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border">Nama</th>
                            <th class="p-2 border">Email</th>
                            <th class="p-2 border">Role</th>
                            <th class="p-2 border">Status</th>
                            <th class="p-2 border">Foto</th>
                            <th class="p-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="p-2 border">{{ $user->name }}</td>
                                <td class="p-2 border">{{ $user->email }}</td>
                                <td class="p-2 border capitalize">{{ $user->role }}</td>
                                <td class="p-2 border capitalize">{{ $user->status }}</td>
                                <td class="p-2 border capitalize">
                                    @if ($user->foto_profile)
                                        <img src="{{ asset('storage/' . $user->foto_profile) }}" width="50">
                                    @endif
                                </td>

                                <td class="p-2 border text-center space-x-2">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                        Edit
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin hapus user ini?')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-4">
                                    Tidak ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
