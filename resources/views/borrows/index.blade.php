<x-app-layout>
    <x-slot name="header">Manajemen Borrow</x-slot>

    <div class="py-6 max-w-7xl mx-auto">

        <div class="mb-3">
            <a href="{{ route('borrows.create') }}" class="btn btn-primary">
                + Create Borrow
            </a>
        </div>


        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">User</th>
                    <th class="border p-2">Buku</th>
                    <th class="border p-2">Waktu Pinjam</th>
                    <th class="border p-2">Waktu Kembalikan</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Fine</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borrows as $b)
                    <tr>
                        <td class="border p-2">{{ $b->user->name }}</td>

                        <td class="border p-2">
                            @foreach ($b->details as $detail)
                                <div>
                                    {{ $detail->book->title }} (x{{ $detail->quantity }})
                                </div>
                            @endforeach
                        </td>

                        <td class="border p-2"><a href="{{ route('borrows.show', $b->id) }}"
                                class="bg-blue-500 text-white px-3 py-1 rounded">
                                Detail
                            </a>
                        </td>
                        <td class="border p-2">{{ $b->borrow_date }}</td>
                        <td class="border p-2">{{ $b->original_due_date }}</td>

                        <td class="border p-2">{{ $b->status }}</td>
                        <td class="border p-2">Rp {{ number_format($b->fine_amount) }}</td>

                        <td class="border p-2 space-x-1">

                            @if ($b->status == 'pending')
                                <form method="POST" action="{{ route('borrows.approve', $b->id) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="bg-green-500 text-white px-2 py-1 rounded text-sm">Approve</button>
                                </form>
                            @endif
                            @if ($b->status == 'approved')
                                <form method="POST" action="{{ route('borrows.pickup', $b->id) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="bg-green-500 text-white px-2 py-1 rounded text-sm">Approve</button>
                                </form>
                            @endif

                            @if ($b->status == 'picked_up')
                                <form method="POST" action="{{ route('borrows.return', $b->id) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="bg-purple-500 text-white px-2 py-1 rounded text-sm">Return</button>
                                </form>
                            @endif

                            @if ($b->status == 'returned')
                                <a class="text-green px-2 py-1 rounded text-sm">Telah dikembalikan</a>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>
