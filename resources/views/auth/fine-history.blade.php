@extends('layouts.app')

@section('title', 'Riwayat Denda')

@section('content')

<div class="container py-5">

    <!-- HEADER -->
    <div class="d-flex align-items-center mb-4 gap-3">
        <div class="rounded-4 p-3 text-white header-icon">
            <i class="bi bi-cash-coin fs-2"></i>
        </div>
        <div>
            <h3 class="fw-bold mb-1" style="color:#6c7b94ff;">Riwayat Denda</h3>
            <p class="mb-0 text-muted">Denda yang pernah Anda terima</p>
        </div>
    </div>

    <!-- PANEL TITLE -->
    <div class="table-header-card rounded-4 mb-3 px-4 py-3 text-white">
        <h4 class="mb-0 fw-bold">Daftar Denda</h4>
    </div>

    @forelse($denda as $d)

    <!-- ITEM ROW -->
    <div class="denda-row rounded-4 bg-white shadow-sm px-4 py-3 mb-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <div class="d-flex flex-column">
            <h5 class="fw-bold mb-1" style="color:#6c7b94ff;">{{ $d->judul_buku }}</h5>
            <span class="text-muted">Nomor Buku: {{ $d->nomor_buku }}</span>
        </div>

        <div class="text-md-end mt-3 mt-md-0">
            <p class="mb-0"><strong>Pinjam:</strong> {{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d M Y') }}</p>
            <p class="mb-0"><strong>Kembali:</strong> {{ \Carbon\Carbon::parse($d->tanggal_kembali)->format('d M Y') }}</p>

            <span class="badge denda-badge mt-2">
                Rp {{ number_format($d->total_denda,0,',','.') }}
            </span>
        </div>
    </div>

    @empty
    <div class="alert text-center fw-bold no-data-alert">
        Belum ada denda
    </div>
    @endforelse

</div>


<style>
/* GLOBAL */
body {
    background-color: #f3f5fa;
    font-family: 'Poppins', sans-serif;
}

/* HEADER ICON */
.header-icon {
    background-color: #6c7b94ff;
}

/* TOP PANEL */
.table-header-card {
    background: linear-gradient(120deg, #6c7b94ff, #49566cff);
}

/* ITEM ROW CARD */
.denda-row {
    border-radius: 18px;
    transition: .25s;
    border-left: 6px solid #6c7b94ff;
}
.denda-row:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* BADGE */
.denda-badge {
    background-color: #6c7b94ff;
    color: #fff;
    padding: 0.4em 0.75em;
    font-weight: 600;
    border-radius: 12px;
    font-size: 0.85rem;
}

/* ALERT */
.no-data-alert {
    background-color: #dbe0eb;
    color: #6c7b94ff;
    border-radius: 12px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .denda-row {
        text-align: left;
    }
}
</style>
@include('components.footer')
@endsection
