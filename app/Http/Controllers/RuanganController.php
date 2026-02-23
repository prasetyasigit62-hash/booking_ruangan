<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        if ($request->ajax()) {
            $data = Ruangan::latest()->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('foto_ruangan', function ($row) {
                    // (Biarkan kode foto Anda yang sudah ada di sini)
                    $foto = $row->foto ? asset('storage/' . $row->foto) : asset('images/default.png');
                    return '<img src="' . $foto . '" class="img-thumbnail rounded" style="width: 80px; height: 80px; object-fit: cover;">';
                })
                ->addColumn('info', function ($row) {
                    // 1. Menampilkan Nama dan Kapasitas Ruangan
                    $html = '<div class="fw-bold text-dark fs-6 mb-1">' . $row->nama_ruangan . '</div>';
                    $html .= '<div class="text-muted small mb-2"><i class="fas fa-users text-primary me-1"></i> Kapasitas: ' . $row->kapasitas . ' Orang</div>';

                    // 2. Menambahkan Label Harga Sewa yang Cantik
                    $html .= '<div class="d-flex flex-wrap gap-1 mt-1">';
                    $html .= '<span class="badge bg-light text-dark border"><i class="fas fa-clock text-warning me-1"></i> 5 Jam: Rp ' . number_format($row->harga_5_jam ?? 0, 0, ',', '.') . '</span>';
                    $html .= '<span class="badge bg-light text-dark border"><i class="fas fa-calendar-day text-success me-1"></i> 1 Hari: Rp ' . number_format($row->harga_1_hari ?? 0, 0, ',', '.') . '</span>';
                    $html .= '<span class="badge bg-light text-dark border"><i class="fas fa-calendar-alt text-info me-1"></i> 3 Hari: Rp ' . number_format($row->harga_3_hari ?? 0, 0, ',', '.') . '</span>';
                    $html .= '<span class="badge bg-light text-dark border"><i class="fas fa-calendar-week text-primary me-1"></i> 1 Mgg: Rp ' . number_format($row->harga_1_minggu ?? 0, 0, ',', '.') . '</span>';
                    $html .= '</div>';

                    return $html;
                })
                ->addColumn('fasilitas_ruangan', function ($row) {
                    return $row->fasilitas;
                })
                ->addColumn('aksi', function ($row) {
                    // 3. Menyuntikkan data harga ke dalam tombol edit agar Modal Edit bisa menyedotnya
                    $btn = '<button type="button" class="btn btn-warning btn-sm btn-edit me-1" 
                                data-id="' . $row->id . '" 
                                data-nama="' . $row->nama_ruangan . '" 
                                data-kapasitas="' . $row->kapasitas . '" 
                                data-fasilitas="' . $row->fasilitas . '"
                                data-harga-5-jam="' . $row->harga_5_jam . '"
                                data-harga-1-hari="' . $row->harga_1_hari . '"
                                data-harga-3-hari="' . $row->harga_3_hari . '"
                                data-harga-1-minggu="' . $row->harga_1_minggu . '"
                            ><i class="fas fa-edit"></i></button>';

                    $btn .= '<form action="' . route('ruangan.destroy', $row->id) . '" method="POST" class="d-inline">
                                ' . csrf_field() . '
                                ' . method_field("DELETE") . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus ruangan ini?\')"><i class="fas fa-trash"></i></button>
                            </form>';
                    return $btn;
                })
                ->rawColumns(['foto_ruangan', 'info', 'fasilitas_ruangan', 'aksi'])
                ->make(true);
        }

        return view('ruangan.index');
    }

    public function store(Request $request)
    {
        // 1. AREA VALIDASI
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer',
            'fasilitas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_5_jam' => 'nullable|numeric',
            'harga_1_hari' => 'nullable|numeric',
            'harga_3_hari' => 'nullable|numeric',
            'harga_1_minggu' => 'nullable|numeric',
        ]);

        $data = $request->except(['foto']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('ruangan', 'public');
        }

        // 2. AREA SIMPAN KE DATABASE
        $data['harga_5_jam'] = $request->harga_5_jam ?? 0;
        $data['harga_1_hari'] = $request->harga_1_hari ?? 0;
        $data['harga_3_hari'] = $request->harga_3_hari ?? 0;
        $data['harga_1_minggu'] = $request->harga_1_minggu ?? 0;

        Ruangan::create($data);

        return back()->with('success', 'Data Ruangan lengkap berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // 1. AREA VALIDASI (Hanya berisi aturan baku/teks)
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'nullable|integer',
            'fasilitas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_5_jam' => 'nullable|numeric',
            'harga_1_hari' => 'nullable|numeric',
            'harga_3_hari' => 'nullable|numeric',
            'harga_1_minggu' => 'nullable|numeric',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $data = $request->except(['foto']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('ruangan', 'public');
        }

        // 2. AREA UPDATE KE DATABASE (Di sinilah data/angka dimasukkan)
        $data['harga_5_jam'] = $request->harga_5_jam ?? 0;
        $data['harga_1_hari'] = $request->harga_1_hari ?? 0;
        $data['harga_3_hari'] = $request->harga_3_hari ?? 0;
        $data['harga_1_minggu'] = $request->harga_1_minggu ?? 0;

        $ruangan->update($data);

        return back()->with('success', 'Data Ruangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        // Hapus file foto dari penyimpanan sebelum menghapus data database
        if ($ruangan->foto) {
            Storage::disk('public')->delete($ruangan->foto);
        }

        $ruangan->delete();

        return back()->with('success', 'Data Ruangan beserta fotonya berhasil dihapus!');
    }

    public function getHarga($id)
    {
        return Ruangan::findOrFail($id);
    }
}
