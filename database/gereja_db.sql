-- Database Sistem Gereja
-- Dijalankan di MySQL XAMPP

-- Buat database
CREATE DATABASE IF NOT EXISTS gereja_db;
USE gereja_db;

-- Tabel Admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('super_admin', 'admin', 'operator') DEFAULT 'admin',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Jemaat
CREATE TABLE jemaat (
    id VARCHAR(20) PRIMARY KEY,
    nij VARCHAR(100) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    nama_panggilan VARCHAR(50),
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT,
    rt_rw VARCHAR(20),
    kelurahan VARCHAR(100),
    kecamatan VARCHAR(100),
    kota VARCHAR(100),
    provinsi VARCHAR(100),
    kode_pos VARCHAR(10),
    no_telepon VARCHAR(15),
    email VARCHAR(100),
    status_pernikahan ENUM('belum_menikah', 'menikah', 'cerai', 'janda_duda') DEFAULT 'belum_menikah',
    tanggal_menikah DATE,
    nama_pasangan VARCHAR(100),
    pekerjaan VARCHAR(100),
    pendidikan VARCHAR(100),
    tanggal_baptis DATE,
    tanggal_sidi DATE,
    status_jemaat ENUM('aktif', 'nonaktif', 'meninggal', 'pindah') DEFAULT 'aktif',
    golongan_darah ENUM('A', 'B', 'AB', 'O'),
    foto VARCHAR(255),
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Keluarga
CREATE TABLE keluarga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kepala_keluarga_id VARCHAR(20),
    nama_keluarga VARCHAR(100),
    alamat TEXT,
    no_telepon VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kepala_keluarga_id) REFERENCES jemaat(id) ON DELETE SET NULL
);

-- Tabel Anggota Keluarga
CREATE TABLE anggota_keluarga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keluarga_id INT,
    jemaat_id VARCHAR(20),
    hubungan ENUM('kepala_keluarga', 'istri', 'anak', 'orang_tua', 'saudara') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (keluarga_id) REFERENCES keluarga(id) ON DELETE CASCADE,
    FOREIGN KEY (jemaat_id) REFERENCES jemaat(id) ON DELETE CASCADE
);

-- Tabel Jadwal Ibadah
CREATE TABLE jadwal_ibadah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    tanggal DATE NOT NULL,
    waktu_mulai TIME NOT NULL,
    waktu_selesai TIME,
    jenis_ibadah ENUM('ibadah_minggu', 'ibadah_doa', 'ibadah_pemuda', 'ibadah_anak', 'ibadah_khusus') NOT NULL,
    tempat VARCHAR(200),
    pemimpin_ibadah VARCHAR(100),
    khotbah VARCHAR(200),
    status ENUM('akan_datang', 'sedang_berlangsung', 'selesai', 'dibatalkan') DEFAULT 'akan_datang',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Keuangan
CREATE TABLE keuangan (
    id VARCHAR(20) PRIMARY KEY,
    tanggal DATE NOT NULL,
    jenis ENUM('pemasukan', 'pengeluaran') NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    sub_kategori VARCHAR(100),
    deskripsi TEXT,
    jumlah DECIMAL(15,2) NOT NULL,
    metode_pembayaran ENUM('tunai', 'transfer', 'cek', 'lainnya') DEFAULT 'tunai',
    referensi VARCHAR(100),
    status ENUM('pending', 'diterima', 'ditolak') DEFAULT 'diterima',
    approved_by INT,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (approved_by) REFERENCES admin(id) ON DELETE SET NULL
);

-- Tabel Persembahan
CREATE TABLE persembahan (
    id VARCHAR(20) PRIMARY KEY,
    tanggal DATE NOT NULL,
    jenis_persembahan ENUM('persembahan_minggu', 'persembahan_khusus', 'persembahan_online', 'lainnya') NOT NULL,
    nama_pemberi VARCHAR(100),
    jumlah DECIMAL(15,2) NOT NULL,
    metode_pembayaran ENUM('tunai', 'transfer', 'cek', 'online') DEFAULT 'tunai',
    referensi VARCHAR(100),
    status ENUM('pending', 'diterima', 'ditolak') DEFAULT 'diterima',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Warta
CREATE TABLE warta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    konten TEXT NOT NULL,
    ringkasan TEXT,
    gambar VARCHAR(255),
    kategori ENUM('berita', 'pengumuman', 'acara', 'renungan', 'lainnya') DEFAULT 'berita',
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    tanggal_publish DATE,
    penulis VARCHAR(100),
    tags VARCHAR(255),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Galeri
CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    nama_file VARCHAR(255) NOT NULL,
    path_file VARCHAR(500) NOT NULL,
    ukuran_file INT,
    tipe_file VARCHAR(50),
    kategori VARCHAR(100),
    tanggal_upload DATE,
    uploaded_by INT,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES admin(id) ON DELETE SET NULL
);

-- Tabel Renungan
CREATE TABLE renungan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    ayat_alkitab VARCHAR(200),
    konten TEXT NOT NULL,
    penulis VARCHAR(100),
    kategori VARCHAR(100),
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    tanggal_publish DATE,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Kegiatan Kerohanian
CREATE TABLE kegiatan_kerohanian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kegiatan VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    waktu_mulai TIME,
    waktu_selesai TIME,
    tempat VARCHAR(200),
    jenis_kegiatan ENUM('pelatihan', 'retreat', 'seminar', 'workshop', 'ibadah_khusus', 'lainnya') NOT NULL,
    target_peserta VARCHAR(100),
    kuota_peserta INT,
    biaya DECIMAL(10,2),
    status ENUM('direncanakan', 'pendaftaran', 'berlangsung', 'selesai', 'dibatalkan') DEFAULT 'direncanakan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Peserta Kegiatan
CREATE TABLE peserta_kegiatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kegiatan_id INT,
    jemaat_id VARCHAR(20),
    status_pendaftaran ENUM('terdaftar', 'hadir', 'tidak_hadir', 'dibatalkan') DEFAULT 'terdaftar',
    tanggal_daftar DATE,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kegiatan_id) REFERENCES kegiatan_kerohanian(id) ON DELETE CASCADE,
    FOREIGN KEY (jemaat_id) REFERENCES jemaat(id) ON DELETE CASCADE
);

-- Tabel Pengaturan Sistem
CREATE TABLE pengaturan_sistem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pengaturan VARCHAR(100) UNIQUE NOT NULL,
    nilai TEXT,
    deskripsi TEXT,
    kategori VARCHAR(100),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert data awal
-- Admin default
INSERT INTO admin (username, password, nama_lengkap, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@gereja.com', 'super_admin');

-- Pengaturan sistem
INSERT INTO pengaturan_sistem (nama_pengaturan, nilai, deskripsi, kategori) VALUES
('nama_gereja', 'Gereja Kristen Indonesia', 'Nama resmi gereja', 'umum'),
('alamat_gereja', 'Jl. Gereja No. 123, Jakarta', 'Alamat lengkap gereja', 'umum'),
('telepon_gereja', '+62 21 1234 5678', 'Nomor telepon gereja', 'kontak'),
('email_gereja', 'info@gereja.com', 'Email resmi gereja', 'kontak'),
('jam_ibadah_minggu', '09:00', 'Jam ibadah minggu', 'ibadah'),
('jam_ibadah_doa', '18:00', 'Jam ibadah doa', 'ibadah'),
('mata_uang', 'IDR', 'Mata uang yang digunakan', 'keuangan'),
('logo_gereja', 'logo.png', 'File logo gereja', 'tampilan'),
('favicon', 'favicon.ico', 'File favicon website', 'tampilan');

-- Index untuk optimasi query
CREATE INDEX idx_nij ON jemaat(nij);
CREATE INDEX idx_jemaat_nama ON jemaat(nama_lengkap);
CREATE INDEX idx_jemaat_status ON jemaat(status_jemaat);
CREATE INDEX idx_jadwal_tanggal ON jadwal_ibadah(tanggal);
CREATE INDEX idx_keuangan_tanggal ON keuangan(tanggal);
CREATE INDEX idx_keuangan_jenis ON keuangan(jenis);
CREATE INDEX idx_persembahan_tanggal ON persembahan(tanggal);
CREATE INDEX idx_warta_status ON warta(status);
CREATE INDEX idx_warta_kategori ON warta(kategori);
CREATE INDEX idx_renungan_status ON renungan(status);
CREATE INDEX idx_kegiatan_status ON kegiatan_kerohanian(status);
