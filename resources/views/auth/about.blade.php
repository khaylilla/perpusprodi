<!-- Updated About Page with Navy Background and Glass Padding -->
@extends('layouts.app')

@section('content')
<style>
  body {
    position: relative;
    min-height: 100vh;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: #172c46ff; /* NAVY */
  }

  /* ==== GLASS EFFECT CONTAINER ==== */
  .glass-box {
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 28px;
    padding: 45px 50px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.25);
  }

  /* ==== LAYOUT GRID SEPERTI CONTOH ==== */
  .about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 40px;
    margin-top: 80px;
  }

  .about-title {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #ffffff;
  }

  .about-text {
    font-size: 16px;
    color: #e8eaf0;
    line-height: 1.8;
  }

  .about-img {
    width: 100%;
    border-radius: 22px;
    object-fit: cover;
    box-shadow: 0 4px 18px rgba(0,0,0,0.3);
  }

  @media(max-width: 768px) {
    .about-grid {
      grid-template-columns: 1fr;
      text-align: center;
    }
  }
</style>

<div class="container py-5">
  <div class="glass-box">

    <div class="about-grid">
      <!-- TEXT -->
      <div>
        <h2 class="about-title">Tentang Perpustakaan Kami</h2>
        <p class="about-text">
          Perpustakaan Fakultas Teknik Universitas Bengkulu melaksanakan fungsi sebagai bagian dari Tri Dharma Perguruan Tinggi, dengan tugas utama mengelola koleksi karya tulis, cetak, dan rekam dalam bidang teknologi dan rekayasa untuk mendukung pendidikan, penelitian, dan kebutuhan informasi bagi sivitas akademika Fakultas Teknik.
        </p>
        <p class="about-text mt-3">
          Pengguna dari perpustakaan ini mencakup mahasiswa, staf fakultas, dan mahasiswa umum serta perguruan tinggi lainnya. Layanan yang tersedia meliputi keanggotaan, sirkulasi (peminjaman dan pengembalian) buku, peminjaman jurnal/majalah serta karya ilmiah untuk baca di tempat, layanan informasi melalui email, telepon, atau fax.
        </p>
      </div>

      <!-- IMAGE -->
      <div>
         <img src="{{ asset('FT.jpg') }}" class="about-img">
      </div>
    </div>

  </div>
</div>
@include('components.footer')
@endsection