<!-- =============================== -->
<!--        HOME PAGE (CLEAN)        -->
<!-- =============================== -->
@extends('layouts.app')
@section('title','Beranda')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
/* ============================================================= */
/*                           GLOBAL CSS                          */
/* ============================================================= */
:root {
  --c-navy:#0A2342; --c-orange:#FFA500; --c-white:#FFF;
  --c-muted:#f5f7fa; --text:rgba(240, 142, 45, 0.92);
  --muted-text:rgba(252, 252, 252, 0.6);
  --card-bg:#fff; --shadow-soft:0 10px 30px rgba(10,35,66,0.06);
  --radius-lg:18px; --radius-md:12px; --font-base:15px;
}
body{font-family:Inter,system-ui; background:#001F54; color:var(--text);}
.container{max-width:1200px; margin:auto; padding:0 24px;}
.card{background:#fff; border-radius:var(--radius-lg); box-shadow:var(--shadow-soft);}

/* ============================================================= */
/*                   ANIMATED BORDER + GLOW                      */
/* ============================================================= */
.animated-border {
    position: relative;
    overflow: hidden;
}

.animated-border::before {
    content:"";
    position:absolute;
    inset:0;
    border-radius:inherit;
    padding:3px;
    background:linear-gradient(
      130deg,
      #FFA500,
      #ff8a00,
      #ffca38,
      #FFA500
    );
    background-size:300% 300%;
    animation:borderMove 4s linear infinite;
    mask:
      linear-gradient(#000 0 0) content-box,
      linear-gradient(#000 0 0);
    mask-composite:exclude;
    -webkit-mask-composite:destination-out;
}

@keyframes borderMove {
    0% {background-position:0% 50%;}
    100% {background-position:100% 50%;}
}

/* Hover glow */
.hover-glow:hover {
    box-shadow:0 0 20px rgba(255,165,0,0.45),
               0 0 40px rgba(255,165,0,0.25);
    transform:translateY(-4px);
    transition:0.25s;
}

/* ============================================================= */
/*                           HERO SECTION                         */
/* ============================================================= */
.hero{position:relative; height:560px; overflow:hidden;}
.hero img.bg-hero{width:100%; height:100%; object-fit:cover; filter:saturate(.95); transform:scale(1.03);} 
.hero .overlay{position:absolute; inset:0; background:linear-gradient(180deg,rgba(10,35,66,0.28),rgba(10,35,66,0.62));}
.hero .content{position:absolute; top:50%; left:6%; transform:translateY(-50%); color:#fff;}
/* ============================================================= */
/*                   KOLEKSI BUKU TERBARU SLIDER                 */
/* ============================================================= */
.collection-wrap {
    padding: 32px 0 40px;
    margin-bottom: 40px; /* Tambahan */
}
.collection-head{display:flex; justify-content:space-between; align-items:center; margin-bottom:22px;}
.collection-title-big{font-size:22px; font-weight:800;}
.collection-sub{font-size:14px; color:var(--muted-text);} 
.swiper-slide{width:360px !important;}

.koleksi-card {
    background:#fff;
    border-radius:18px;
    border:3px solid var(--c-orange);
    box-shadow:var(--shadow-soft);
    display:flex;
    flex-direction:column;
    overflow:hidden;
    min-height:520px; /* dikurangi sebelumnya 600px */
    position:relative;
}

/* Terapkan efek animasi */
.koleksi-card.animated-border,
.favorite-box.animated-border,
.article-box.animated-border,
.footer-map.animated-border {
    border:none;
    position:relative;
    overflow:hidden;
}

/* Glow on hover */
.koleksi-card:hover,
.favorite-box:hover,
.article-box:hover {
    box-shadow:0 0 25px rgba(255,165,0,0.4);
    transform:translateY(-4px);
    transition:0.25s;
}

.koleksi-img {
    width:100%;
    height:260px; /* sebelumnya 330px */
    object-fit:cover;
    border-bottom:3px solid var(--c-orange);
}


.koleksi-body {
    padding:12px;  /* sebelumnya 18px */
    gap:4px; /* lebih rapat */
}
.koleksi-title {
    font-size:15px; /* sebelumnya 16px */
    font-weight:700;
}
.koleksi-author {
    font-size:13px;
}
.koleksi-cat {
    margin-top:auto;
    padding:5px 10px;
    font-size:12px;
}

.koleksi-footer{padding:16px 18px 22px;}
.btn-koleksi{width:100%; padding:12px; border-radius:10px; border:none; background:linear-gradient(90deg,var(--c-orange),#ff8a00); color:#fff; font-weight:700;}
.swiper-button-custom{width:44px; height:44px; border-radius:12px; background:#fff; border:1px solid rgba(10,35,66,0.1); display:flex; align-items:center; justify-content:center;}
.swiper-pagination-bullet{width:10px; height:10px; background:rgba(10,35,66,0.15);} 
.swiper-pagination-bullet-active{background:var(--c-orange); width:28px; border-radius:999px;}

/* ============================================================= */
/*                FAVORITE BOOK (TOP 5) SECTION                  */
/* ============================================================= */
.favorite-box{
  background:#fff;
  padding:20px;
  border-radius:18px;
  border:3px solid var(--c-orange);
  box-shadow:var(--shadow-soft);
} 
.favorite-item{display:flex; justify-content:space-between; padding:14px 16px; border:1px solid rgba(10,35,66,0.05); border-radius:12px; background:linear-gradient(#fff,#fcfdff); margin-bottom:12px;}
.rank-badge{width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700;}
.rank-1{background:#FFF3C7;} .rank-2{background:#E6E9FF;} .rank-3{background:#FFE3D2;} 
.favorite-info h6{margin:0; font-size:15px; font-weight:700;}
.favorite-info small{color:var(--muted-text);} 
.cat-pill{padding:4px 10px; border-radius:8px; background:#e8f0ff; font-size:12px; margin-top:6px;}

/* ============================================================= */
/*                       ARTICLE SECTION                         */
/* ============================================================= */
.article-box{
  background:#fff;
  padding:20px;
  border-radius:18px;
  border:3px solid var(--c-orange);
  box-shadow:var(--shadow-soft);
} 
.article-item{padding:16px; border-radius:12px; border:1px solid rgba(10,35,66,0.06); background:linear-gradient(#fff,#fcfdff); margin-bottom:14px;}
.article-title{font-size:15px; font-weight:700;}
.article-preview{font-size:14px; color:rgba(10,35,66,0.75);} 
.blue-badge{padding:4px 10px; background:#e8f0ff; border-radius:8px; font-size:12px;}
.article-footer{display:flex; justify-content:space-between; font-size:13px; color:var(--muted-text);} 


/* ============================================================= */
/*                          RESPONSIVE                           */
/* ============================================================= */
@media(max-width:992px){.swiper-slide{width:260px !important;}}
@media(max-width:576px){.swiper-slide{width:220px !important;}}
</style>


<!-- ============================================================= -->
<!--                           HERO BANNER                         -->
<!-- ============================================================= -->
<div class="hero">
  <img src="{{ asset('FT.jpg') }}" class="bg-hero">
  <div class="overlay"></div>

  <div class="content">
    <h6 style="color:var(--c-orange);">Selamat Datang</h6>
    <h1>Perpustakaan Fakultas Teknik<br>Universitas Bengkulu</h1>
    <p>Temukan ilmu, jelajahi pengetahuan, dan wujudkan ide inovatifmu di perpustakaan kami.</p>
  </div>

  <div class="badge-logo">
    <img src="{{ asset('unib.jpg') }}" style="width:100%; height:100%; object-fit:cover;">
  </div>
</div>

<!-- ============================================================= -->
<!--                       KOLEKSI BUKU SLIDER                     -->
<!-- ============================================================= -->
<div class="container collection-wrap">

  <div class="collection-head">
      <div>
          <div class="collection-title-big">Koleksi Buku Terbaru</div>
          <div class="collection-sub">koleksi buku yang baru tersedia</div>
      </div>

      <div class="d-flex gap-3">
          <a href="{{ route('buku.index') }}" style="padding:10px 14px; border-radius:10px; border:1px solid var(--c-orange); color:var(--c-orange); font-weight:700;">
            Lihat Semua →
          </a>
      </div>
  </div>

  <div class="swiper koleksi-swiper">
      <div class="swiper-wrapper">

          @foreach($books as $book)
          @php
            $covers=json_decode($book->cover,true);
            $title=$book->title ?? $book->judul ?? 'Untitled';
            $author=$book->author ?? $book->penulis ?? '-';
            $kategori=$book->kategori->name ?? $book->kategori ?? '-';
          @endphp

          <div class="swiper-slide">
              <div class="koleksi-card">

                  @if($covers && count($covers)>0)
                    <img src="{{ asset('storage/'.$covers[0]) }}" class="koleksi-img">
                  @elseif($book->cover)
                    <img src="{{ asset('storage/'.$book->cover) }}" class="koleksi-img">
                  @else
                    <img src="/mnt/data/e758dbcc-bea2-49c8-9ac0-984958890e13.png" class="koleksi-img">
                  @endif

                  <div class="koleksi-body">
                    <div class="koleksi-title">{{ $title }}</div>
                    <div class="koleksi-author">{{ $author }}</div>
                    <div class="koleksi-cat">{{ $kategori }}</div>
                  </div>

                  <div class="koleksi-footer">
                    <a href="{{ route('buku.show',$book->id) }}" class="btn-koleksi">Lihat Detail</a>
                  </div>

              </div>
          </div>
          @endforeach

      </div>

      <div class="swiper-pagination"></div>
  </div>
</div>

<!-- ============================================================= -->
<!--                        FAVORITE BOOKS                         -->
<!-- ============================================================= -->
<div class="container my-5">
  <div class="favorite-box">

    <div class="d-flex justify-content-between mb-3">
      <h5 class="section-title">Buku Paling Sering Dipinjam</h5>
    </div>

    @foreach($bukuFavorit as $index=>$buku)
    <div class="favorite-item">

      <div class="d-flex gap-3">
        <div class="rank-badge rank-{{ $index+1 }}">#{{ $index+1 }}</div>
        <div class="favorite-info">
          <h6>{{ $buku->judul_buku }}</h6>
          <small>{{ $buku->penulis }}</small>
        </div>
      </div>

      <div class="favorite-count">📚 {{ $buku->total }}</div>

    </div>
    @endforeach

  </div>
</div>

<!-- ============================================================= -->
<!--                           ARTICLE LIST                        -->
<!-- ============================================================= -->
<div class="container my-5">

  <div class="d-flex justify-content-between mb-2">
      <div>
        <h5 class="section-title mb-1">Artikel Terbaru</h5>
        <p style="color:rgba(10,35,66,0.65); margin:0; font-size:14px;">
            Kumpulan artikel terbaru dari perpustakaan sebagai bahan wawasan dan referensi.
        </p>
      </div>

      <a class="link-blue" href="{{ route('auth.artikel') }}" style="color:#2563eb; font-weight:700;">
        Lihat Semua Artikel →
      </a>
  </div>

  <div class="article-box">
      @foreach($artikels as $artikel)
      <div class="article-item">

        <!-- Judul -->
        <div class="article-title">{{ $artikel->judul }}</div>

        <!-- Preview -->
        <div class="article-preview">
            {{ Str::limit(strip_tags($artikel->deskripsi), 140) }}
        </div>

        <!-- Footer -->
        <div class="article-footer mt-2">
          <span>{{ $artikel->created_at->format('d F Y') }}</span>
        </div>

        <!-- Tombol Selengkapnya -->
        <div style="text-align:right; margin-top:8px;">
            <a href="{{ route('auth.artikel') }}"
               style="
                 padding:6px 12px;
                 border-radius:8px;
                 background:#2563eb;
                 color:white;
                 font-size:13px;
                 font-weight:600;
                 text-decoration:none;
               ">
               Selengkapnya →
            </a>
        </div>

      </div>
      @endforeach
  </div>

</div>

  @include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
new Swiper('.swiper',{
  slidesPerView:'auto', spaceBetween:22, loop:true,
  autoplay:{delay:2600, disableOnInteraction:false},
});
</script>
@endsection
