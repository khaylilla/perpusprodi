@extends('admin.layout')

@section('page-title', 'Notifikasi')

@section('content')

<style>
    .stat-card {
        border-radius: 16px;
        padding: 20px;
        color: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    .table-container {
        background: white;
        padding: 0;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    table thead {
        background: #e7e8fc;
    }
    .search-bar {
        background: white;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        padding: 10px 15px;
        margin-bottom: 20px;
        width: 100%;
        max-width: 80%;
    }
    .search-bar input {
        border: none;
        outline: none;
        flex: 1;
        font-size: 15px;
        padding-left: 8px;
    }
    .search-bar .btn {
        white-space: nowrap;
    }
</style>

<div class="container-fluid">

    {{-- 3 KOTAK STATISTIK --}}
    <div class="d-flex flex-wrap gap-3 mb-4">

        <a href="{{ route('admin.datauser') }}" 
           class="text-decoration-none flex-grow-1"
           style="max-width: 300px;">
          <div class="card shadow-sm border-0 text-white"
               style="background: linear-gradient(135deg, #f7931e, #ffa94d); border-radius: 16px;">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h5 class="fw-bold mb-1">Manajemen Data User</h5>
                <p class="mb-0 text-light small">Kelola anggota perpustakaan</p>
              </div>
              <i class="bi bi-people-fill fs-2 opacity-75"></i>
            </div>
          </div>
        </a>

        <a href="{{ route('admin.dataabsen') }}" 
           class="text-decoration-none flex-grow-1"
           style="max-width: 300px;">
          <div class="card shadow-sm border-0 text-white"
               style="background: linear-gradient(135deg, #f7931e, #ffb84d); border-radius: 16px;">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h5 class="fw-bold mb-1">Manajemen Data Absen</h5>
                <p class="mb-0 text-light small">Pantau kehadiran anggota</p>
              </div>
              <i class="bi bi-calendar-check-fill fs-2 opacity-75"></i>
            </div>
          </div>
        </a>

        <a href="{{ route('admin.notifikasi') }}" 
           class="text-decoration-none flex-grow-1"
           style="max-width: 300px;">
          <div class="card shadow-sm border-0 text-white"
               style="background: linear-gradient(135deg, #f7931e, #ffb84d); border-radius: 16px;">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <h5 class="fw-bold mb-1">Notifikasi</h5>
                <p class="mb-0 text-light small">Lihat pemberitahuan terbaru</p>
              </div>
              <i class="bi bi-bell-fill fs-2 opacity-75"></i>
            </div>
          </div>
        </a>

    </div> {{-- END STATISTIC CARDS --}}

    {{-- BUTTON TAMBAH --}}
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Notifikasi
        </button>
    </div>

    {{-- SEARCH BAR --}}
    <form action="{{ route('admin.notifikasi') }}" method="GET" class="search-bar w-100 mb-3">
        <i class="bi bi-search"></i>
        <input type="text" name="keyword" id="searchInput" placeholder="Cari Notifikasi..." value="{{ request('keyword') }}">
        <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
    </form>

    {{-- FILTER TANGGAL --}}
    <form action="{{ route('admin.notifikasi') }}" method="GET" class="d-flex align-items-center gap-2 mb-4">
        <label class="fw-semibold mb-0">Filter Tanggal:</label>
        <input type="date" name="start_date" class="form-control form-control-sm"
               value="{{ request('start_date') }}" style="width: 160px;">
        <span class="fw-bold">s/d</span>
        <input type="date" name="end_date" class="form-control form-control-sm"
               value="{{ request('end_date') }}" style="width: 160px;">
        <button type="submit" class="btn btn-primary btn-sm ms-2">Terapkan</button>
    </form>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- TABEL --}}
    <div class="table-container">
        <table class="table text-center align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Pesan</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifikasi as $index => $n)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $n->judul }}</td>
                    <td>{{ Str::limit($n->pesan, 40) }}</td>
                    <td>{{ $n->created_at->format('d M Y') }}</td>
                    <td>
                        {{-- EDIT --}}
                        <i class="bi bi-pencil-square text-warning"
                           data-bs-toggle="modal"
                           data-bs-target="#modalEdit{{ $n->id }}"
                           style="cursor:pointer;"></i>

                        {{-- HAPUS --}}
                        <i class="bi bi-trash text-danger ms-2"
                           data-bs-toggle="modal"
                           data-bs-target="#modalHapus{{ $n->id }}"
                           style="cursor:pointer;"></i>
                    </td>
                </tr>

                {{-- MODAL EDIT --}}
                <div class="modal fade" id="modalEdit{{ $n->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.notifikasi.update', $n->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title">Edit Notifikasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Judul</label>
                                        <input type="text" name="judul" class="form-control" value="{{ $n->judul }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Pesan</label>
                                        <textarea name="pesan" class="form-control" required>{{ $n->pesan }}</textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-warning" type="submit">Simpan</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- MODAL HAPUS --}}
                <div class="modal fade" id="modalHapus{{ $n->id }}">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.notifikasi.delete', $n->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Hapus Notifikasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    Yakin ingin menghapus notifikasi ini?
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="5">Belum ada notifikasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> {{-- END container-fluid --}}

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.notifikasi.store') }}" method="POST">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Notifikasi Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Pesan</label>
                        <textarea name="pesan" class="form-control" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
