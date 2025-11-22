<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AdminKartuController extends Controller
{
    // Menampilkan daftar anggota
    public function index(Request $request)
    {
        $query = User::query();

        // SEARCH berdasarkan nama atau npm
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('npm', 'like', "%{$keyword}%");
            });
        }

        // FILTER berdasarkan tanggal pembuatan kartu
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($request->filled('start_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('created_at', '>=', $start);
        } elseif ($request->filled('end_date')) {
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereDate('created_at', '<=', $end);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.generate_kartu', compact('users'));
    }

    // Generate ulang kartu
    public function regenerate(User $user)
    {
        $now = Carbon::now();

        // Cek apakah kartu sudah kadaluarsa
        if($now->lt($user->created_at->addYears(2))) {
            return response()->json(['error' => 'Kartu anggota ini masih aktif.'], 400);
        }

        // Update tanggal pembuatan kartu menjadi sekarang
        $user->created_at = $now;
        $user->save();

        $masa_aktif = $user->created_at->addYears(2)->translatedFormat('d F Y');

        return response()->json([
            'success' => 'Kartu anggota berhasil digenerate ulang.',
            'masa_aktif' => $masa_aktif,
        ]);
    }
}
