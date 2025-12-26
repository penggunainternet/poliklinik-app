
<x-layouts.app title="Admin Dashboard">
    <div class="container-fluid px-4 mt-4">
        @php
            $obatHabis = \App\Models\Obat::where('stok', '<=', 0)->get();
            $obatMenipis = \App\Models\Obat::where('stok', '>', 0)->where('stok', '<=', 5)->get();
        @endphp

        {{-- ALERT OBAT HABIS --}}
        @if ($obatHabis->count() > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle"></i> <strong>URGENT!</strong> Ada {{ $obatHabis->count() }} obat yang HABIS!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <hr>
                <ul class="mb-0">
                    @foreach ($obatHabis as $obat)
                        <li><strong>{{ $obat->nama_obat }}</strong> - Stok: {{ $obat->stok }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ALERT OBAT MENIPIS --}}
        @if ($obatMenipis->count() > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Peringatan!</strong> Ada {{ $obatMenipis->count() }} obat yang MENIPIS (tinggal 5 atau kurang).
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <hr>
                <ul class="mb-0">
                    @foreach ($obatMenipis as $obat)
                        <li><strong>{{ $obat->nama_obat }}</strong> - Stok: {{ $obat->stok }} (Segera restock!)</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="mb-4">Halo Selamat Datang Admin</h1>
    </div>
</x-layouts.app>
