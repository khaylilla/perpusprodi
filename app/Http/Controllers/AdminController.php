<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notifikasi;

class AdminController extends Controller
{
    public function dataUser(Request $request)
    {
        $query = User::query();

        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where('nama', 'like', "%$keyword%")
                  ->orWhere('npm', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%");
        }

        $users = $query->get();

        return view('admin.datauser', compact('users'));
    }

    public function createUser()
    {
        return view('admin.createuser');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'alamat' => 'nullable|string',
            'nohp' => 'nullable|string',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'nohp' => $request->nohp,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.datauser')->with('success', 'User berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edituser', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:50|unique:users,npm,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'alamat' => 'nullable|string',
            'nohp' => 'nullable|string',
        ]);

        $user->update([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'nohp' => $request->nohp,
        ]);

        return redirect()->route('admin.datauser')->with('success', 'Data user berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.datauser')->with('success', 'User berhasil dihapus!');
    }

    public function notifikasi(Request $request)
{
    $notifikasi = Notifikasi::latest()->get();
    $totalNotif = Notifikasi::count();
    $notifBaru = Notifikasi::whereDate('created_at', today())->count();

   return view('admin.notifikasi', compact('notifikasi', 'totalNotif', 'notifBaru'));
}

public function notifikasiStore(Request $request)
{
    $request->validate([
        'judul' => 'required',
        'pesan' => 'required',
    ]);

    Notifikasi::create($request->all());

    return redirect()->back()->with('success', 'Notifikasi berhasil ditambahkan!');
}

public function notifikasiUpdate(Request $request, $id)
{
    $notifikasi = Notifikasi::findOrFail($id);
    $notifikasi->update($request->all());

    return redirect()->back()->with('success', 'Notifikasi berhasil diperbarui!');
}

public function notifikasiDelete($id)
{
    Notifikasi::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Notifikasi berhasil dihapus!');
}
}

