-- Database: peminjaman_alat_2401020146
-- Tipe B: Sistem Peminjaman Alat Laboratorium

-- Table: alat
CREATE TABLE `alat` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_alat` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alat_nama_alat_unique` (`nama_alat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: peminjaman_alat
CREATE TABLE `peminjaman_alat` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_peminjaman` CHAR(10) NOT NULL,
  `nama_peminjam` VARCHAR(100) NOT NULL,
  `nomor_identitas` VARCHAR(30) NOT NULL,
  `jumlah_alat` TINYINT UNSIGNED NOT NULL,
  `alat_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `peminjaman_alat_kode_peminjaman_unique` (`kode_peminjaman`),
  UNIQUE KEY `peminjaman_alat_nomor_identitas_unique` (`nomor_identitas`),
  KEY `peminjaman_alat_alat_id_foreign` (`alat_id`),
  CONSTRAINT `peminjaman_alat_alat_id_foreign` FOREIGN KEY (`alat_id`) REFERENCES `alat` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `chk_jumlah_alat` CHECK ((`jumlah_alat` BETWEEN 1 AND 10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert data awal: alat
INSERT INTO `alat` (`id`, `nama_alat`) VALUES
(1, 'Kamera'),
(2, 'Proyektor');

-- Insert data awal: peminjaman_alat
INSERT INTO `peminjaman_alat` (`id`, `kode_peminjaman`, `nama_peminjam`, `nomor_identitas`, `jumlah_alat`, `alat_id`, `created_at`, `updated_at`) VALUES
(1, 'PM00000001', 'Rina Kurnia', 'MHS-2201001', 2, 1, NOW(), NOW()),
(2, 'PM00000002', 'Dedi Pranata', 'MHS-2201002', 1, 2, NOW(), NOW()),
(3, 'PM00000003', 'Mira Ananda', 'MHS-2201003', 3, 1, NOW(), NOW());
