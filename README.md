# Sistem Peminjaman Alat Laboratorium

Aplikasi web untuk mengelola peminjaman alat laboratorium menggunakan Laravel dengan PDO.

## Informasi Proyek

- **Mata Kuliah**: Praktikum Pemrograman Web (INF11197)
- **Tipe Soal**: B - Sistem Peminjaman Alat
- **Nama**: [Nama Mahasiswa]
- **NIM**: 2401020146
- **Semester**: V

## Fitur Utama

- CRUD (Create, Read, Update, Delete) peminjaman alat
- Validasi server-side dengan sanitasi input
- Pencarian berdasarkan kode peminjaman, nama peminjam, dan nomor identitas
- Filter berdasarkan jenis alat
- Prepared statement untuk keamanan SQL injection
- Error handling dengan logging
- Pengecekan duplikat untuk kode peminjaman dan nomor identitas

## Teknologi

- Laravel 13.x
- PHP 8.3
- MySQL/MariaDB
- PDO (PHP Data Objects)
- Blade Template Engine

## Struktur Database

### Tabel: alat
- `id` - Primary Key
- `nama_alat` - VARCHAR(100), UNIQUE

### Tabel: peminjaman_alat
- `id` - Primary Key
- `kode_peminjaman` - CHAR(10), UNIQUE
- `nama_peminjam` - VARCHAR(100)
- `nomor_identitas` - VARCHAR(30), UNIQUE
- `jumlah_alat` - TINYINT (1-10)
- `alat_id` - Foreign Key → alat.id
- `created_at`, `updated_at` - Timestamps

**Relasi**: alat (1) → peminjaman_alat (many)  
**Constraint**: ON UPDATE CASCADE, ON DELETE RESTRICT

## Instalasi

1. Clone repository
```bash
git clone [URL_REPOSITORY]
cd peminjaman-alat
```

2. Install dependencies
```bash
composer install
```

3. Copy file environment
```bash
cp .env.example .env
```

4. Konfigurasi database di `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=peminjaman_alat_2401020146
DB_USERNAME=root
DB_PASSWORD=
```

5. Generate application key
```bash
php artisan key:generate
```

6. Import database
```bash
mysql -u root -p peminjaman_alat_2401020146 < peminjaman_alat.sql
```

Atau jalankan migration dan seeder:
```bash
php artisan migrate
php artisan db:seed --class=AlatSeeder
```

7. Jalankan server
```bash
php artisan serve
```

Akses aplikasi di `http://127.0.0.1:8000/peminjaman`

## Route List

| Method | URI | Action | Deskripsi |
|--------|-----|--------|-----------|
| GET | /peminjaman | index | Tampil daftar, form tambah, search, filter |
| POST | /peminjaman | store | Simpan data baru |
| GET | /peminjaman/{id}/edit | edit | Form edit |
| PUT | /peminjaman/{id} | update | Update data |
| DELETE | /peminjaman/{id} | destroy | Hapus data |

## Validasi

- **Kode Peminjaman**: Tepat 10 karakter alfanumerik, unik
- **Nama Peminjam**: 3-100 karakter
- **Nomor Identitas**: 5-30 karakter, unik
- **Jumlah Alat**: Integer 1-10
- **Alat**: Harus tersedia di tabel alat

## Keamanan

- Prepared statement untuk semua query dengan user input
- CSRF token pada semua form
- Sanitasi input dengan `trim()` dan `strip_tags()`
- Error handling dengan `try-catch` dan `report()`
- Password database tidak masuk repository (.gitignore)

## Testing

10 skenario pengujian telah dilakukan:
1. Read - Menampilkan 3 data awal
2. Create valid - Data tersimpan dengan feedback
3. Create invalid - Validasi error dan old input
4. Duplikat - Penolakan kode/nomor identitas sama
5. Update - Form prefilled dan data tersimpan
6. Update konflik - Duplikat ditolak
7. Delete - Data terhapus
8. Pencarian - 3 kolom (kode, nama, nomor)
9. Filter - Berdasarkan alat
10. Error handling - Pesan aman saat database error

## Helper Function

`statusJumlah($jumlah)` - Menampilkan status "Banyak" jika >= 3, "Sedikit" jika < 3

## Lisensi

Proyek ini dibuat untuk keperluan akademik Praktikum Pemrograman Web.
