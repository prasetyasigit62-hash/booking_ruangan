<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // ==========================================
    // 1. FITUR PROFIL SAYA
    // ==========================================
    public function profile()
    {
        return view('users.profile', [
            'title' => 'Pengaturan Profil',
            'user' => auth()->user() // Ambil data user yang sedang login
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Validasi inputan + File Foto
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Max 2MB
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Jika isi password baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Proses Upload Foto Baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama di folder jika ada
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            // Simpan foto baru ke public/uploads/profil
            $file = $request->file('foto');
            $namaFile = time() . '_profil_' . $user->id . '.' . $file->extension();
            $file->move(public_path('uploads/profil'), $namaFile);

            // Simpan path ke database
            $user->foto = 'uploads/profil/' . $namaFile;
        }

        $user->save();

        return back()->with('success', 'Profil dan Kredensial berhasil diperbarui! ✨');
    }
    
    // ==========================================
    // 2. FITUR MANAJEMEN PETUGAS (CRUD)
    // ==========================================
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('terdaftar', function ($row) {
                    return $row->created_at->translatedFormat('d F Y');
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<div class="d-flex justify-content-center gap-2">';

                    // Tombol Edit (Kirim data ke JS)
                    $btn .= '<button type="button" class="btn-action btn-action-info" 
                                onclick="editPetugas(' . $row->id . ', \'' . addslashes($row->name) . '\', \'' . addslashes($row->email) . '\')" 
                                title="Edit Petugas">
                                <i class="fas fa-edit"></i>
                             </button>';

                    // Tombol Hapus (Kecuali diri sendiri)
                    if (auth()->id() != $row->id) {
                        $btn .= '<form action="' . route('users.destroy', $row->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin ingin mencabut akses login petugas ini?\');">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                    <button type="submit" class="btn-action" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545; border: none;" title="Hapus Petugas">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                 </form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('users.index', ['title' => 'Manajemen Petugas']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Petugas baru berhasil ditambahkan! 🎉');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8' // Opsional jika ingin ganti password
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'Data petugas berhasil diperbarui! ✨');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah Admin menghapus dirinya sendiri yang sedang login
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri saat sedang login!');
        }

        $user->delete();
        return back()->with('success', 'Petugas berhasil dihapus! 🗑️');
    }
}
