
<x-layouts.app title="Admin Dashboard">
    <div class="container-fluid px-4 mt-4">
        @php
            $obatHabis = \App\Models\Obat::where('stok', '<=', 0)->get();
        @endphp

        @if ($obatHabis->count() > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Peringatan!</strong> Ada {{ $obatHabis->count() }} obat yang habis atau stok kosong.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <hr>
                <ul class="mb-0">
                    @foreach ($obatHabis as $obat)
                        <li>{{ $obat->nama_obat }} - Stok: {{ $obat->stok }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="mb-4">Halo Selamat Datang Admin</h1>
    </div>
</x-layouts.app>
