-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 09:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appsbeem_gereja`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('super_admin','admin','operator') DEFAULT 'admin',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'sadmin', '$2y$10$IusguSHbQ988HT/9eOKkfeFAHooTzKLzoRBmXGPTrDo3fZDYCJhbC', 'Doni Abiy', 'admin@gereja.com', 'super_admin', 'aktif', '2025-08-25 00:26:50', '2025-09-02 06:47:03'),
(6, 'admin', '$2y$10$wRlCmmPZ57Uwpdoy/BlpAemMUVEQiXVcGr6n.mxitEDdSp7j08rL2', 'Admin Gereja', '-', 'admin', 'aktif', '2025-08-25 01:34:03', '2025-08-25 01:34:25'),
(7, 'operator', '$2y$10$eCKnWUx8M368Rp/ooC846.b49Q1xB8t/r4ES0g/CqAsQ/9Btjelou', 'Operator Gereja', '--', 'operator', 'aktif', '2025-08-25 01:34:03', '2025-08-25 01:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_keluarga`
--

CREATE TABLE `anggota_keluarga` (
  `id` int(11) NOT NULL,
  `keluarga_id` int(11) DEFAULT NULL,
  `jemaat_id` varchar(20) DEFAULT NULL,
  `hubungan` enum('kepala_keluarga','istri','anak','orang_tua','saudara') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(500) NOT NULL,
  `ukuran_file` int(11) DEFAULT NULL,
  `tipe_file` varchar(50) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `tanggal_upload` date DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `tema` text NOT NULL,
  `jam` time NOT NULL,
  `pengkotbah` text NOT NULL,
  `bacaan` text NOT NULL,
  `imam` text NOT NULL,
  `baca_warta` text NOT NULL,
  `sahadat` text NOT NULL,
  `persembahan` text NOT NULL,
  `musik` text NOT NULL,
  `pnj` text NOT NULL,
  `lcd` text NOT NULL,
  `pengisi` text NOT NULL,
  `dekorasi` text NOT NULL,
  `lain` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_ibadah`
--

CREATE TABLE `jadwal_ibadah` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `jenis_ibadah` enum('ibadah_minggu','ibadah_doa','ibadah_pemuda','ibadah_anak','ibadah_khusus') NOT NULL,
  `tempat` varchar(200) DEFAULT NULL,
  `pemimpin_ibadah` varchar(100) DEFAULT NULL,
  `khotbah` varchar(200) DEFAULT NULL,
  `status` enum('akan_datang','sedang_berlangsung','selesai','dibatalkan') DEFAULT 'akan_datang',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_ibadah`
--

INSERT INTO `jadwal_ibadah` (`id`, `judul`, `deskripsi`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `jenis_ibadah`, `tempat`, `pemimpin_ibadah`, `khotbah`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'Ibadah Minggu Pagi', 'Ibadah di setiap hari Minggu Pagi di Gereja', '0000-00-00', '06:00:00', '08:00:00', 'ibadah_minggu', 'Gereja', 'Pendeta/Majelis', '-', 'akan_datang', '-', '2025-08-25 00:56:18', '2025-08-25 06:30:49'),
(2, 'Ibadah Minggu Siang', 'Ibadah di setiap hari Minggu Siang di Gereja', '0000-00-00', '09:00:00', '11:00:00', 'ibadah_minggu', 'Gereja', 'Pendeta/Majelis', '-', 'akan_datang', '-', '2025-08-25 00:56:18', '2025-08-25 00:56:18'),
(3, 'Ibadah Minggu Sore', 'Ibadah di setiap hari Minggu Sore di Gereja', '0000-00-00', '17:00:00', '19:00:00', 'ibadah_minggu', 'Gereja', 'Pendeta/Majelis', '-', 'akan_datang', '-', '2025-08-25 00:57:17', '2025-08-25 06:30:57'),
(4, 'Ibadah Sekolah Minggu', 'Ibadah Sekolah Minggu di setiap hari Minggu Pagi di Gereja', '0000-00-00', '08:00:00', '09:00:00', 'ibadah_minggu', 'Gereja', 'Pendeta/Majelis/Pemuda/Remaja', '-', 'akan_datang', '-', '2025-08-25 00:57:17', '2025-08-25 01:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `jemaat`
--

CREATE TABLE `jemaat` (
  `id` varchar(20) NOT NULL,
  `nij` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_panggilan` varchar(50) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text DEFAULT NULL,
  `rt_rw` varchar(20) DEFAULT NULL,
  `kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status_pernikahan` enum('belum_menikah','menikah','cerai','janda_duda') DEFAULT 'belum_menikah',
  `tanggal_menikah` date DEFAULT NULL,
  `nama_pasangan` varchar(100) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `tanggal_baptis` date DEFAULT NULL,
  `tanggal_sidi` date DEFAULT NULL,
  `status_jemaat` enum('aktif','nonaktif','meninggal','pindah') DEFAULT 'aktif',
  `golongan_darah` enum('A','B','AB','O') DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_kerohanian`
--

CREATE TABLE `kegiatan_kerohanian` (
  `id` int(11) NOT NULL,
  `nama_kegiatan` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `tempat` varchar(200) DEFAULT NULL,
  `jenis_kegiatan` enum('pelatihan','retreat','seminar','workshop','ibadah_khusus','lainnya') NOT NULL,
  `target_peserta` varchar(100) DEFAULT NULL,
  `kuota_peserta` int(11) DEFAULT NULL,
  `biaya` decimal(10,2) DEFAULT NULL,
  `status` enum('direncanakan','pendaftaran','berlangsung','selesai','dibatalkan') DEFAULT 'direncanakan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan_kerohanian`
--

INSERT INTO `kegiatan_kerohanian` (`id`, `nama_kegiatan`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `waktu_mulai`, `waktu_selesai`, `tempat`, `jenis_kegiatan`, `target_peserta`, `kuota_peserta`, `biaya`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Bible Camp', '<p>Bible Camp khusus muda mudi remaja GKJ Randuares</p>', '2025-09-19', '2025-09-20', NULL, NULL, 'Kopeng', 'lainnya', 'Warga Jemaat Pemuda dan Remaja', 50, 100000.00, 'direncanakan', '2025-09-03 14:46:53', '2025-09-03 14:46:53');

-- --------------------------------------------------------

--
-- Table structure for table `keluarga`
--

CREATE TABLE `keluarga` (
  `id` int(11) NOT NULL,
  `kepala_keluarga_id` varchar(20) DEFAULT NULL,
  `nama_keluarga` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('pemasukan','pengeluaran') NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `sub_kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `metode_pembayaran` enum('tunai','transfer','cek','lainnya') DEFAULT 'tunai',
  `referensi` varchar(100) DEFAULT NULL,
  `status` enum('pending','diterima','ditolak') DEFAULT 'diterima',
  `approved_by` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `majelis_anggota`
--

CREATE TABLE `majelis_anggota` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `nama_panggilan` varchar(50) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status_pernikahan` enum('belum_menikah','menikah','cerai','meninggal') DEFAULT 'belum_menikah',
  `tanggal_bergabung` date DEFAULT NULL,
  `status_aktif` enum('aktif','nonaktif','pensiun') DEFAULT 'aktif',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majelis_anggota`
--

INSERT INTO `majelis_anggota` (`id`, `nama_lengkap`, `nama_panggilan`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `no_telepon`, `email`, `status_pernikahan`, `tanggal_bergabung`, `status_aktif`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'Pdt. Dr. Samuel Kristianto', 'Pdt. Samuel', 'Surabaya', '1975-03-15', 'L', 'Jl. Gereja No. 1, Surabaya', '081234567890', 'pdt.samuel@gkjranduares.com', 'menikah', '2010-01-01', 'aktif', NULL, '2025-09-02 05:11:59', '2025-09-02 05:11:59'),
(2, 'Budi Santoso, S.E.', 'Pak Budi', 'Surabaya', '1980-07-22', 'L', 'Jl. Merdeka No. 45, Surabaya', '081234567891', 'budi.santoso@gmail.com', 'menikah', '2015-01-01', 'aktif', NULL, '2025-09-02 05:12:00', '2025-09-02 05:12:00'),
(3, 'Siti Rahayu, S.Pd.', 'Bu Siti', 'Sidoarjo', '1982-11-08', 'P', 'Jl. Pendidikan No. 12, Surabaya', '081234567892', 'siti.rahayu@gmail.com', 'menikah', '2016-01-01', 'aktif', NULL, '2025-09-02 05:12:00', '2025-09-02 05:12:00'),
(5, 'Dewi Sartika, S.E.', 'Bu Dewi', 'Surabaya', '1985-09-14', 'P', 'Jl. Ekonomi No. 23, Surabaya', '081234567894', 'dewi.sartika@gmail.com', 'menikah', '2017-01-01', 'aktif', NULL, '2025-09-02 05:12:00', '2025-09-02 05:12:00'),
(6, 'Rudi Hermawan', 'Pak Rudi', 'Gresik', '1983-12-03', 'L', 'Jl. Industri No. 56, Surabaya', '081234567895', 'rudi.hermawan@gmail.com', 'menikah', '2018-01-01', 'aktif', NULL, '2025-09-02 05:12:00', '2025-09-02 05:12:00'),
(7, 'Nina Kartika, S.Psi.', 'Bu Nina', 'Surabaya', '1987-04-18', 'P', 'Jl. Psikologi No. 34, Surabaya', '081234567896', 'nina.kartika@gmail.com', 'belum_menikah', '2019-01-01', 'aktif', NULL, '2025-09-02 05:12:00', '2025-09-02 05:12:00'),
(8, 'Eko Prasetyo, S.T.', 'Pak Eko', 'Lamongan', '1981-08-25', 'L', 'Jl. Teknik No. 67, Surabaya', '081234567897', 'eko.prasetyo@gmail.com', 'menikah', '2013-01-01', 'aktif', NULL, '2025-09-02 05:12:00', '2025-09-02 05:12:00'),
(9, 'Pdt. Dr. Samuel Kristianto', NULL, 'Surabaya', '1975-03-15', 'L', 'Jl. Gereja No. 1, Surabaya', '081234567890', 'pdt.samuel@gkjranduares.com', 'menikah', '2010-01-01', 'aktif', 'uploads/majelis/pdt_samuel.jpg', '2025-09-02 05:26:26', '2025-09-02 11:09:24'),
(10, 'Budi Santoso, S.Th.', 'Pak Budi', 'Malang', '1980-07-22', 'L', 'Jl. Merdeka No. 45, Surabaya', '081234567891', 'budi.santoso@gmail.com', 'menikah', '2015-01-01', 'aktif', 'uploads/majelis/budi_santoso.jpg', '2025-09-02 05:26:26', '2025-09-02 05:26:26'),
(11, 'Siti Nurhaliza', 'Bu Siti', 'Sidoarjo', '1982-11-08', 'P', 'Jl. Sudirman No. 12, Surabaya', '081234567892', 'siti.nurhaliza@gmail.com', 'menikah', '2016-01-01', 'aktif', 'uploads/majelis/siti_nurhaliza.jpg', '2025-09-02 05:26:26', '2025-09-02 05:26:26'),
(13, 'Dewi Sartika', 'Bu Dewi', 'Lamongan', '1985-09-30', 'P', 'Jl. Diponegoro No. 23, Surabaya', '081234567894', 'dewi.sartika@gmail.com', 'menikah', '2017-01-01', 'aktif', 'uploads/majelis/dewi_sartika.jpg', '2025-09-02 05:26:27', '2025-09-02 05:26:27'),
(14, 'Rudi Hermawan', 'Pak Rudi', 'Tuban', '1983-12-03', 'L', 'Jl. Gajah Mada No. 56, Surabaya', '081234567895', 'rudi.hermawan@gmail.com', 'menikah', '2018-01-01', 'aktif', 'uploads/majelis/rudi_hermawan.jpg', '2025-09-02 05:26:27', '2025-09-02 05:26:27'),
(15, 'Nina Kartika', 'Bu Nina', 'Bojonegoro', '1987-04-18', 'P', 'Jl. Veteran No. 34, Surabaya', '081234567896', 'nina.kartika@gmail.com', 'belum_menikah', '2019-01-01', 'aktif', 'uploads/majelis/nina_kartika.jpg', '2025-09-02 05:26:27', '2025-09-02 05:26:27'),
(16, 'Eko Prasetyo', 'Pak Eko', 'Mojokerto', '1981-08-25', 'L', 'Jl. Hayam Wuruk No. 67, Surabaya', '081234567897', 'eko.prasetyo@gmail.com', 'menikah', '2013-01-01', 'aktif', 'uploads/majelis/eko_prasetyo.jpg', '2025-09-02 05:26:27', '2025-09-02 05:26:27'),
(17, 'Maya Indah', 'Bu Maya', 'Jombang', '1984-06-11', 'P', 'Jl. Basuki Rahmat No. 89, Surabaya', '081234567898', 'maya.indah@gmail.com', 'menikah', '2016-01-01', 'aktif', 'uploads/majelis/maya_indah.jpg', '2025-09-02 05:26:27', '2025-09-02 05:26:27'),
(18, 'Joko Widodo', 'Pak Joko', 'Solo', '1979-01-21', 'L', 'Jl. Semarang No. 123, Surabaya', '081234567899', 'joko.widodo@gmail.com', 'menikah', '2012-01-01', 'aktif', 'uploads/majelis/joko_widodo.jpg', '2025-09-02 05:26:27', '2025-09-02 05:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `majelis_anggota_komisi`
--

CREATE TABLE `majelis_anggota_komisi` (
  `id` int(11) NOT NULL,
  `komisi_id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `peran` varchar(100) DEFAULT NULL,
  `periode_mulai` date DEFAULT NULL,
  `periode_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majelis_anggota_komisi`
--

INSERT INTO `majelis_anggota_komisi` (`id`, `komisi_id`, `anggota_id`, `peran`, `periode_mulai`, `periode_selesai`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:28', '2025-09-02 05:26:28'),
(3, 1, 13, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:28', '2025-09-02 05:26:28'),
(4, 1, 6, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:28', '2025-09-02 05:26:28'),
(5, 1, 16, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(6, 2, 11, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(7, 2, 16, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(8, 2, 15, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(9, 2, 17, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(10, 2, 18, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(11, 3, 18, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(12, 3, 10, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(13, 3, 13, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(15, 3, 11, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(17, 4, 11, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(18, 4, 6, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(19, 4, 16, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(20, 4, 17, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:29', '2025-09-02 05:26:29'),
(21, 5, 13, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(22, 5, 15, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(23, 5, 17, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(24, 5, 18, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(25, 5, 10, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(26, 6, 15, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(27, 6, 6, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(28, 6, 16, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(29, 6, 10, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(30, 6, 13, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(31, 7, 17, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(32, 7, 13, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(33, 7, 11, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(35, 7, 15, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(36, 8, 11, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:30', '2025-09-02 05:26:30'),
(37, 8, 17, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:31', '2025-09-02 05:26:31'),
(38, 8, 13, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:31', '2025-09-02 05:26:31'),
(39, 8, 15, 'Anggota', '2025-01-01', NULL, 'aktif', '2025-09-02 05:26:31', '2025-09-02 05:26:31');

-- --------------------------------------------------------

--
-- Table structure for table `majelis_jabatan`
--

CREATE TABLE `majelis_jabatan` (
  `id` int(11) NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `level_hierarki` int(11) DEFAULT 0,
  `urutan_tampil` int(11) DEFAULT 0,
  `status_aktif` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majelis_jabatan`
--

INSERT INTO `majelis_jabatan` (`id`, `nama_jabatan`, `deskripsi`, `level_hierarki`, `urutan_tampil`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Pendeta', 'Pemimpin rohani gereja', 1, 1, 'aktif', '2025-09-02 04:55:26', '2025-09-02 04:55:26'),
(2, 'Ketua Majelis Jemaat', 'Pemimpin tertinggi majelis jemaat', 2, 2, 'aktif', '2025-09-02 04:55:26', '2025-09-02 04:55:26'),
(3, 'Wakil Ketua Majelis Jemaat', 'Wakil pemimpin majelis jemaat', 3, 3, 'aktif', '2025-09-02 04:55:26', '2025-09-02 04:55:26'),
(4, 'Sekretaris Majelis Jemaat', 'Penanggung jawab administrasi', 4, 4, 'aktif', '2025-09-02 04:55:26', '2025-09-02 04:55:26'),
(5, 'Bendahara Majelis Jemaat', 'Penanggung jawab keuangan', 5, 5, 'aktif', '2025-09-02 04:55:26', '2025-09-02 04:55:26'),
(6, 'Anggota Majelis Jemaat', 'Anggota majelis jemaat', 6, 6, 'aktif', '2025-09-02 04:55:26', '2025-09-02 04:55:26'),
(7, 'Ketua Komisi', 'Pemimpin komisi pelayanan', 7, 7, 'aktif', '2025-09-02 04:55:26', '2025-09-02 04:55:26'),
(8, 'Anggota Komisi', 'Anggota komisi pelayanan', 8, 8, 'aktif', '2025-09-02 04:55:26', '2025-09-02 04:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `majelis_komisi`
--

CREATE TABLE `majelis_komisi` (
  `id` int(11) NOT NULL,
  `nama_komisi` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ketua_id` int(11) DEFAULT NULL,
  `wakil_ketua_id` int(11) DEFAULT NULL,
  `anggota_id` text DEFAULT NULL,
  `status_aktif` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majelis_komisi`
--

INSERT INTO `majelis_komisi` (`id`, `nama_komisi`, `deskripsi`, `ketua_id`, `wakil_ketua_id`, `anggota_id`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Komisi Pelayanan', 'Komisi untuk pelayanan jemaat', 10, NULL, '', 'aktif', '2025-09-02 04:55:26', '2025-09-03 09:32:23'),
(2, 'Komisi Koinonia', 'Komisi untuk persekutuan dan pembinaan', 17, 16, '13,16,8', 'aktif', '2025-09-02 04:55:26', '2025-09-03 09:39:44'),
(3, 'Komisi Marturia', 'Komisi untuk kesaksian dan penginjilan', 18, 10, '13,5,16', 'aktif', '2025-09-02 04:55:27', '2025-09-03 09:40:19'),
(4, 'Komisi Diakonia', 'Komisi untuk pelayanan sosial', 10, NULL, '18,17,15', 'aktif', '2025-09-02 04:55:27', '2025-09-03 09:38:38'),
(5, 'Komisi Musik dan Liturgi', 'Komisi untuk musik dan tata ibadah', 13, 15, '', 'aktif', '2025-09-02 04:55:27', '2025-09-02 05:26:28'),
(6, 'Komisi Pemuda dan Remaja', 'Komisi untuk pelayanan pemuda dan remaja', 15, NULL, '18,15', 'aktif', '2025-09-02 04:55:27', '2025-09-03 11:05:00'),
(7, 'Komisi Anak', 'Komisi untuk pelayanan anak', 17, 13, '2,13', 'aktif', '2025-09-02 04:55:27', '2025-09-03 09:31:24'),
(8, 'Komisi Wanita', 'Komisi untuk pelayanan wanita', 11, 17, '', 'aktif', '2025-09-02 04:55:27', '2025-09-02 05:26:28');

-- --------------------------------------------------------

--
-- Table structure for table `majelis_komisi_anggota`
--

CREATE TABLE `majelis_komisi_anggota` (
  `id` int(11) NOT NULL,
  `komisi_id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `peran` enum('anggota') DEFAULT 'anggota',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `majelis_komisi_anggota`
--

INSERT INTO `majelis_komisi_anggota` (`id`, `komisi_id`, `anggota_id`, `peran`, `created_at`) VALUES
(3, 7, 2, 'anggota', '2025-09-02 10:56:47'),
(4, 7, 13, 'anggota', '2025-09-02 10:56:47'),
(7, 4, 4, 'anggota', '2025-09-02 10:57:20'),
(8, 4, 6, 'anggota', '2025-09-02 10:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `majelis_periode`
--

CREATE TABLE `majelis_periode` (
  `id` int(11) NOT NULL,
  `nama_periode` varchar(100) NOT NULL,
  `tahun_mulai` int(11) NOT NULL,
  `tahun_selesai` int(11) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('aktif','selesai','akan_datang') DEFAULT 'akan_datang',
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majelis_periode`
--

INSERT INTO `majelis_periode` (`id`, `nama_periode`, `tahun_mulai`, `tahun_selesai`, `tanggal_mulai`, `tanggal_selesai`, `status`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Periode 2025', 2025, 2026, NULL, NULL, 'aktif', NULL, '2025-09-02 04:55:27', '2025-09-02 04:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `majelis_riwayat_jabatan`
--

CREATE TABLE `majelis_riwayat_jabatan` (
  `id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `periode_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `alasan_berhenti` text DEFAULT NULL,
  `status` enum('aktif','selesai','diberhentikan') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majelis_riwayat_jabatan`
--

INSERT INTO `majelis_riwayat_jabatan` (`id`, `anggota_id`, `jabatan_id`, `periode_id`, `tanggal_mulai`, `tanggal_selesai`, `alasan_berhenti`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-01-01', NULL, NULL, 'aktif', '2025-09-02 06:21:41', '2025-09-02 06:21:41'),
(2, 2, 2, 1, '2025-01-01', NULL, NULL, 'aktif', '2025-09-02 06:21:41', '2025-09-02 06:21:41'),
(3, 3, 3, 1, '2025-01-01', NULL, NULL, 'aktif', '2025-09-02 06:21:41', '2025-09-02 06:21:41'),
(5, 5, 5, 1, '2025-01-01', NULL, NULL, 'aktif', '2025-09-02 06:21:42', '2025-09-02 06:21:42'),
(6, 6, 6, 1, '2025-01-01', NULL, NULL, 'aktif', '2025-09-02 06:21:42', '2025-09-02 06:21:42');

-- --------------------------------------------------------

--
-- Table structure for table `majelis_struktur`
--

CREATE TABLE `majelis_struktur` (
  `id` int(11) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `periode_mulai` date NOT NULL,
  `periode_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif','pensiun') DEFAULT 'aktif',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majelis_struktur`
--

INSERT INTO `majelis_struktur` (`id`, `jabatan_id`, `anggota_id`, `periode_mulai`, `periode_selesai`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-01-01', '2027-12-31', 'aktif', 'Pendeta Jemaat', '2025-09-02 05:12:01', '2025-09-02 05:12:01'),
(2, 2, 2, '2025-01-01', '2027-12-31', 'aktif', 'Ketua Majelis Jemaat Periode 2025-2027', '2025-09-02 05:12:01', '2025-09-02 05:12:01'),
(3, 3, 3, '2025-01-01', '2027-12-31', 'aktif', 'Wakil Ketua Majelis Jemaat Periode 2025-2027', '2025-09-02 05:12:01', '2025-09-02 05:12:01'),
(5, 4, 5, '2025-01-01', '2027-12-31', 'aktif', 'Bendahara Majelis Jemaat Periode 2025-2027', '2025-09-02 05:12:01', '2025-09-03 10:52:46'),
(6, 5, 6, '2025-01-01', '2027-12-31', 'aktif', 'Anggota Majelis Jemaat Periode 2025-2027', '2025-09-02 05:12:01', '2025-09-03 10:53:33');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_sistem`
--

CREATE TABLE `pengaturan_sistem` (
  `id` int(11) NOT NULL,
  `nama_pengaturan` varchar(100) NOT NULL,
  `nilai` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_sistem`
--

INSERT INTO `pengaturan_sistem` (`id`, `nama_pengaturan`, `nilai`, `deskripsi`, `kategori`, `updated_at`) VALUES
(1, 'nama_gereja', 'Gereja Kristen Jawa Randuares', 'Nama resmi gereja', 'umum', '2025-08-25 00:27:14'),
(2, 'alamat_gereja', 'Jl. Amarta No. 14 Randuares Salatiga', 'Alamat lengkap gereja', 'umum', '2025-08-25 00:27:34'),
(3, 'telepon_gereja', '+62 85 2251 06200', 'Nomor telepon gereja', 'kontak', '2025-08-25 00:27:49'),
(4, 'email_gereja', 'info@gereja.com', 'Email resmi gereja', 'kontak', '2025-08-25 00:26:50'),
(5, 'jam_ibadah_minggu', '06:00', 'Jam ibadah minggu', 'ibadah', '2025-08-25 00:28:08'),
(6, 'jam_ibadah_doa', '18:00', 'Jam ibadah doa', 'ibadah', '2025-08-25 00:26:50'),
(7, 'mata_uang', 'IDR', 'Mata uang yang digunakan', 'keuangan', '2025-08-25 00:26:50'),
(8, 'logo_gereja', 'logo.png', 'File logo gereja', 'tampilan', '2025-09-02 08:53:38'),
(9, 'favicon', 'favicon.ico', 'File favicon website', 'tampilan', '2025-08-25 00:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `persembahan`
--

CREATE TABLE `persembahan` (
  `id` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_persembahan` enum('persembahan_minggu','persembahan_khusus','persembahan_online','lainnya') NOT NULL,
  `nama_pemberi` varchar(100) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `metode_pembayaran` enum('tunai','transfer','cek','online') DEFAULT 'tunai',
  `referensi` varchar(100) DEFAULT NULL,
  `status` enum('pending','diterima','ditolak') DEFAULT 'diterima',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peserta_kegiatan`
--

CREATE TABLE `peserta_kegiatan` (
  `id` int(11) NOT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `jemaat_id` varchar(20) DEFAULT NULL,
  `status_pendaftaran` enum('terdaftar','hadir','tidak_hadir','dibatalkan') DEFAULT 'terdaftar',
  `tanggal_daftar` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `renungan`
--

CREATE TABLE `renungan` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `ayat_alkitab` varchar(200) DEFAULT NULL,
  `konten` text NOT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `tanggal_publish` date DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `renungan`
--

INSERT INTO `renungan` (`id`, `judul`, `ayat_alkitab`, `konten`, `penulis`, `kategori`, `status`, `tanggal_publish`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Mengapa Tuhan Memberi Petrus Kunci Kerajaan Surga?', 'Matius 16:19', '<h3><strong>Aku akan memberikan kepada-Mu kunci-kunci Kerajaan Surga: apa pun yang engkau ikat di bumi akan terikat di surga: dan apa pun yang engkau lepaskan di bumi akan terlepas di sorga</strong></h3>\r\n\r\n<hr />\r\n<p>Dalam&nbsp;<a href=\"https://renunganhariankatolik.findshepherd.com/category/injil-katolik-hari-ini\" rel=\"noopener\" target=\"_blank\" title=\"Injil Katolik Hari Ini\">Injil</a>&nbsp;Matius 16:19, Tuhan Yesus berkata kepada Petrus: &quot;<strong>Aku akan memberikan kepadamu kunci kerajaan surga. Apa yang kamu ikat di dunia ini akan terikat di sorga; apa yang kamu lepaskan di dunia juga akan terikat di sorga. Akan dibebaskan</strong>.&rdquo; Dari firman Tuhan Yesus, kita tahu bahwa Petrus dipuji oleh Tuhan. Lalu mengapa Tuhan Yesus memuji Petrus dan bahkan memberikan Petrus kunci kerajaan surga daripada murid-murid lain? Mengetahui alasannya, kita dapat menemukan jalan mendapatkan perkenanan Tuhan dari pengalaman sukses Petrus.&nbsp;<a href=\"https://renunganhariankatolik.findshepherd.com/category/bacaan-harian-katolik/ayat-emas-alkitab\" rel=\"noopener\" target=\"_blank\" title=\"Ayat Emas Alkitab\">Alkitab</a>&nbsp;mencatat bahwa ketika Tuhan Yesus bertanya kepada murid-murid-Nya: &quot;Menurut orang, siapakah Anak Manusia itu?&quot;Dan mereka menjawab, katanya: &quot;<strong>Ada yang mengatakan: Yohanes Pembaptis; ada yang menyatakan, Elia; yang lain mengatakan, Yeremia, atau salah satu dari para nabi</strong>.&quot; Yesus bertanya kepada mereka: &quot;Tetapi menurut engkau, siapakah Aku ini?&quot; Dan Simon Petrus menjawab, &quot;Engkau adalah Kristus, Anak Tuhan yang hidup.&quot;&nbsp;(Lihat Matius 16:13-16)&nbsp;Dapat dilihat bahwa di antara kedua belas murid, hanya Petrus yang mengenal bahwa Tuhan Yesus adalah Mesias yang akan datang, Kristus, yang datang dari Roh Kudus. Oleh karena itu, bagaimanapun orang Farisi mengutuk, menyerang, atau menghakimi Tuhan Yesus, dia tidak tertipu dan terus mengikuti Tuhan Yesus, ini menunjukkan bahwa Petrus memiliki pengetahuan yang benar tentang Tuhan Yesus. Jadi bagaimana tepatnya Petrus mengejar untuk mengenal dan mengasihi Tuhan? Firman Tuhan dengan jelas menyatakan kebenaran dalam hal ini.</p>\r\n\r\n<p>Tuhan Yang Mahakuasa berfirman: &ldquo;<strong>Petrus mengikuti Yesus selama beberapa tahun dan melihat banyak hal dalam diri Yesus yang tidak dimiliki oleh orang lain&hellip;...Dalam kehidupannya, Petrus mengukur dirinya sendiri dengan segala sesuatu yang Yesus lakukan. Yang terutama adalah bahwa pesan-pesan yang Yesus khotbahkan terukir di dalam hatinya. Petrus benar-benar penuh pengabdian dan setia kepada Yesus, dan ia tidak pernah mengeluh tentang Yesus. Akibatnya, ia menjadi rekan Yesus yang setia ke mana pun Dia pergi. Petrus mengamati ajaran-ajaran Yesus, perkataan-Nya yang lembut, apa yang dimakan dan dipakai-Nya, kehidupan-Nya sehari-hari, serta bagaimana Dia melakukan perjalanan-Nya. Ia mengikuti teladan Yesus dalam segala hal. Ia tidak pernah merasa diri benar, tetapi membuang segala hal yang telah ketinggalan zaman dan mengikuti teladan Yesus dalam perkataan dan perbuatan. Pada saat itulah, Petrus merasa bahwa langit dan bumi dan segala sesuatu berada di tangan Yang Mahakuasa, dan karena alasan ini, ia tidak memiliki pilihannya sendiri. Petrus juga menyerap segala sesuatu yang diperbuat Yesus dan menggunakannya sebagai teladan</strong>.&rdquo;</p>\r\n\r\n<p>Dikutip dari &quot; Tentang Kehidupan Petrus&rdquo;</p>\r\n\r\n<p>&ldquo;<strong>Setelah satu periode pengalaman, Petrus melihat banyak perbuatan Tuhan di dalam diri Yesus, dia menyaksikan keindahan Tuhan, dan dia melihat banyak keberadaan Tuhan dalam diri Yesus. Demikian juga dia melihat bahwa perkataan yang diucapkan Yesus tidak mungkin diucapkan oleh manusia, dan bahwa pekerjaan yang Yesus lakukan tidak mungkin dilakukan oleh manusia. Terlebih lagi, dalam perkataan dan tindakan Yesus, Petrus melihat banyak hikmat Tuhan, dan banyak pekerjaan yang bersifat ilahi. Selama berbagai pengalamannya itu, dia tidak hanya mengenal dirinya sendiri, tetapi juga memperhatikan dengan saksama setiap tindakan Yesus, yang membuatnya menemukan banyak hal baru, yaitu, ada banyak pengungkapan tentang Tuhan yang nyata dalam pekerjaan yang Tuhan perbuat melalui Yesus, dan bahwa Yesus berbeda dari manusia biasa dalam hal perkataan yang Dia ucapkan dan tindakan-tindakan yang diambil-Nya, serta cara Dia menggembalakan gereja-gereja dan pekerjaan yang Dia lakukan. Jadi, Petrus memetik banyak pelajaran yang memang harus dia pelajari dari Yesus, dan pada saat Yesus akan dipakukan di kayu salib, dia telah memperoleh sejumlah pengetahuan tentang Yesus&mdash;pengetahuan yang menjadi dasar kesetiaan seumur hidupnya kepada Yesus dan penyalibannya secara terbalik yang ditanggungnya demi Tuhan</strong>. &rdquo;</p>\r\n\r\n<p>Dikutip dari &quot; Hanya Mereka yang Mengenal Tuhan yang Bisa Menjadi Kesaksian bagi Tuhan&rdquo;</p>\r\n\r\n<p>Dari firman Tuhan, Petrus sangat ingin mengenal Tuhan, ketika dia berhubungan dengan Tuhan Yesus, dia melihat semua yang dia katakan, lakukan, dan setiap perbuatannya. Di dalam Tuhan Yesus, ia melihat semua yang dimiliki Tuhan dan siapa Dia, misalnya firman Tuhan Yesus penuh otoritas dan kuasa; pekerjaan Tuhan Yesus tidak dapat dilakukan oleh siapa pun; kasih sayang, cinta, toleransi dan kesabaran Tuhan Yesus terhadap manusia, tidak dimiliki oleh siapa pun; Dan sikap Tuhan Yesus terhadap orang-orang Farisi dan orang biasa berbeda. Anda dapat melihat bahwa kekudusan dan kebenaran Tuhan, pencurahan dan kehidupan Tuhan, semuanya adalah hal-hal positif, yang dapat membawa terang bagi orang-orang. Semua keuntungan ini menginspirasinya untuk mencintai Tuhan. Dia menjadikan Tuhan Yesus sebagai tolok ukur, memperhatikan kehendak Tuhan, setia kepada Tuhan, menggembalakan gereja, dan memberitakan Injil. Pada akhirnya, Dia bisa mencapai ketaatan sampai mati dan mencintai Tuhan sepenuhnya dan disalibkan terbalik. Tuhan Yesus menyukai kualitas kemanusiaan Petrus dan upayanya untuk percaya kepada Tuhan Dia tahu bahwa Petrus adalah yang paling dapat dipercaya, jadi Tuhan Yesus memberi Petrus kunci kerajaan surga.</p>\r\n\r\n<p>Ketika Tuhan Yesus memberi Petrus kunci kerajaan surga, dia juga memberi tahu kita bahwa Petrus adalah orang yang berkenan di hati Tuhan, dan pengejarannya dipuji oleh Tuhan. Jika kita ingin masuk kerajaan surga, kita harus meneladani Petrus dan mengejar dalam hidup kita untuk mengenal Tuhan, mencintai Tuhan, mengamalkan firman Tuhan, dan menjadi orang yang mencintai dan mengenal Tuhan, sehingga ada kesempatan untuk masuk ke dalam kerajaan surga.</p>\r\n\r\n<p>Melihat sampai disini, teman-teman, apakah Anda merasakan niat baik Tuhan? Apakah Anda ingin mempelajari firman Tuhan dan menjadi orang yang mengenal dan mengasihi Tuhan?</p>', 'Doni Abiy', 'Pengharapan', 'published', '2025-09-14', 39, '2025-09-02 12:02:01', '2025-09-03 14:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `sejarah`
--

CREATE TABLE `sejarah` (
  `id` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `judul` varchar(120) NOT NULL,
  `konten` longtext NOT NULL,
  `tahun_didirikan` year(4) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sejarah`
--

INSERT INTO `sejarah` (`id`, `judul`, `konten`, `tahun_didirikan`, `updated_at`) VALUES
(1, 'GKJ RANDUARES DALAM SEJARAH', '<p><span style=\"font-size:14px\"><strong>1932 &ndash; 1935</strong></span></p>\r\n\r\n<p>Pada sekitar tahun 1932 sudah ada pekabaran injil yang masuk ke bumi Randuares yang dibawa oleh orang-orang dari Salib Putih. Sasaran PI mereka adalah kepada anak-anak yang ada di Randuares. Oleh sebab anak-anak senang dengan dunia bermain,maka para pekabar Injil ini melakukan PI dengan tehnik mengajak anak-anak untuk bermain bersama di tanah lapang yang ada di daerah Randuares. Bersamaan dengan permainan itu, mereka masukkan tokoh-tokoh Alkitab didalamnya dan mereka juga memberikan cerita kepada anak-anak dengan mengambil cerita dari Alkitab. Selain itu, anak-anak juga diajari menyanyi dengan lagu-lagu rohani. Pengasuh anak-anak saat itu antara lain : Bp. Kartono, ibu Juminem dan dibantu oleh ibu Parsi</p>\r\n\r\n<p><strong>NAMUN SAYANG TIDAK ADA DATA SEJARAH PADA TAHUN TAHUN BERIKUTNYA SAMPAI DENGAN TAHUN&nbsp;</strong><span style=\"font-size:14px\"><strong>1965 </strong></span></p>\r\n\r\n<p><span style=\"font-size:14px\"><strong>1965</strong></span></p>\r\n\r\n<p>Kepala polisi getasan Sandiyo mengeluarkan surat perintah bahwa di daerah Randuares dan sekitarnya haruslah ada pembinaan masalah agama dan keyakinan, supaya setiap warga dapat memiliki spiritualitas dalam hidupnya 1966 Datanglah Guru Injil Brotowiratmojo yang ditugaskan secara khusus oleh deputat pekabaran Injil Klasis Semarang untuk melayani PI di Salib pUtih dan sekitarnya. Dan inilah awal Pekabaran Injil secara besar besaran di Randuares. Dalam melaksanakan tugasnya itu, beliau menjalankan tugas bersama Pdt. Soesilo Darmowigoto melakukan pendekatan demi pendekatan kepada masyarakat sekitar. Dan dalam pelayannannya ini merka dibantu oleh para sesepuh warga Randuares, antara lain: <strong>&bull; Sunoto Prayitno &bull; Tjiptomihardjo &bull; Hardjo Suwito &bull; Mertoarso</strong></p>\r\n\r\n<p>Pekabaran Injil yang dilakukan menggunakan metode permainan ketoprak dengan mengambil cerita dari Alkitab. Dan sesuai pementasan ketoprak, selalu diteriakkan seruan &ldquo;<strong>MONGGO SAMI NDEREK GUSTI</strong>&rdquo;. Perkembangan orang percaya pada saat itu berkembang dengan sangat baik dan banyak orang yang ikut kelompok Kristen. Pada saat itu, untuk memfasilitasi pertemuan dan acara acara mereka, maka dipakailah gedung sekolah dasar Kumpulrejo. Dan disanalah kemudian dilaksanakan ibadah-ibadah yang dilayani oleh pdt. Soesilo darmowigoto, Pdt. Basuki Probowinoto, Pdt. Broto semedi wiryotenojo dan juga guru injil Brotowiratmodjo serta dibantu Pdt. WH. Rekso soebroto Tempat ibadah yang awalnya berada di sd Kumpulrejo, selanjutnya pindah ke rumah milik KAMITUWO MERTOARSO. Pada saat itu yang ikut kebaktian bukan hanya orang Randuares saja, namun ada juga beberapa orang dari Kenteng dan juga Karangalit. Lalu pada saat itu terjadilah babtisan massal I berjumlah 150 orang pada tgl 11 Desember <span style=\"font-size:12px\">1966<strong> </strong></span></p>\r\n\r\n<p><span style=\"font-size:14px\"><strong>1967 </strong></span></p>\r\n\r\n<p>21 Mei 1967 terjadi babtisan massal ke 2 dengan jumlah 222 orang 3 desember 1967 terjadi babtisan ke 3 dengan jumlah 12 orang</p>\r\n\r\n<p><strong>1968 </strong></p>\r\n\r\n<p>Melihat perkembangan jemaat yang luar biasa banyak dan sangat pesat ini, maka dibangunlah sebuah rumah ibadah sederhana yang terbuat dari kayu dan bambu dengan ukuran 6 x 15 meter diatas tanah milik kamituwo mertoarso. Pembangunan tempat ibadah ini dilakukan dengan semangat gotong royong yang dilakukan sendiri oleh orang-orang Kristen pada saat itu. Perlu dicatat adanya pelayanan yang luar biasa untuk menjada dan membersihkan bangunan temmpat ibadah pada saat itu, yang dilakukan oleh koster pertama gereja yaitu MBAH SENEN, yang tinggal di gubug kecil di belakang gereja. Dan menurut cerita, akhirnya mbah Senen mengikuti program transmigrasi kePalembang pada tahun 1984.<strong> 31 maret 1968 terjadi babtisan massal ke 4 dengan jumlah 16 orang 8 desember 1968</strong> terjadi babtisan massal ke 5 dengan jumlah 56 orang Dan pada saat itu mulai diadakannya saresehan dengan jumlah kehadiran yang sangat menggembirakan, yaitu 40-60 orang dan bahkan bisa lebih. Bahkan pada tahun tersebut muncullah persekutuan sekolah Minggu dibawah asuhan : <strong>Marius tumirin, harto Kamsi dan Andreas Sudimin</strong></p>\r\n\r\n<p><strong>1969 </strong></p>\r\n\r\n<p>Pada tahun 1969, GI Brotowiratmijo dipanggil untuk menjadi pendeta di GKJ Kendal sehingga harus meninggalkan Randuares dan tugasnya digantikan oleh Pdt.Sarwi Padmowijono dengan dibantu oleh GI. Soedarmo Hadiwarsito. Berdasarkan cerita, beliau Emiritus pada tahun 2004 dan bertempat tinggal di desa Sranten kec karanggede Kab. Boyolali</p>\r\n\r\n<p><strong>1970</strong></p>\r\n\r\n<p>Pada tahun 1970, perkembangan jemaat di Randuares terus meningkat dan bahkan sekarang Randuares sudah berani keluar untuk melakukan PI ke tempat lain dan tetap menggunakan metode ketoprak.</p>\r\n\r\n<p><strong>1978</strong></p>\r\n\r\n<p>Atas prakarsa Pdt. Soesilo darmowigoto dibelilah tanah milik SOEWITOREJO yang selanjutnya didirikanlah sebuah bangunan gereja Randuares permanen yang bisa kita pakai sampai saat ini.</p>\r\n\r\n<p><strong>2007</strong></p>\r\n\r\n<p>GKJ RANDUARES resmi menjadi gereja Dewasa yang didewasakan oleh GKJ SIDOMUKTI Salatiga</p>\r\n\r\n<p><strong>2010</strong></p>\r\n\r\n<p>Setelah dilakukan proses pencalonan, pemilihan, pembimbingan serta masa vikariat calon pendeta, maka pada tanggal 21 mei 2010 di tahbiskanlah pendeta I GKJ Randuares atas diri <strong>Pdt. Adi Setyo Kristianto, S.Si</strong></p>', '1932', '2025-09-03 14:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `warta`
--

CREATE TABLE `warta` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `konten` text NOT NULL,
  `ringkasan` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` enum('berita','pengumuman','acara','renungan','lainnya') DEFAULT 'berita',
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `tanggal_publish` date DEFAULT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `youtube_channels`
--

CREATE TABLE `youtube_channels` (
  `id` int(11) NOT NULL,
  `channel_id` varchar(100) NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `channel_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `youtube_channels`
--

INSERT INTO `youtube_channels` (`id`, `channel_id`, `channel_name`, `channel_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'UC4BiIrPNgcc43kl07IUkMbg', 'GKJ Randuares', 'https://www.youtube.com/@gkjranduares4607', 1, 1, '2025-09-02 01:13:44', '2025-09-02 01:13:44'),
(2, 'UCh296DhDt9GLipLEySNl9aQ', 'GKJ Randuares New', 'https://www.youtube.com/@GKJRanduares', 1, 2, '2025-09-02 01:13:44', '2025-09-02 01:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `youtube_config`
--

CREATE TABLE `youtube_config` (
  `id` int(11) NOT NULL,
  `config_key` varchar(100) NOT NULL,
  `config_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `youtube_config`
--

INSERT INTO `youtube_config` (`id`, `config_key`, `config_value`, `created_at`, `updated_at`) VALUES
(1, 'youtube_api_key', 'AIzaSyAJ4s39XgG2SbByjLB_JHYAlfnyDaEdpk0', '2025-09-02 01:13:43', '2025-09-02 01:13:43'),
(2, 'youtube_max_results', '12', '2025-09-02 01:13:43', '2025-09-02 01:13:43'),
(3, 'youtube_total_videos', '500', '2025-09-02 01:13:43', '2025-09-02 01:13:43'),
(4, 'youtube_fetch_all_videos', '1', '2025-09-02 01:13:43', '2025-09-02 01:13:43'),
(5, 'youtube_cache_duration', '3600', '2025-09-02 01:13:43', '2025-09-02 01:13:43'),
(6, 'youtube_enable_cache', '1', '2025-09-02 01:13:43', '2025-09-02 01:13:43'),
(7, 'youtube_search_enabled', '1', '2025-09-02 01:13:43', '2025-09-02 01:13:43'),
(8, 'youtube_multi_channel_enabled', '1', '2025-09-02 01:13:43', '2025-09-02 01:13:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `anggota_keluarga`
--
ALTER TABLE `anggota_keluarga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keluarga_id` (`keluarga_id`),
  ADD KEY `jemaat_id` (`jemaat_id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_ibadah`
--
ALTER TABLE `jadwal_ibadah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jadwal_tanggal` (`tanggal`);

--
-- Indexes for table `jemaat`
--
ALTER TABLE `jemaat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nij` (`nij`),
  ADD KEY `idx_jemaat_nama` (`nama_lengkap`),
  ADD KEY `idx_jemaat_status` (`status_jemaat`);

--
-- Indexes for table `kegiatan_kerohanian`
--
ALTER TABLE `kegiatan_kerohanian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kegiatan_status` (`status`);

--
-- Indexes for table `keluarga`
--
ALTER TABLE `keluarga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kepala_keluarga_id` (`kepala_keluarga_id`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_keuangan_tanggal` (`tanggal`),
  ADD KEY `idx_keuangan_jenis` (`jenis`);

--
-- Indexes for table `majelis_anggota`
--
ALTER TABLE `majelis_anggota`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `majelis_anggota_komisi`
--
ALTER TABLE `majelis_anggota_komisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_komisi_anggota` (`komisi_id`,`anggota_id`,`periode_mulai`),
  ADD KEY `anggota_id` (`anggota_id`);

--
-- Indexes for table `majelis_jabatan`
--
ALTER TABLE `majelis_jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `majelis_komisi`
--
ALTER TABLE `majelis_komisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ketua_id` (`ketua_id`),
  ADD KEY `wakil_ketua_id` (`wakil_ketua_id`);

--
-- Indexes for table `majelis_komisi_anggota`
--
ALTER TABLE `majelis_komisi_anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_komisi` (`komisi_id`),
  ADD KEY `idx_anggota` (`anggota_id`);

--
-- Indexes for table `majelis_periode`
--
ALTER TABLE `majelis_periode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `majelis_riwayat_jabatan`
--
ALTER TABLE `majelis_riwayat_jabatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`anggota_id`),
  ADD KEY `jabatan_id` (`jabatan_id`),
  ADD KEY `periode_id` (`periode_id`);

--
-- Indexes for table `majelis_struktur`
--
ALTER TABLE `majelis_struktur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_jabatan_periode` (`jabatan_id`,`periode_mulai`),
  ADD KEY `anggota_id` (`anggota_id`);

--
-- Indexes for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_pengaturan` (`nama_pengaturan`);

--
-- Indexes for table `persembahan`
--
ALTER TABLE `persembahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_persembahan_tanggal` (`tanggal`);

--
-- Indexes for table `peserta_kegiatan`
--
ALTER TABLE `peserta_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kegiatan_id` (`kegiatan_id`),
  ADD KEY `jemaat_id` (`jemaat_id`);

--
-- Indexes for table `renungan`
--
ALTER TABLE `renungan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_renungan_status` (`status`);

--
-- Indexes for table `sejarah`
--
ALTER TABLE `sejarah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warta`
--
ALTER TABLE `warta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_warta_status` (`status`),
  ADD KEY `idx_warta_kategori` (`kategori`);

--
-- Indexes for table `youtube_channels`
--
ALTER TABLE `youtube_channels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `youtube_config`
--
ALTER TABLE `youtube_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `config_key` (`config_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `anggota_keluarga`
--
ALTER TABLE `anggota_keluarga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_ibadah`
--
ALTER TABLE `jadwal_ibadah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kegiatan_kerohanian`
--
ALTER TABLE `kegiatan_kerohanian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `keluarga`
--
ALTER TABLE `keluarga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `majelis_anggota`
--
ALTER TABLE `majelis_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `majelis_anggota_komisi`
--
ALTER TABLE `majelis_anggota_komisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `majelis_jabatan`
--
ALTER TABLE `majelis_jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `majelis_komisi`
--
ALTER TABLE `majelis_komisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `majelis_komisi_anggota`
--
ALTER TABLE `majelis_komisi_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `majelis_periode`
--
ALTER TABLE `majelis_periode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `majelis_riwayat_jabatan`
--
ALTER TABLE `majelis_riwayat_jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `majelis_struktur`
--
ALTER TABLE `majelis_struktur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `peserta_kegiatan`
--
ALTER TABLE `peserta_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `renungan`
--
ALTER TABLE `renungan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warta`
--
ALTER TABLE `warta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `youtube_channels`
--
ALTER TABLE `youtube_channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `youtube_config`
--
ALTER TABLE `youtube_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota_keluarga`
--
ALTER TABLE `anggota_keluarga`
  ADD CONSTRAINT `anggota_keluarga_ibfk_1` FOREIGN KEY (`keluarga_id`) REFERENCES `keluarga` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anggota_keluarga_ibfk_2` FOREIGN KEY (`jemaat_id`) REFERENCES `jemaat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `galeri`
--
ALTER TABLE `galeri`
  ADD CONSTRAINT `galeri_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `keluarga`
--
ALTER TABLE `keluarga`
  ADD CONSTRAINT `keluarga_ibfk_1` FOREIGN KEY (`kepala_keluarga_id`) REFERENCES `jemaat` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD CONSTRAINT `keuangan_ibfk_1` FOREIGN KEY (`approved_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `majelis_anggota_komisi`
--
ALTER TABLE `majelis_anggota_komisi`
  ADD CONSTRAINT `majelis_anggota_komisi_ibfk_1` FOREIGN KEY (`komisi_id`) REFERENCES `majelis_komisi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `majelis_anggota_komisi_ibfk_2` FOREIGN KEY (`anggota_id`) REFERENCES `majelis_anggota` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `majelis_komisi`
--
ALTER TABLE `majelis_komisi`
  ADD CONSTRAINT `majelis_komisi_ibfk_1` FOREIGN KEY (`ketua_id`) REFERENCES `majelis_anggota` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `majelis_komisi_ibfk_2` FOREIGN KEY (`wakil_ketua_id`) REFERENCES `majelis_anggota` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `majelis_riwayat_jabatan`
--
ALTER TABLE `majelis_riwayat_jabatan`
  ADD CONSTRAINT `majelis_riwayat_jabatan_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `majelis_anggota` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `majelis_riwayat_jabatan_ibfk_2` FOREIGN KEY (`jabatan_id`) REFERENCES `majelis_jabatan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `majelis_riwayat_jabatan_ibfk_3` FOREIGN KEY (`periode_id`) REFERENCES `majelis_periode` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `majelis_struktur`
--
ALTER TABLE `majelis_struktur`
  ADD CONSTRAINT `majelis_struktur_ibfk_1` FOREIGN KEY (`jabatan_id`) REFERENCES `majelis_jabatan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `majelis_struktur_ibfk_2` FOREIGN KEY (`anggota_id`) REFERENCES `majelis_anggota` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peserta_kegiatan`
--
ALTER TABLE `peserta_kegiatan`
  ADD CONSTRAINT `peserta_kegiatan_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan_kerohanian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peserta_kegiatan_ibfk_2` FOREIGN KEY (`jemaat_id`) REFERENCES `jemaat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
