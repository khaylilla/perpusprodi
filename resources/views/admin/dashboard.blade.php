@extends('admin.layout')
@section('page-title', 'Dashboard')
@section('content')

<style>
    body {
        background: #f5f7fb;
    }

    /* ===== Dashboard Cards ===== */
    .dashboard-card {
        border-radius: 16px;
        padding: 22px;
        background: white;
        border: 0;
        box-shadow: 0 6px 22px rgba(0,0,0,0.06);
        transition: 0.28s;
        position: relative;
        overflow: hidden;
        color: #333;
    }

    .dashboard-card::after {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        top: -50%;
        left: -50%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
        transition: 0.5s;
    }

    .dashboard-card:hover::after {
        top: -30%;
        left: -30%;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }

    .dashboard-card h2 {
        font-size: 28px;
        margin-top: 8px;
        margin-bottom: 4px;
    }

    .dashboard-card h6 {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .dashboard-card small {
        font-size: 12px;
        color: #555;
    }

    /* ===== Chart Containers ===== */
    .chart-container {
        background: white;
        padding: 24px;
        border-radius: 18px;
        box-shadow: 0 6px 22px rgba(0,0,0,0.06);
        transition: 0.25s;
        position: relative;
    }

    .chart-container:hover {
        box-shadow: 0 10px 26px rgba(0,0,0,0.08);
    }

    #chartKategori {
        max-height: 260px !important;
    }

    h2, h6 {
        font-weight: 600;
    }

    /* ===== Gradient Stat Cards ===== */
    .bg-gradient-blue { background: linear-gradient(135deg, #4e73df, #1cc88a); color: white; }
    .bg-gradient-orange { background: linear-gradient(135deg, #f6c23e, #f7931e); color: white; }
    .bg-gradient-red { background: linear-gradient(135deg, #e74a3b, #f56236); color: white; }
    .bg-gradient-purple { background: linear-gradient(135deg, #8e44ad, #9b59b6); color: white; }

    /* ===== Table Styling ===== */
    .table-container {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 6px 22px rgba(0,0,0,0.06);
        transition: 0.25s;
    }

    .table-container:hover {
        box-shadow: 0 10px 26px rgba(0,0,0,0.08);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
    }

    table th {
        font-weight: 600;
        color: #555;
    }

</style>

<div class="container">

    <!-- ROW 1 – STATISTIK -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-blue">
                <h6>Total Pengunjung</h6>
                <h2>{{ $totalPengunjung }}</h2>
                <small>Hari ini: <b>{{ $pengunjungHarian }}</b></small>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-purple">
                <h6>Total User</h6>
                <h2>{{ $totalUser }}</h2>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-orange">
                <h6>Total Buku</h6>
                <h2>{{ $totalBuku }}</h2>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-blue">
                <h6>Buku Sedang Dipinjam</h6>
                <h2>{{ $totalPeminjaman }}</h2>
                <small>Bulan ini: <b>{{ $peminjamanBulanan }}</b></small>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-orange">
                <h6>Buku Dikembalikan</h6>
                <h2>{{ $totalPengembalian }}</h2>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-red">
                <h6>Total Denda</h6>
                <h2>{{ $totalDenda }}</h2>
            </div>
        </div>
    </div>

    <!-- ROW 2 – LINE CHART -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h6 class="text-center mb-2">📈 Grafik Peminjaman per Bulan</h6>
                <canvas id="chartPeminjaman"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="chart-container">
                <h6 class="text-center mb-2">📊 Grafik Pengunjung per Bulan</h6>
                <canvas id="chartPengunjung"></canvas>
            </div>
        </div>
    </div>
    

    <!-- ROW 3 – PIE CHART -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="chart-container">
                <h6 class="text-center mb-2">🟣 Diagram Kategori Buku</h6>
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>

    <!-- ROW 4 – TOP 5 TABLES -->
    <div class="row mt-4">

        <div class="col-md-6">
            <div class="table-container">
                <h6 class="text-center mb-3">📚 5 Buku Paling Sering Dipinjam</h6>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Jumlah Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bukuFavorit as $index => $buku)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $buku->judul_buku }}</td>
                            <td>{{ $buku->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="table-container">
                <h6 class="text-center mb-3">👤 5 User Paling Aktif</h6>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Jumlah Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userAktif as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bulan = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

    /* =============================
       1) LINE CHART — PEMINJAMAN & PENGEMBALIAN
    ============================== */
    const ctxLine = document.getElementById('chartPeminjaman').getContext('2d');

    const peminjaman = @json(array_values($grafikPeminjaman));
    const pengembalian = @json(array_values($grafikPengembalian));

    new Chart(ctxLine, {
        type: "line",
        data: {
            labels: bulan,
            datasets: [
                {
                    label: "Peminjaman",
                    data: peminjaman,
                    borderColor: "#2d4bf0",
                    backgroundColor: "rgba(45,75,240,0.15)",
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: "#2d4bf0"
                },
                {
                    label: "Pengembalian",
                    data: pengembalian,
                    borderColor: "#f15a29",
                    backgroundColor: "rgba(241,90,41,0.15)",
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: "#f15a29"
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: { usePointStyle: true }
                },
                tooltip: {
                    backgroundColor: "white",
                    titleColor: "#333",
                    bodyColor: "#333",
                    borderColor: "#e5e7eb",
                    borderWidth: 1,
                    displayColors: false,
                    padding: 12
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: "#666" }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: "#666" }
                }
            }
        }
    });

    /* =============================
       2) BAR CHART — USER AKTIF PER BULAN
       (PERBAIKAN: ANGKA TANPA DESIMAL)
    ============================== */
    const ctxUser = document.getElementById('chartPengunjung').getContext('2d');
    const userAktif = @json(array_values($grafikUserAktif)); // pastikan dari controller ada datanya

    new Chart(ctxUser, {
        type: "bar",
        data: {
            labels: bulan,
            datasets: [
                {
                    label: "User Aktif",
                    data: userAktif,
                    backgroundColor: "rgba(34,197,94,0.85)",
                    borderRadius: 12,
                    maxBarThickness: 40
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: "bottom" }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: "#444" }
                },
                y: {
                    beginAtZero: true,
                    ticks: { 
                        color: "#444",
                        precision: 0,
                        callback: value => Number.isInteger(value) ? value : null
                    }
                }
            }
        }
    });

    /* =============================
       3) PIE CHART — KATEGORI BUKU
    ============================== */
    new Chart(document.getElementById('chartKategori').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kategoriBuku->keys()) !!},
            datasets: [{
                data: {!! json_encode($kategoriBuku->values()) !!},
                backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc", "#f6c23e", "#e74a3b"],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection
