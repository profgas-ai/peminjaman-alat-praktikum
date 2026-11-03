<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;

class PeminjamanController extends Controller
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_DATABASE'),
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            report($e);
            abort(500, 'Koneksi database gagal');
        }
    }

    public function index(Request $request)
    {
        try {
            // Get all alat for filter dropdown
            $stmt = $this->pdo->prepare("SELECT * FROM alat ORDER BY nama_alat");
            $stmt->execute();
            $alats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Base query
            $sql = "SELECT p.*, a.nama_alat 
                    FROM peminjaman_alat p 
                    JOIN alat a ON p.alat_id = a.id 
                    WHERE 1=1";
            $params = [];

            // Search filter
            if ($request->has('search') && $request->search != '') {
                $search = '%' . $request->search . '%';
                $sql .= " AND (p.kode_peminjaman LIKE ? OR p.nama_peminjam LIKE ? OR p.nomor_identitas LIKE ?)";
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }

            // Alat filter
            if ($request->has('alat_id') && $request->alat_id != '') {
                $sql .= " AND p.alat_id = ?";
                $params[] = $request->alat_id;
            }

            $sql .= " ORDER BY p.created_at DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $peminjamans = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return view('peminjaman.index', compact('peminjamans', 'alats'));
        } catch (PDOException $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    public function store(Request $request)
    {
        // Sanitasi input
        $kode = trim(strip_tags($request->kode_peminjaman));
        $nama = trim(strip_tags($request->nama_peminjam));
        $nomor = trim(strip_tags($request->nomor_identitas));
        $jumlah = $request->jumlah_alat;
        $alatId = $request->alat_id;

        // Validasi
        $errors = [];

        if (strlen($kode) != 10 || !ctype_alnum($kode)) {
            $errors['kode_peminjaman'] = 'Kode peminjaman harus tepat 10 karakter alfanumerik';
        }

        if (strlen($nama) < 3 || strlen($nama) > 100) {
            $errors['nama_peminjam'] = 'Nama peminjam harus 3-100 karakter';
        }

        if (strlen($nomor) < 5 || strlen($nomor) > 30) {
            $errors['nomor_identitas'] = 'Nomor identitas harus 5-30 karakter';
        }

        if (!is_numeric($jumlah) || $jumlah < 1 || $jumlah > 10) {
            $errors['jumlah_alat'] = 'Jumlah alat harus antara 1-10';
        }

        if (empty($alatId)) {
            $errors['alat_id'] = 'Alat wajib dipilih';
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        try {
            // Check duplicate kode_peminjaman
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM peminjaman_alat WHERE kode_peminjaman = ?");
            $stmt->execute([$kode]);
            if ($stmt->fetchColumn() > 0) {
                return back()->withErrors(['kode_peminjaman' => 'Kode peminjaman sudah digunakan'])->withInput();
            }

            // Check duplicate nomor_identitas
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM peminjaman_alat WHERE nomor_identitas = ?");
            $stmt->execute([$nomor]);
            if ($stmt->fetchColumn() > 0) {
                return back()->withErrors(['nomor_identitas' => 'Nomor identitas sudah digunakan'])->withInput();
            }

            // Check alat exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM alat WHERE id = ?");
            $stmt->execute([$alatId]);
            if ($stmt->fetchColumn() == 0) {
                return back()->withErrors(['alat_id' => 'Alat tidak ditemukan'])->withInput();
            }

            // Insert data
            $stmt = $this->pdo->prepare("
                INSERT INTO peminjaman_alat (kode_peminjaman, nama_peminjam, nomor_identitas, jumlah_alat, alat_id, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute([$kode, $nama, $nomor, $jumlah, $alatId]);

            return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil ditambahkan');
        } catch (PDOException $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            // Get peminjaman data
            $stmt = $this->pdo->prepare("SELECT * FROM peminjaman_alat WHERE id = ?");
            $stmt->execute([$id]);
            $peminjaman = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$peminjaman) {
                return redirect()->route('peminjaman.index')->with('error', 'Data tidak ditemukan');
            }

            // Get all alat
            $stmt = $this->pdo->prepare("SELECT * FROM alat ORDER BY nama_alat");
            $stmt->execute();
            $alats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return view('peminjaman.edit', compact('peminjaman', 'alats'));
        } catch (PDOException $e) {
            report($e);
            return redirect()->route('peminjaman.index')->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    public function update(Request $request, $id)
    {
        // Sanitasi input
        $kode = trim(strip_tags($request->kode_peminjaman));
        $nama = trim(strip_tags($request->nama_peminjam));
        $nomor = trim(strip_tags($request->nomor_identitas));
        $jumlah = $request->jumlah_alat;
        $alatId = $request->alat_id;

        // Validasi
        $errors = [];

        if (strlen($kode) != 10 || !ctype_alnum($kode)) {
            $errors['kode_peminjaman'] = 'Kode peminjaman harus tepat 10 karakter alfanumerik';
        }

        if (strlen($nama) < 3 || strlen($nama) > 100) {
            $errors['nama_peminjam'] = 'Nama peminjam harus 3-100 karakter';
        }

        if (strlen($nomor) < 5 || strlen($nomor) > 30) {
            $errors['nomor_identitas'] = 'Nomor identitas harus 5-30 karakter';
        }

        if (!is_numeric($jumlah) || $jumlah < 1 || $jumlah > 10) {
            $errors['jumlah_alat'] = 'Jumlah alat harus antara 1-10';
        }

        if (empty($alatId)) {
            $errors['alat_id'] = 'Alat wajib dipilih';
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        try {
            // Check duplicate kode_peminjaman (exclude current id)
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM peminjaman_alat WHERE kode_peminjaman = ? AND id != ?");
            $stmt->execute([$kode, $id]);
            if ($stmt->fetchColumn() > 0) {
                return back()->withErrors(['kode_peminjaman' => 'Kode peminjaman sudah digunakan'])->withInput();
            }

            // Check duplicate nomor_identitas (exclude current id)
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM peminjaman_alat WHERE nomor_identitas = ? AND id != ?");
            $stmt->execute([$nomor, $id]);
            if ($stmt->fetchColumn() > 0) {
                return back()->withErrors(['nomor_identitas' => 'Nomor identitas sudah digunakan'])->withInput();
            }

            // Check alat exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM alat WHERE id = ?");
            $stmt->execute([$alatId]);
            if ($stmt->fetchColumn() == 0) {
                return back()->withErrors(['alat_id' => 'Alat tidak ditemukan'])->withInput();
            }

            // Update data
            $stmt = $this->pdo->prepare("
                UPDATE peminjaman_alat 
                SET kode_peminjaman = ?, nama_peminjam = ?, nomor_identitas = ?, jumlah_alat = ?, alat_id = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$kode, $nama, $nomor, $jumlah, $alatId, $id]);

            return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil diupdate');
        } catch (PDOException $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM peminjaman_alat WHERE id = ?");
            $stmt->execute([$id]);

            return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus');
        } catch (PDOException $e) {
            report($e);
            return redirect()->route('peminjaman.index')->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
}
