<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Detail Borrow
        </h2>
    </x-slot>

    <div class="p-6">

        {{-- INFO USER --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <p><strong>Nama:</strong> {{ $borrow->user->name }}</p>
            <p><strong>Tanggal Pinjam:</strong> {{ $borrow->borrow_date }}</p>
            <p><strong>Jatuh Tempo:</strong> {{ $borrow->current_due_date ?? $borrow->original_due_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($borrow->status) }}</p>
        </div>

        {{-- LIST BUKU --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="font-semibold mb-3">Buku Dipinjam</h3>

            <ul>
                @foreach ($borrow->details as $detail)
                    <li>
                        {{ $detail->book->title }} ({{ $detail->quantity }}x)
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- UBAH STATUS --}}
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="font-semibold mb-3">Manajemen Status</h3>

            <form action="{{ route('borrows.update', $borrow->id) }}" method="POST">
                @csrf
                @method('PUT')

                <select name="status" class="border rounded p-2">
                    <option value="approved">Approve</option>
                    <option value="rejected">Reject</option>
                    <option value="picked_up">Picked Up</option>
                    <option value="returned">Returned</option>
                </select>

                <button class="bg-green-500 text-white px-4 py-2 rounded">
                    Update Status
                </button>
            </form>
        </div>




        {{-- REQUEST PERPANJANGAN --}}
        @if ($borrow->status == 'picked_up')
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-3">Perpanjangan</h3>

                <form action="{{ route('borrows.extend', $borrow->id) }}" method="POST">
                    @csrf

                    <input type="date" name="new_due_date" class="border rounded p-2" required>

                    <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                        Ajukan Perpanjangan
                    </button>
                </form>
            </div>
        @endif

    </div>

    <div class="bg-white p-6 rounded shadow mt-6">
        <h3 class="font-semibold mb-4">Riwayat Perpanjangan</h3>

        @forelse($borrow->extensions as $extension)
            <div class="border-b py-4">

                <p><strong>Requested:</strong> {{ $extension->requested_due_date }}</p>
                <p><strong>Status:</strong> {{ ucfirst($extension->status) }}</p>

                @if ($extension->approved_due_date)
                    <p><strong>Approved Date:</strong>
                        {{ $extension->approved_due_date }}
                    </p>
                @endif

                @if ($extension->approver)
                    <p><strong>Approved By:</strong>
                        {{ $extension->approver->name }}
                    </p>
                @endif

                {{-- Tombol ACC / Reject --}}
                @if ($extension->status == 'pending')
                    <form action="{{ route('borrow-extensions.update', $extension->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <button type="submit" name="action" value="approve"
                            class="bg-green-500 text-white px-3 py-1 rounded">
                            Approve
                        </button>

                        <button type="submit" name="action" value="reject"
                            class="bg-red-500 text-white px-3 py-1 rounded">
                            Reject
                        </button>
                    </form>
                @endif

            </div>

        @empty
            <p class="text-gray-500">
                Belum ada request perpanjangan
            </p>
        @endforelse
    </div>

</x-app-layout>
