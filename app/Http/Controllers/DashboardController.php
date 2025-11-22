<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\User;
use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->mode ?? 'bulan';
        $start = $request->start;
        $end   = $request->end;

        // ======== FILTER RANGE ========
        $queryRange = function($query) use ($mode, $start, $end) {
            if ($mode == 'hari') {
                $date = $start ?? now()->toDateString();
                $query->whereDate('created_at', $date);
            } elseif ($mode == 'bulan') {
                $carbon = $start ? Carbon::parse($start) : now();
                $query->whereYear('created_at', $carbon->year)
                      ->whereMonth('created_at', $carbon->month);
            } elseif ($mode == 'tahun') {
                $tahun = $start ? Carbon::parse($start)->year : now()->year;
                $query->whereYear('created_at', $tahun);
            } elseif ($mode == 'range_tahun') {
                $tahunAwal = $start ? Carbon::parse($start)->year : 2025;
                $tahunAkhir = $end ? Carbon::parse($end)->year : now()->year;
                $query->whereYear('created_at', '>=', $tahunAwal)
                      ->whereYear('created_at', '<=', $tahunAkhir);
            }
        };

        // ======== STATISTIK ========
        $totalPengunjung = Absen::when(true, $queryRange)->count();
        $pengunjungHarian = Absen::whereDate('created_at', now())->count();
        $totalUser = User::count();
        $totalBuku = Book::count();
        $totalPeminjaman = Peminjaman::where('status', 'dipinjam')->when(true, $queryRange)->count();
        $peminjamanBulanan = Peminjaman::whereMonth('created_at', now()->month)->count();
        $totalPengembalian = Peminjaman::where('status', 'dikembalikan')->when(true, $queryRange)->count();

        // ======== TOP DATA ========
        $bukuFavorit = Peminjaman::select('judul_buku', DB::raw('COUNT(*) as total'))
            ->when(true, $queryRange)
            ->groupBy('judul_buku')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $userAktif = Peminjaman::select('nama', DB::raw('COUNT(*) as total'))
            ->when(true, $queryRange)
            ->groupBy('nama')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // ======== GRAFIK ========
        $labels = [];
        $grafikPeminjaman = [];
        $grafikPengembalian = [];
        $grafikUserAktif = [];

        // Fungsi hitung user aktif per periode
        $getUserAktif = function($query) {
            return $query->where(function($q) {
                $q->where('status', 'dipinjam')
                  ->orWhere('status', 'dikembalikan');
            })->distinct('nama')->count('nama');
        };

        if($mode == 'hari') {
            $labels = range(0,23);
            foreach($labels as $i){
                $grafikPeminjaman[$i] = Peminjaman::whereDate('created_at', $start ?? now())
                    ->whereHour('created_at', $i)->count();
                $grafikPengembalian[$i] = Peminjaman::whereDate('created_at', $start ?? now())
                    ->whereHour('created_at', $i)
                    ->where('status','dikembalikan')->count();
                $grafikUserAktif[$i] = $getUserAktif(Peminjaman::whereDate('created_at', $start ?? now())
                    ->whereHour('created_at', $i));
            }
        } elseif($mode == 'bulan') {
            $carbon = Carbon::parse($start ?? now());
            $days = $carbon->daysInMonth;
            $labels = range(1,$days);
            foreach($labels as $i){
                $grafikPeminjaman[$i-1] = Peminjaman::whereYear('created_at',$carbon->year)
                    ->whereMonth('created_at',$carbon->month)
                    ->whereDay('created_at',$i)->count();
                $grafikPengembalian[$i-1] = Peminjaman::whereYear('created_at',$carbon->year)
                    ->whereMonth('created_at',$carbon->month)
                    ->whereDay('created_at',$i)
                    ->where('status','dikembalikan')->count();
                $grafikUserAktif[$i-1] = $getUserAktif(Peminjaman::whereYear('created_at',$carbon->year)
                    ->whereMonth('created_at',$carbon->month)
                    ->whereDay('created_at',$i));
            }
        } elseif($mode == 'tahun') {
            $tahun = $start ? Carbon::parse($start)->year : now()->year;
            $labels = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
            foreach(range(1,12) as $i){
                $grafikPeminjaman[$i-1] = Peminjaman::whereYear('created_at',$tahun)
                    ->whereMonth('created_at',$i)->count();
                $grafikPengembalian[$i-1] = Peminjaman::whereYear('created_at',$tahun)
                    ->whereMonth('created_at',$i)
                    ->where('status','dikembalikan')->count();
                $grafikUserAktif[$i-1] = $getUserAktif(Peminjaman::whereYear('created_at',$tahun)
                    ->whereMonth('created_at',$i));
            }
        } elseif($mode == 'range_tahun') {
            $tahunAwal  = $start ? Carbon::parse($start)->year : 2025;
            $tahunAkhir = $end   ? Carbon::parse($end)->year : now()->year;
            $labels = range($tahunAwal,$tahunAkhir);
            foreach($labels as $i => $tahun){
                $grafikPeminjaman[$i] = Peminjaman::whereYear('created_at',$tahun)->count();
                $grafikPengembalian[$i] = Peminjaman::whereYear('created_at',$tahun)
                    ->where('status','dikembalikan')->count();
                $grafikUserAktif[$i] = $getUserAktif(Peminjaman::whereYear('created_at',$tahun));
            }
        }

        // ======== KATEGORI BUKU ========
        $kategoriBuku = Book::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total','kategori');

        return view('admin.dashboard', compact(
            'totalPengunjung','pengunjungHarian','totalUser','totalBuku',
            'totalPeminjaman','peminjamanBulanan','totalPengembalian',
            'bukuFavorit','userAktif','labels','grafikPeminjaman','grafikPengembalian','grafikUserAktif','kategoriBuku'
        ));
    }
}
