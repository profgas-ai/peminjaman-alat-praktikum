USE peminjaman_alat_2401020146;

-- Tabel Master
CREATE TABLE alat (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_alat VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Detail
CREATE TABLE peminjaman_alat (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_peminjaman CHAR(10) NOT NULL UNIQUE,
    nama_peminjam VARCHAR(100) NOT NULL,
    nomor_identitas VARCHAR(30) NOT NULL UNIQUE,
    jumlah_alat TINYINT UNSIGNED NOT NULL CHECK (jumlah_alat BETWEEN 1 AND 10),
    alat_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (alat_id) REFERENCES alat(id) 
        ON UPDATE CASCADE 
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Awal Master
INSERT INTO alat (nama_alat) VALUES 
('Kamera'),
('Proyektor'),
('Laptop');

-- Data Awal Detail (3 wajib)
INSERT INTO peminjaman_alat (kode_peminjaman, nama_peminjam, nomor_identitas, jumlah_alat, alat_id) VALUES
('PM00000001', 'Rina Kurnia', 'MHS-2201001', 2, 1),
('PM00000002', 'Dedi Pranata', 'MHS-2201002', 1, 2),
('PM00000003', 'Mira Ananda', 'MHS-2201003', 3, 1);