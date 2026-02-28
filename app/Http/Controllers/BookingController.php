<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\KonfirmasiBookingMail;
use App\Exports\BookingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // 1. Logika Server-Side AJAX DataTables
        if ($request->ajax()) {
            // Ambil data booking dengan relasi user dan ruangan
            $data = Booking::with(['user', 'ruangan'])->select('bookings.*');

            return DataTables::of($data)
                ->addIndexColumn()

                // MANIPULASI KOLOM TANGGAL (Untuk Efek Berkedip H-2)
                ->editColumn('waktu_mulai', function ($row) {
                    $hariIni = Carbon::now()->startOfDay();
                    $tanggalAcara = Carbon::parse($row->waktu_mulai)->startOfDay();
                    $selisihHari = $hariIni->diffInDays($tanggalAcara, false);

                    $tanggalFormat = Carbon::parse($row->waktu_mulai)->format('d M Y');
                    $waktuFormat = Carbon::parse($row->waktu_mulai)->format('H:i');

                    // Cek jika acara H-2, H-1, atau Hari H (dan status aktif)
                    $isMendekati = ($selisihHari >= 0 && $selisihHari <= 2) && in_array($row->status_booking, ['Pending', 'Dikonfirmasi']);

                    if ($isMendekati) {
                        $labelPeringatan = $selisihHari == 0 ? 'HARI INI' : 'H-' . $selisihHari;
                        $warnaBadge = $selisihHari == 0 ? 'bg-danger' : 'bg-warning text-dark';

                        return '
                            <div class="p-2 rounded blink-alert text-center">
                                <span class="small fw-bold text-uppercase">' . $tanggalFormat . '</span><br>
                                <span class="badge ' . $warnaBadge . ' shadow-sm animate-pulse mt-1">
                                    <i class="fas fa-clock me-1"></i> ' . $labelPeringatan . ' (' . $waktuFormat . ')
                                </span>
                            </div>';
                    }

                    return '<div class="text-center">' . $tanggalFormat . '<br><small class="text-muted">' . $waktuFormat . ' WIB</small></div>';
                })

                // MANIPULASI KOLOM AKSI (Tambahkan Tombol Remind WA)
                ->addColumn('action', function ($row) {
                    // Tombol standar (Anda bisa sesuaikan ID dan Class-nya)
                    $btn = '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-detail" data-id="' . $row->id . '"><i class="fas fa-eye"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-info btn-edit" data-id="' . $row->id . '"><i class="fas fa-edit"></i></button>';

                    // Logika Tambah Tombol WhatsApp jika H-2
                    $hariIni = Carbon::now()->startOfDay();
                    $tanggalAcara = Carbon::parse($row->waktu_mulai)->startOfDay();
                    $selisihHari = $hariIni->diffInDays($tanggalAcara, false);

                    if (($selisihHari >= 0 && $selisihHari <= 2) && in_array($row->status_booking, ['Pending', 'Dikonfirmasi'])) {
                        $urlReminder = route('admin.booking.remind.single', $row->id);
                        $btn .= '<a href="' . $urlReminder . '" target="_blank" class="btn btn-sm btn-success" title="Kirim WA Pengingat">
                                    <i class="fab fa-whatsapp"></i> Remind
                                 </a>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['waktu_mulai', 'action']) // Penting agar HTML dirender browser
                ->make(true);
        }

        // 2. Logika Normal (Bukan AJAX) - Memuat Halaman Pertama Kali
        $ruangans = Ruangan::where('status', 'Tersedia')->get();

        return view('booking.index', [
            'title'    => 'Sistem Booking Ruangan',
            'ruangans' => $ruangans
        ]);
    }

    // API Server-Side untuk menarik data ke FullCalendar
    public function getEvents(Request $request)
    {
        try {
            // 1. [REVISI] Tambahkan relasi 'user' ke dalam with() agar sistem bisa memanggil nama user
            $bookings = Booking::with(['ruangan:id,nama_ruangan', 'user'])
                ->whereIn('status_booking', ['Pending', 'Dikonfirmasi', 'Selesai'])
                ->get();

            $events = $bookings->map(function ($booking) {
                if ($booking->status_booking === 'Selesai') {
                    $color = '#007bff';
                    $textColor = '#ffffff';
                    $className = 'event-selesai';
                } elseif ($booking->status_booking === 'Dikonfirmasi') {
                    $color = '#198754';
                    $textColor = '#ffffff';
                    $className = 'event-dikonfirmasi';
                } else {
                    $color = '#ffc107';
                    $textColor = '#000000';
                    $className = 'event-pending';
                }

                return [
                    'id'            => $booking->id,
                    'title'         => ($booking->ruangan->nama_ruangan ?? 'Ruangan') . ' - ' . $booking->keperluan,
                    'start'         => \Carbon\Carbon::parse($booking->waktu_mulai)->format('Y-m-d\TH:i:s'),
                    'end'           => \Carbon\Carbon::parse($booking->waktu_selesai)->format('Y-m-d\TH:i:s'),
                    'color'         => $color,
                    'textColor'     => $textColor,
                    'classNames'    => $className,
                    'extendedProps' => [
                        'status'       => $booking->status_booking,
                        'nama_penyewa' => $booking->user->name ?? 'Penyewa',
                        'nohp'         => $booking->no_hp, // Wajib pakai garis bawah sesuai database
                        'keperluan'    => $booking->keperluan,
                        'kode_booking' => $booking->kode_booking,
                    ]
                ];
            });

            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Fungsi Menerima Data AJAX Form Booking
    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                // 1. Validasi Input (+ Tambahan no_hp)
                $request->validate([
                    'nama_peminjam' => 'required|string|max:255',
                    'no_hp'         => 'required|numeric', // <-- Baru
                    'ruangan_id'    => 'required',
                    'waktu_mulai'   => 'required|date',
                    'waktu_selesai' => 'required|date|after:waktu_mulai',
                    'keperluan'     => 'required|string|max:255',
                ]);

                $start = \Carbon\Carbon::parse($request->waktu_mulai);
                $end = \Carbon\Carbon::parse($request->waktu_selesai);

                // 2. Cek Bentrok Jadwal (Sama persis dengan kode Anda)
                $bentrok = Booking::where('ruangan_id', $request->ruangan_id)
                    ->whereIn('status_booking', ['Pending', 'Dikonfirmasi', 'Digunakan'])
                    ->where(function ($query) use ($start, $end) {
                        $query->where('waktu_mulai', '<', $end)
                            ->where('waktu_selesai', '>', $start);
                    })
                    ->first();

                if ($bentrok) {
                    return response()->json([
                        'errors' => ['sistem' => ['Maaf! Ruangan sudah dibooking pada rentang waktu tersebut.']]
                    ], 422);
                }

                // 3. Buat User / Peminjam Otomatis (Sama persis dengan kode Anda)
                $user = \App\Models\User::firstOrCreate(
                    ['name' => $request->nama_peminjam],
                    [
                        'email'    => strtolower(str_replace(' ', '', $request->nama_peminjam)) . rand(100, 999) . '@tamu.com',
                        'password' => bcrypt('password123')
                    ]
                );

                $total = $request->total_bayar ?? 0;

                // 4. Buat Kode Booking Dulu (Diubah sedikit agar kodenya bisa dipanggil ke WA)
                $kode_booking_baru = 'BKG-' . strtoupper(\Illuminate\Support\Str::random(6));

                // 5. Simpan ke Database (+ Tambahan no_hp)
                Booking::create([
                    'kode_booking'   => $kode_booking_baru, // <-- Pakai variabel yang dibuat di atas
                    'no_hp'          => $request->no_hp,    // <-- Baru
                    'user_id'        => $user->id,
                    'ruangan_id'     => $request->ruangan_id,
                    'waktu_mulai'    => $start->format('Y-m-d H:i:s'),
                    'waktu_selesai'  => $end->format('Y-m-d H:i:s'),
                    'keperluan'      => $request->keperluan,
                    'total_bayar'    => $total,
                    'status_booking' => 'Pending',
                ]);

                // 6. SIAPKAN LOGIKA WHATSAPP (Format Baru & Lebih Profesional)

                // Ambil data nama ruangan (opsional, untuk memperjelas pesan WA)
                $ruangan = \App\Models\Ruangan::find($request->ruangan_id);
                $namaRuangan = $ruangan ? $ruangan->nama_ruangan : 'Ruangan ' . $request->ruangan_id;

                $pesan = "Halo *{$request->nama_peminjam}*! ✨\n\n";
                $pesan .= "Yeay, *request booking* ruangan Anda telah berhasil masuk ke sistem kami! 🙌\n\n";
                $pesan .= "Berikut adalah rangkuman jadwal Anda:\n";
                $pesan .= "📌 *Ruangan:* {$namaRuangan}\n";
                $pesan .= "🗓️ *Tanggal:* " . \Carbon\Carbon::parse($start)->translatedFormat('d F Y') . "\n";
                $pesan .= "🕒 *Waktu:* " . $start->format('H:i') . " WIB - " . $end->format('H:i') . " WIB\n";
                $pesan .= "🎯 *Keperluan:* {$request->keperluan}\n\n";
                $pesan .= "🏷️ *KODE BOOKING ANDA: {$kode_booking_baru}*\n\n";
                $pesan .= "*Apa langkah selanjutnya?* 🤔\n";
                $pesan .= "Tim kami sedang memastikan ruangan tersebut *ready* dan tidak bentrok dengan jadwal lain. Mohon ditunggu ya, kami akan segera menghubungi Anda kembali untuk status persetujuannya! 🚀\n\n";
                $pesan .= "Terima kasih,\n";
                $pesan .= "*Tim Admin Layanan Ruangan*";

                // Buat link wa.me
                $link_wa = "https://api.whatsapp.com/send?phone=" . $request->no_hp . "&text=" . rawurlencode($pesan);

                // 7. Kembalikan Response (+ Selipkan link_wa agar ditangkap Javascript)
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Booking berhasil diajukan!',
                    'link_wa' => $link_wa // <-- Baru
                ]);
            } catch (\Exception $e) {
                return response()->json(['errors' => ['sistem' => [$e->getMessage()]]], 422);
            }
        }
    }

    // Fitur Monitoring Real-Time Server-Side DataTables
    public function monitoring(Request $request)
    {
        if ($request->ajax()) {
            $data = \App\Models\Booking::with(['ruangan', 'user'])->orderBy('waktu_mulai', 'desc');

            return \Yajra\DataTables\Facades\DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('total_bayar', function ($row) {
                    return 'Rp ' . number_format($row->total_bayar, 0, ',', '.');
                })
                ->editColumn('waktu_mulai', function ($row) {
                    return \Carbon\Carbon::parse($row->waktu_mulai)->format('d M Y, H:i');
                })
                ->addColumn('status_badge', function ($row) {
                    $waktuMulai = \Carbon\Carbon::parse($row->getRawOriginal('waktu_mulai'))->timezone('Asia/Jakarta');
                    $sekarang = \Carbon\Carbon::now('Asia/Jakarta');

                    if ($row->status_booking === 'Pending' && $waktuMulai->lessThan($sekarang)) {
                        return '<span class="badge bg-danger shadow-sm"><i class="fas fa-times-circle"></i> Kedaluwarsa</span>';
                    }

                    if ($row->status_booking === 'Pending') {
                        return '<span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>';
                    }
                    if ($row->status_booking === 'Dikonfirmasi') {
                        return '<span class="badge bg-success"><i class="fas fa-check"></i> Dikonfirmasi</span>';
                    }
                    if ($row->status_booking === 'Selesai') {
                        return '<span class="badge bg-primary"><i class="fas fa-flag-checkered"></i> Selesai</span>';
                    }

                    return '<span class="badge bg-secondary">' . $row->status_booking . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<div class="d-flex justify-content-center gap-2">';

                    $btn .= '<button type="button" class="btn-action btn-action-info" onclick="detailBooking(' . $row->id . ')" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                             </button>';

                    $waktuMulai = \Carbon\Carbon::parse($row->getRawOriginal('waktu_mulai'))->timezone('Asia/Jakarta');
                    $sekarang = \Carbon\Carbon::now('Asia/Jakarta');

                    if ($row->status_booking === 'Pending' && $waktuMulai->greaterThanOrEqualTo($sekarang)) {
                        $btn .= '<button type="button" class="btn-action btn-action-success" onclick="konfirmasiBooking(' . $row->id . ')" title="Setujui Booking">
                                    <i class="fas fa-check"></i>
                                 </button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'aksi'])
                ->make(true);
        }

        return view('booking.monitoring');
    }

    public function show($id)
    {
        $booking = Booking::with(['ruangan', 'user'])->findOrFail($id);
        $booking->waktu_mulai_format = \Carbon\Carbon::parse($booking->waktu_mulai)->format('d F Y, H:i');
        $booking->waktu_selesai_format = \Carbon\Carbon::parse($booking->waktu_selesai)->format('d F Y, H:i');
        $booking->harga_format = 'Rp ' . number_format($booking->total_bayar, 0, ',', '.');
        return response()->json($booking);
    }

    public function cetakPDF($id)
    {
        $booking = Booking::with(['ruangan', 'user'])->findOrFail($id);
        if ($booking->status_booking != 'Dikonfirmasi') {
            return abort(403, 'Nota belum tersedia. Menunggu konfirmasi Admin.');
        }
        $booking->tanggal_cetak = \Carbon\Carbon::now()->format('d F Y, H:i');
        $pdf = Pdf::loadView('booking.nota', compact('booking'));
        return $pdf->stream('Invoice-Booking-' . $booking->kode_booking . '.pdf');
    }

    // Fungsi Konfirmasi oleh Admin (+ Upload Bukti)
    public function confirm(Request $request, $id)
    {
        try {
            // 1. Validasi File (wajib gambar, max 2MB)
            $request->validate([
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $booking = Booking::with(['user', 'ruangan'])->findOrFail($id);

            // 2. Proses Upload File Bukti Pembayaran
            $pathBukti = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $namaFile = time() . '_bukti_' . $booking->kode_booking . '.' . $file->extension();

                // Simpan ke folder public/uploads/bukti_pembayaran
                $file->move(public_path('uploads/bukti_pembayaran'), $namaFile);
                $pathBukti = 'uploads/bukti_pembayaran/' . $namaFile;
            }

            // 3. Ubah status jadi Dikonfirmasi dan simpan path foto
            $booking->update([
                'status_booking'   => 'Dikonfirmasi',
                'bukti_pembayaran' => $pathBukti
            ]);

            // 4. Pastikan nomor HP berawalan 62
            $noHp = $booking->no_hp;
            if (str_starts_with($noHp, '0')) {
                $noHp = '62' . substr($noHp, 1);
            }

            // 5. RAKIT PESAN WA BESERTA EMOJI LANGSUNG DI PHP
            $pesan = "Halo *{$booking->user->name}*! ✨\n\n";
            $pesan .= "Kabar gembira! Pembayaran Anda telah kami terima dan peminjaman ruangan *DISETUJUI*.\n\n";
            $pesan .= "Berikut rincian jadwal Anda:\n";
            $pesan .= "📌 *Ruangan:* {$booking->ruangan->nama_ruangan}\n";
            $pesan .= "📅 *Tanggal:* " . \Carbon\Carbon::parse($booking->waktu_mulai)->translatedFormat('d F Y') . "\n";
            $pesan .= "⏰ *Waktu:* " . \Carbon\Carbon::parse($booking->waktu_mulai)->format('H:i') . " WIB\n";
            $pesan .= "🏷️ *KODE BOOKING ANDA: {$booking->kode_booking}*\n\n";
            $pesan .= "Silakan datang sesuai jadwal. Kami tunggu kedatangannya!\n\n";
            $pesan .= "*Tim Admin Layanan Ruangan*";

            // 6. Langsung buatkan Link WA siap pakai
            $link_wa = "https://api.whatsapp.com/send?phone=" . $noHp . "&text=" . rawurlencode($pesan);

            // 7. Kembalikan balasan sukses + link WA ke Javascript
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil disetujui & Bukti tersimpan!',
                'link_wa' => $link_wa
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyetujui: ' . $e->getMessage()], 500);
        }
    }

    public function exportData(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $format = $request->format;
        $fileName = 'Laporan_Booking_' . $start_date . '_sd_' . $end_date;

        if ($format === 'excel') {
            return Excel::download(new BookingExport($start_date, $end_date), $fileName . '.xlsx');
        }

        if ($format === 'pdf') {
            $bookings = Booking::with(['user', 'ruangan'])
                ->whereBetween('waktu_mulai', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
                ->latest()
                ->get();

            $pdf = Pdf::loadView('booking.pdf', compact('bookings', 'start_date', 'end_date'))
                ->setPaper('A4', 'landscape');

            return $pdf->download($fileName . '.pdf');
        }
    }

    // ==========================================
    // FITUR CHECK-IN RESEPSIONIS (FULL AJAX)
    // ==========================================
    public function checkinIndex(Request $request)
    {
        if ($request->ajax()) {
            $hariIni = \Carbon\Carbon::now('Asia/Jakarta')->toDateString();
            $data = \App\Models\Booking::with(['ruangan', 'user'])
                ->where('status_booking', 'Selesai')
                ->whereDate('updated_at', $hariIni)
                ->orderBy('updated_at', 'desc');

            return \Yajra\DataTables\Facades\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('waktu_checkin', function ($row) {
                    return '<span class="fw-bold text-dark">' . \Carbon\Carbon::parse($row->updated_at)->timezone('Asia/Jakarta')->format('H:i') . ' WIB</span>';
                })
                ->addColumn('kode_booking_badge', function ($row) {
                    return '<span class="badge bg-light text-primary border border-primary-subtle px-2 py-1">' . $row->kode_booking . '</span>';
                })
                ->addColumn('peminjam', function ($row) {
                    return $row->user->name ?? 'Admin';
                })
                ->addColumn('ruangan_nama', function ($row) {
                    return $row->ruangan->nama_ruangan ?? '-';
                })
                ->rawColumns(['waktu_checkin', 'kode_booking_badge'])
                ->make(true);
        }

        return view('booking.checkin');
    }

    public function searchBooking(Request $request)
    {
        if ($request->ajax()) {
            $booking = Booking::with(['user', 'ruangan'])->where('kode_booking', $request->kode)->first();

            if ($booking) {
                $booking->waktu_mulai_format = \Carbon\Carbon::parse($booking->waktu_mulai)->format('d M Y, H:i');
                $booking->waktu_selesai_format = \Carbon\Carbon::parse($booking->waktu_selesai)->format('d M Y, H:i');

                return response()->json(['status' => 'success', 'data' => $booking]);
            }

            return response()->json(['status' => 'error', 'message' => 'Kode booking tidak ditemukan dalam sistem.']);
        }
    }

    public function checkinProses(Request $request)
    {
        try {
            $booking = \App\Models\Booking::where('kode_booking', $request->kode_booking)->first();

            if (!$booking) {
                return response()->json(['status' => 'error', 'message' => 'Kode booking tidak ditemukan.']);
            }
            if ($booking->status_booking === 'Selesai') {
                return response()->json(['status' => 'error', 'message' => 'Ruangan ini sudah melakukan Check-In sebelumnya.']);
            }
            if ($booking->status_booking === 'Pending') {
                return response()->json(['status' => 'error', 'message' => 'Booking masih Pending dan belum dikonfirmasi.']);
            }

            $booking->update([
                'status_booking' => 'Selesai'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-in Berhasil! Ruangan ' . ($booking->ruangan->nama_ruangan ?? '') . ' siap digunakan.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        $query = \App\Models\Booking::with(['ruangan', 'user'])->orderBy('waktu_mulai', 'desc');

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($start_date) {
            $query->whereDate('waktu_mulai', '>=', $start_date);
        }
        if ($end_date) {
            $query->whereDate('waktu_mulai', '<=', $end_date);
        }

        $bookings = $query->get();

        if ($request->format === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\BookingExport($bookings),
                'Laporan_Booking_Modern_' . date('Y-m-d_His') . '.xlsx'
            );
        }

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('booking.pdf', compact('bookings', 'start_date', 'end_date'))
                ->setPaper('A4', 'landscape');

            return $pdf->stream('Laporan_Booking_Modern_' . date('Y-m-d_His') . '.pdf');
        }

        return back();
    }

    public function sendReminderManual(Request $request)
    {
        try {
            // Memanggil file Command (robot) yang sudah kita buat tadi secara manual
            Artisan::call('booking:send-reminder');

            // Mengambil pesan hasil kerja robotnya (misal: "Selesai! Telah mengirim pengingat...")
            $hasil = Artisan::output();

            return back()->with('success', 'Reminder berhasil dijalankan! Output: ' . $hasil);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menjalankan reminder: ' . $e->getMessage());
        }
    }

    public function remindSingle($id)
    {
        $booking = Booking::with(['ruangan', 'user'])->findOrFail($id);

        // 1. Pastikan nomor HP diawali 62 (Seragamkan dengan fitur ACC)
        $noHp = $booking->no_hp;
        if (str_starts_with($noHp, '0')) {
            $noHp = '62' . substr($noHp, 1);
        }

        // 2. RAKIT PESAN (Pakai Kode Unicode agar 1000% kebal kotak hitam)
        // \u{1F514} = 🔔 , \u{1F4CC} = 📌 , \u{1F4C5} = 📅 , \u{23F0} = ⏰ , \u{2728} = ✨
        $pesan = "\u{1F514} *PENGINGAT JADWAL RUANGAN* \u{1F514}\n\n";
        $pesan .= "Halo *{$booking->user->name}*,\n";
        $pesan .= "Jadwal booking ruangan Anda sudah semakin dekat.\n\n";
        $pesan .= "\u{1F4CC} *Ruangan:* {$booking->ruangan->nama_ruangan}\n";
        $pesan .= "\u{1F4C5} *Tanggal:* " . \Carbon\Carbon::parse($booking->waktu_mulai)->translatedFormat('d F Y') . "\n";
        $pesan .= "\u{23F0} *Waktu:* " . \Carbon\Carbon::parse($booking->waktu_mulai)->format('H:i') . " WIB\n\n";
        $pesan .= "Sampai jumpa di lokasi! \u{2728}";

        // 3. Gunakan rumus sukses: api.whatsapp.com + rawurlencode
        $url = "https://api.whatsapp.com/send?phone=" . $noHp . "&text=" . rawurlencode($pesan);

        return redirect()->away($url);
    }
}
