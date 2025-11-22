@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')

@section('content')

<style>
    body {
        background: #FDF7EF;
        font-family: 'Poppins', sans-serif;
    }

    /* =============== HEADER =============== */
    .return-header {
        background: #ffffff;
        border-radius: 20px;
        padding: 25px 35px;
        margin-bottom: 25px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .return-header-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        background: linear-gradient(135deg, #FF6F00, #FF8F00);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }

    .return-header h3 {
        margin: 0;
        font-weight: 800;
        color: #402400;
        font-size: 1.3rem;
    }

    .return-header p {
        margin: 0;
        margin-top: -2px;
        font-size: 0.9rem;
        color: rgba(40, 22, 0, 0.65);
    }

    /* =============== TABLE CARD =============== */
    .return-table-wrapper {
        background: #ffffff;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .return-table-title {
        background: linear-gradient(135deg, #FF6F00, #FF8F00);
        padding: 22px 28px;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .return-table table {
        margin: 0;
        width: 100%;
    }

    .return-table th {
        background: #ffffff;
        font-weight: 700;
        padding: 15px;
        font-size: 0.9rem;
        border-bottom: 1px solid #e6e9ef;
        white-space: nowrap;
    }

    .return-table td {
        padding: 18px;
        font-size: 0.92rem;
        border-bottom: 1px solid #eceff3;
        vertical-align: middle;
    }

    .return-table tbody tr:hover {
        background: #fff6ea;
    }

    .return-icon {
        width: 42px;
        height: 42px;
        background: #fff0da;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
        color: #8a4600;
    }

    .return-status {
        background: #FF6F00;
        color: white;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }
</style>

<div class="container py-4">

    <!-- ================= HEADER ================= -->
    <div class="return-header">
        <div class="return-header-icon">
            📚
        </div>
        <div>
            <h3>Riwayat Pengembalian</h3>
            <p>Buku yang sudah Anda kembalikan ({{ count($pengembalian) }} buku)</p>
        </div>
    </div>

    <!-- ================= MAIN TABLE ================= -->
    <div class="return-table-wrapper">
        <div class="return-table-title">
            Daftar Buku Dikembalikan
        </div>

        <div class="return-table table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th># Nomor Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $p)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="return-icon">📘</div>
                                <span>{{ $p->judul_buku }}</span>
                            </div>
                        </td>
                        <td>{{ $p->nomor_buku }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('Y-m-d') }}</td>
                        <td>
                            <span class="return-status">
                                Selesai
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Belum ada pengembalian
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@include('components.footer')
@endsection
