@extends('admin.layout')

@section('page-title', 'Manajemen Denda Buku')

@section('content')
{{-- STYLE TAMBAHAN --}}
<style>
  .info-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
  }

  .info-box {
    background: linear-gradient(135deg, #e53935, #ff7043);
    color: white;
    border-radius: 16px;
    width: 320px;
    padding: 20px 24px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    transition: transform 0.2s ease;
  }

  .info-box:hover { transform: translateY(-3px); }
  .info-box i { font-size: 32px; opacity: 0.8; }
  .info-box-content h5 { margin: 0; font-weight: 700; font-size: 16px; }
  .info-box-content p { font-size: 13px; margin: 3px 0 0 0; }

  .table-container {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    overflow-x: auto;
    margin-top: 10px;
    padding: 15px;
  }

  table { width: 100%; border-collapse: collapse; min-width: 1000px; }
  table th, table td {
    padding: 10px;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #ddd;
  }
  table th { background-color: #f2f2f2; font-weight: 600; }
  table tbody tr:hover { background-color: #fff6f6; }

  .badge { font-size: 0.85em; }
  .action-icons i { cursor: pointer; font-size: 18px; margin: 0 5px; }
  .action-icons i:hover { opacity: 0.7; }
</style>

  {{-- TOMBOL TAMBAH & PDF --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg"></i> Tambah Denda
      </button>
    </div>
    <a href="#" id="downloadPdf" class="btn btn-success"><i class="bi bi-file-earmark-pdf"></i> Download PDF</a>
  </div>

  {{-- TABEL DENDA --}}
  <div class="table-container">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>NPM</th>
          <th>Judul Buku</th>
          <th>Nomor Buku</th>
          <th>Tanggal Pinjam</th>
          <th>Tanggal Kembali</th>
          <th>Hari Terlambat</th>
          <th>Total Denda</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($denda as $index => $item)
          <tr 
            data-id="{{ $item->id }}" 
            data-nama="{{ $item->nama }}" 
            data-npm="{{ $item->npm }}" 
            data-judul="{{ $item->judul_buku }}" 
            data-nomor="{{ $item->nomor_buku }}" 
            data-pinjam="{{ $item->tanggal_pinjam }}" 
            data-kembali="{{ $item->tanggal_kembali }}" 
            data-hari="{{ $item->hari_terlambat }}" 
            data-total="{{ $item->total_denda }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->npm }}</td>
            <td>{{ $item->judul_buku }}</td>
            <td>{{ $item->nomor_buku }}</td>
            <td>{{ $item->tanggal_pinjam }}</td>
            <td>{{ $item->tanggal_kembali }}</td>
            <td><span class="badge bg-warning text-dark">{{ $item->hari_terlambat }} hari</span></td>
            <td><span class="badge bg-danger">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</span></td>
            <td class="action-icons">
              <i class="bi bi-pencil-square text-primary btn-edit" title="Edit"></i>
              <i class="bi bi-trash text-danger btn-delete" title="Hapus"></i>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="text-center text-muted">Tidak ada data denda 📚</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.riwayat.denda.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Denda</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>NPM</label>
            <input type="text" name="npm" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Judul Buku</label>
            <input type="text" name="judul_buku" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Nomor Buku</label>
            <input type="text" name="nomor_buku" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Hari Terlambat</label>
            <input type="number" name="hari_terlambat" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Total Denda</label>
            <input type="number" name="total_denda" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Edit Denda</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
          <div class="mb-2"><label>NPM</label><input type="text" name="npm" class="form-control" required></div>
          <div class="mb-2"><label>Judul Buku</label><input type="text" name="judul_buku" class="form-control" required></div>
          <div class="mb-2"><label>Nomor Buku</label><input type="text" name="nomor_buku" class="form-control" required></div>
          <div class="mb-2"><label>Tanggal Pinjam</label><input type="date" name="tanggal_pinjam" class="form-control" required></div>
          <div class="mb-2"><label>Tanggal Kembali</label><input type="date" name="tanggal_kembali" class="form-control" required></div>
          <div class="mb-2"><label>Hari Terlambat</label><input type="number" name="hari_terlambat" class="form-control" required></div>
          <div class="mb-2"><label>Total Denda</label><input type="number" name="total_denda" class="form-control" required></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- SCRIPT MODAL --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

  // EDIT Denda
  const editButtons = document.querySelectorAll('.btn-edit');
  const editForm = document.getElementById('editForm');

  editButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const tr = this.closest('tr');
      editForm.action = `/admin/denda/${tr.dataset.id}/update`;
      editForm.nama.value = tr.dataset.nama;
      editForm.npm.value = tr.dataset.npm;
      editForm.judul_buku.value = tr.dataset.judul;
      editForm.nomor_buku.value = tr.dataset.nomor;
      editForm.tanggal_pinjam.value = tr.dataset.pinjam;
      editForm.tanggal_kembali.value = tr.dataset.kembali;
      editForm.hari_terlambat.value = tr.dataset.hari;
      editForm.total_denda.value = tr.dataset.total;
      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
  });

  // HAPUS Denda
  const deleteButtons = document.querySelectorAll('.btn-delete');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const tr = this.closest('tr');
      if(confirm('Yakin ingin menghapus data denda ini?')) {
        fetch(`/admin/denda/${tr.dataset.id}/delete`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        }).then(res => location.reload());
      }
    });
  });

  // Download PDF
  const downloadPdf = document.getElementById('downloadPdf');
  downloadPdf.addEventListener('click', function() {
    window.open('{{ route("admin.riwayat.denda.pdf") }}', '_blank');
  });

});
</script>
@endsection
