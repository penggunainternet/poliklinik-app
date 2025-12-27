<x-layouts.app title="Detail Riwayat Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Detail Riwayat</h2>
                    <a href="{{ route('riwayat-pasien.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card">
                    <h5 class="card-header">Informasi Pasien</h5>
                    <div class="card-body">
                        <p><strong>Nama Pasien:</strong> {{ $periksa->daftarPoli->pasien->nama }}</p>
                        <p><strong>No. Antrian:</strong> {{ $periksa->daftarPoli->no_antrian }}</p>
                        <p><strong>Keluhan:</strong> {{ $periksa->daftarPoli->keluhan }}</p>
                        <p><strong>Poli:</strong> {{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli }}</p>
                        <p><strong>Dokter:</strong> {{ $periksa->daftarPoli->jadwalPeriksa->dokter->nama }}</p>
                        <p><strong>Tanggal Periksa:</strong> {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <h5 class="card-header">Catatan Dokter</h5>
                    <div class="card-body">
                        <p>{{ $periksa->catatan ?: 'Tidak ada catatan' }}</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <h5 class="card-header">Obat yang Diresepkan</h5>
                    <div class="card-body">
                        @if($periksa->detailPeriksas && $periksa->detailPeriksas->count() > 0)
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Obat</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalObat = 0; @endphp
                                    @foreach($periksa->detailPeriksas as $index => $detail)
                                        @php $subtotal = $detail->obat->harga * $detail->kuantitas; $totalObat += $subtotal; @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->obat->nama_obat }}</td>
                                            <td>Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                                            <td>{{ $detail->kuantitas }}</td>
                                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Tidak ada obat yang diresepkan</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <h5 class="card-header">Rincian Biaya</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Biaya Konsultasi:</span>
                                    <strong>Rp 150.000</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Harga Obat:</span>
                                    <strong>Rp {{ number_format($totalObat ?? 0, 0, ',', '.') }}</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">TOTAL BIAYA:</span>
                                    <strong class="text-primary fs-5">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
