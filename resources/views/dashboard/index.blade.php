<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Perpustakaan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- STAT CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total Buku</h3>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ $totalBooks }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total User</h3>
                    <p class="text-3xl font-bold text-green-600">
                        {{ $totalUsers }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total Borrow</h3>
                    <p class="text-3xl font-bold text-purple-600">
                        {{ $totalBorrows }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Borrow Aktif</h3>
                    <p class="text-3xl font-bold text-yellow-600">
                        {{ $activeBorrows }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total Denda</h3>
                    <p class="text-3xl font-bold text-red-600">
                        Rp {{ number_format($totalFines, 0, ',', '.') }}
                    </p>
                </div>

            </div>

            {{-- LATEST BORROWS --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">
                    5 Peminjaman Terbaru
                </h3>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">User</th>
                            <th class="text-left py-2">Tanggal Pinjam</th>
                            <th class="text-left py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestBorrows as $borrow)
                            <tr class="border-b">
                                <td class="py-2">
                                    {{ $borrow->user->name }}
                                </td>
                                <td class="py-2">
                                    {{ $borrow->borrow_date }}
                                </td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-white
                                        @if($borrow->status == 'returned') bg-green-500
                                        @elseif($borrow->status == 'pending') bg-gray-500
                                        @elseif($borrow->status == 'approved') bg-blue-500
                                        @elseif($borrow->status == 'picked_up') bg-yellow-500
                                        @else bg-red-500
                                        @endif
                                    ">
                                        {{ ucfirst($borrow->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    Belum ada data peminjaman
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</x-app-layout>
