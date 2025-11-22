<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #ff9800;">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('unib.jpg') }}" alt="Logo" width="40" height="40" class="me-2 rounded-circle">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Menu kiri -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link fw-bold text-dark" href="{{ route('home') }}">Beranda</a></li>
        <li class="nav-item"><a class="nav-link fw-bold text-dark" href="{{ route('about') }}">Tentang</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="bukuDropdown" role="button" data-bs-toggle="dropdown">Buku</a>
          <ul class="dropdown-menu" aria-labelledby="bukuDropdown">
            <li><a class="dropdown-item" href="{{ route('buku.kategori', 'Bacaan') }}">Bacaan</a></li>
            <li><a class="dropdown-item" href="{{ route('buku.kategori', 'Skripsi') }}">Skripsi</a></li>
            <li><a class="dropdown-item" href="{{ route('buku.kategori', 'Referensi') }}">Referensi</a></li>
            <li><a class="dropdown-item" href="{{ route('buku.index') }}">Semua</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="lainnyaDropdown" role="button" data-bs-toggle="dropdown">Lainnya</a>
          <ul class="dropdown-menu" aria-labelledby="lainnyaDropdown">
            <li><a class="dropdown-item" href="{{ route('auth.artikel') }}">Artikel</a></li>
            <li><a class="dropdown-item" href="{{ route('auth.kontak') }}">Kontak</a></li>
            </li>
          </ul>
        </li>
      </ul>

      <!-- Icon Notifikasi -->
<div class="dropdown me-3">
    @php
        // Hitung notifikasi yang belum dibaca
        $notifikasiCount = \App\Models\Notifikasi::whereNull('read_at')->count();
        $notifikasis = \App\Models\Notifikasi::latest()->get();
    @endphp

    <a href="#" class="text-dark position-relative" id="notificationDropdown" data-bs-toggle="dropdown">
        <i class="bi bi-bell fs-4"></i>
        @if($notifikasiCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifBadge">
            {{ $notifikasiCount }}
        </span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end p-2" style="width: 300px; max-height: 400px; overflow-y: auto;">
        @forelse($notifikasis as $notif)
        <li>
            <a href="#" class="dropdown-item notif-item" data-bs-toggle="modal" data-bs-target="#notifModal"
               data-id="{{ $notif->id }}"
               data-title="{{ $notif->judul }}" data-message="{{ $notif->pesan }}" data-time="{{ $notif->created_at }}">
                <div class="d-flex justify-content-between">
                    <div>{{ Str::limit($notif->judul, 30) }}</div>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
            </a>
        </li>
        @empty
        <li class="text-center text-muted p-2">Tidak ada notifikasi</li>
        @endforelse
    </ul>
</div>

<!-- Modal Notifikasi -->
<div class="modal fade" id="notifModal" tabindex="-1" aria-labelledby="notifModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notifModalLabel">Judul Notifikasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="notifMessage"></p>
        <small class="text-muted" id="notifTime"></small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

      <!-- Profil -->
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown">
          @php
              $foto = Auth::user()->foto ?? null;
          @endphp
          @if ($foto && file_exists(storage_path('app/public/foto/'.$foto)))
              <img src="{{ asset('storage/foto/'.$foto) }}" alt="Foto Profil" width="40" height="40" class="rounded-circle border border-dark me-2">
          @else
              <i class="bi bi-person-circle fs-3 me-2"></i>
          @endif
          <span class="fw-bold">{{ Auth::user()->nama ?? 'Pengguna' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
          <li><a class="dropdown-item" href="{{ route('card', Auth::user()->id) }}">Kartu Anggota</a></li>
          <li><a class="dropdown-item" href="{{ route('profile', Auth::user()->id) }}">Profil Saya</a></li>
          <!-- Submenu Riwayat -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#">Riwayat</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('auth.borrow-history') }}">Peminjaman</a></li>
                <li><a class="dropdown-item" href="{{ route('auth.return-history') }}">Pengembalian</a></li>
                <li><a class="dropdown-item" href="{{ route('auth.fine-history') }}">Denda</a></li>
              </ul>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
              @csrf
              <button type="submit" class="dropdown-item text-danger">Logout</button>
            </form>
          </li>
        </ul>
      </div>

    </div>
  </div>
</nav>

<style>
/* Submenu */
/* Submenu kanan hover */
.dropdown-submenu {
    position: relative;
}
.dropdown-submenu > .dropdown-menu {
    top: 0;
    right: 100%; /* muncul di kanan */
    left: auto;
    margin-top: -1px;
    display: none;
    position: absolute;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    z-index: 1050;
    transition: all 0.2s ease-in-out;
}
.dropdown-submenu:hover > .dropdown-menu {
    display: block; /* muncul saat hover */
}
.dropdown-submenu > a::after {
    content: " ▸";
    float: left;
    margin-right: 5px;
}

/* Submenu hover effect */
.dropdown-item:hover { background-color: #ff9800 !important; color: #fff !important; }

/* Badge */
.badge { font-size: 0.65rem; padding: 0.25em 0.45em; }

/* Notifikasi hover */
.notif-item:hover { background-color: #f8f9fa; cursor: pointer; }

/* Scroll dropdown */
.dropdown-menu { scrollbar-width: thin; scrollbar-color: #ccc transparent; }
.dropdown-menu::-webkit-scrollbar { width: 6px; }
.dropdown-menu::-webkit-scrollbar-thumb { background-color: #ccc; border-radius: 3px; }

/* ============================= */
/* === HOVER NAVBAR DITAMBAHKAN === */
/* ============================= */
.navbar-nav .nav-link:hover {
    color: #ffffff !important;
    background-color: #ffb74d; /* orange lebih muda */
    border-radius: 5px;
    transition: 0.2s ease-in-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var notifModal = document.getElementById('notifModal');
    var modalTitle = notifModal.querySelector('#notifModalLabel');
    var modalMessage = notifModal.querySelector('#notifMessage');
    var modalTime = notifModal.querySelector('#notifTime');
    var notifBadge = document.getElementById('notifBadge');

    document.querySelectorAll('.notif-item').forEach(function(item){
        item.addEventListener('click', function(){
            modalTitle.textContent = this.dataset.title;
            modalMessage.textContent = this.dataset.message;
            modalTime.textContent = this.dataset.time;

            let notifId = this.dataset.id;
            fetch(`/notifikasi/${notifId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
            }).then(() => {
                if(notifBadge){
                    let count = parseInt(notifBadge.textContent);
                    if(count > 1){
                        notifBadge.textContent = count - 1;
                    } else {
                        notifBadge.remove();
                    }
                }
            });
        });
    });

    document.querySelectorAll('.dropdown-submenu > a').forEach(function(el){
        el.addEventListener('click', function(e){
            if(window.innerWidth < 992){
                e.preventDefault();
                this.nextElementSibling.classList.toggle('show');
            }
        });
    });

    document.querySelectorAll('.dropdown').forEach(function(dd){
        dd.addEventListener('hidden.bs.dropdown', function(){
            this.querySelectorAll('.dropdown-menu.show').forEach(function(menu){
                menu.classList.remove('show');
            });
        });
    });
});
</script>
