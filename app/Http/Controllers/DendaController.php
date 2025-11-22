<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;
use Carbon\Carbon;

class DendaController extends Controller
{
    // Halaman denda dengan search & filter tanggal
    public function index(Request $request)
    {
        $query = Denda::query();

        // Search berdasarkan keyword (nama, npm, judul_buku)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('npm', 'like', "%{$keyword}%")
                  ->orWhere('judul_buku', 'like', "%{$keyword}%");
            });
        }

        // Filter tanggal pinjam
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date);
        }

        $denda = $query->latest()->get();

        return view('admin.riwayat.denda', compact('denda'));
    }

    // Simpan denda baru (manual)
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'npm' => 'required',
            'judul_buku' => 'required',
            'nomor_buku' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'hari_terlambat' => 'required|numeric',
            'total_denda' => 'required|numeric',
        ]);

        Denda::create($request->all());

        return redirect()->back()->with('success', 'Denda berhasil ditambahkan!');
    }

    // Update denda
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'npm' => 'required',
            'judul_buku' => 'required',
            'nomor_buku' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'hari_terlambat' => 'required|numeric',
            'total_denda' => 'required|numeric',
        ]);

        $denda = Denda::findOrFail($id);
        $denda->update($request->all());

        return redirect()->back()->with('success', 'Denda berhasil diperbarui!');
    }

    // Hapus denda
    public function destroy($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->delete();

        return response()->json(['message' => 'Denda berhasil dihapus']);
    }

    // Export PDF
    public function exportPdf()
    {
        $denda = Denda::latest()->get();
        $pdf = \PDF::loadView('admin.riwayat.denda_pdf', compact('denda'))->setPaper('a4', 'landscape');
        return $pdf->download('data_denda.pdf');
    }
}
