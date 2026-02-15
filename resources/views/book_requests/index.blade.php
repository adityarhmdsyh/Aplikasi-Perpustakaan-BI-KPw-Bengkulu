<x-app-layout>
    <x-slot name="header">
        Manajemen Book Request
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-4 bg-green-100 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">No</th>
                    <th class="border p-2">User</th>
                    <th class="border p-2">Judul Buku</th>
                    <th class="border p-2">Author</th>
                    <th class="border p-2">Publisher</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookRequests as $req)
                <tr>
                    <td class="border p-2">{{ $loop->iteration }}</td>
                    <td class="border p-2">{{ $req->user->name }}</td>
                    <td class="border p-2">{{ $req->book_title }}</td>
                    <td class="border p-2">{{ $req->author }}</td>
                    <td class="border p-2">{{ $req->publisher }}</td>
                    <td class="border p-2 space-x-1">
                        

                        <form action="{{ route('book_requests.destroy',$req->id) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded text-sm"
                                    onclick="return confirm('Yakin hapus?')">
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
