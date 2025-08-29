-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 12:13 PM
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
(1, 'sadmin', '$2y$10$p0mDEo6Zu7K3lGjOsZIB/OG47nGObu8HnbEL/CO2ydOLD4VqcNenq', 'Doni Abiy', 'admin@gereja.com', 'super_admin', 'aktif', '2025-08-25 00:26:50', '2025-08-25 01:35:06'),
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
(8, 'logo_gereja', 'logo.png', 'File logo gereja', 'tampilan', '2025-08-25 00:26:50'),
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
(1, 'RANDUARES DALAM SEJARAH', '1932 – 1935\nPada sekitar tahun 1932 sudah ada pekabaran injil yang masuk ke bumi Randuares yang dibawa oleh orang-orang dari Salib Putih. Sasaran PI mereka adalah kepada anak-anak yang ada di Randuares. Oleh sebab anak-anak senang dengan dunia bermain,maka para pekabar Injil ini melakukan PI dengan tehnik mengajak anak-anak untuk bermain bersama di tanah lapang yang ada di daerah Randuares. Bersamaan dengan permainan itu, mereka masukkan tokoh-tokoh Alkitab didalamnya dan mereka juga memberikan cerita kepada anak-anak dengan mengambil  cerita dari Alkitab. Selain itu, anak-anak juga diajari menyanyi dengan lagu-lagu rohani.\nPengasuh anak-anak saat itu antara lain : Bp. Kartono, ibu Juminem dan dibantu oleh ibu Parsi\nNAMUN SAYANG TIDAK ADA DATA SEJARAH PADA TAHUN TAHUN BERIKUTNYA SAMPAI DENGAN TAHUN 1965\n\n1965\nKepala polisi getasan Sandiyo mengeluarkan surat perintah bahwa di daerah Randuares dan sekitarnya haruslah ada pembinaan masalah agama dan keyakinan, supaya setiap warga dapat memiliki spiritualitas dalam hidupnya \n\n1966\nDatanglah Guru Injil Brotowiratmojo yang ditugaskan secara khusus oleh deputat pekabaran Injil Klasis Semarang untuk melayani PI di Salib pUtih dan sekitarnya. Dan inilah awal Pekabaran Injil secara besar besaran di Randuares.\n\nDalam melaksanakan tugasnya itu, beliau menjalankan tugas bersama Pdt. Soesilo Darmowigoto melakukan pendekatan demi pendekatan kepada masyarakat sekitar. Dan dalam pelayannannya ini merka dibantu oleh para sesepuh warga Randuares, antara lain:\n•	Sunoto Prayitno\n•	Tjiptomihardjo\n•	Hardjo Suwito\n•	Mertoarso \n\nPekabaran Injil yang dilakukan menggunakan metode permainan ketoprak dengan mengambil cerita dari Alkitab. Dan sesuai pementasan ketoprak, selalu diteriakkan seruan “MONGGO SAMI NDEREK GUSTI”. Perkembangan orang percaya pada saat itu berkembang dengan sangat baik dan banyak orang yang ikut kelompok Kristen. \nPada saat itu, untuk memfasilitasi pertemuan dan acara acara mereka, maka dipakailah gedung sekolah dasar Kumpulrejo. Dan disanalah kemudian dilaksanakan ibadah-ibadah yang dilayani oleh pdt. Soesilo darmowigoto, Pdt. Basuki Probowinoto, Pdt. Broto semedi wiryotenojo dan juga guru injil Brotowiratmodjo serta dibantu Pdt. WH. Rekso soebroto\n\nTempat ibadah yang awalnya berada di sd Kumpulrejo, selanjutnya pindah ke rumah milik KAMITUWO MERTOARSO. Pada saat itu yang ikut kebaktian bukan hanya orang Randuares saja, namun ada juga beberapa orang dari Kenteng dan juga Karangalit.\nLalu pada saat itu terjadilah babtisan massal I berjumlah 150 orang pada tgl 11 desember 1966\n\n1967\n21 mei 1967 terjadi babtisan massal ke 2 dengan jumlah 222 orang\n3 desember 1967 terjadi babtisan ke 3 dengan jumlah 12 orang\n\n1968\nMelihat perkembangan jemaat yang luar biasa banyak dan sangat pesat ini, maka dibangunlah sebuah rumah ibadah sederhana yang terbuat dari kayu dan bambu dengan ukuran 6 x 15 meter diatas tanah milik kamituwo mertoarso.  Pembangunan tempat ibadah ini dilakukan dengan semangat gotong royong yang dilakukan sendiri oleh orang-orang Kristen pada saat itu.\nPerlu dicatat adanya pelayanan yang luar biasa untuk menjada dan membersihkan bangunan temmpat ibadah pada saat itu, yang dilakukan oleh koster pertama gereja yaitu MBAH SENEN, yang tinggal di gubug kecil di belakang gereja. Dan menurut cerita, akhirnya mbah Senen mengikuti program transmigrasi kePalembang pada tahun 1984.\n\n31 maret 1968 terjadi babtisan massal ke 4 dengan jumlah 16 orang\n8 desember 1968 terjadi babtisan massal ke 5 dengan jumlah 56 orang\n\nDan pada saat itu mulai diadakannya saresehan dengan jumlah kehadiran yang sangat menggembirakan, yaitu 40-60 orang dan bahkan bisa lebih.\nBahkan pada tahun tersebut muncullah persekutuan sekolah Minggu dibawah asuhan :\nMarius tumirin, harto Kamsi dan Andreas Sudimin\n\n1969\nPada tahun 1969, GI Brotowiratmijo dipanggil untuk menjadi pendeta di GKJ Kendal sehingga harus meninggalkan Randuares dan tugasnya digantikan oleh Pdt.Sarwi Padmowijono dengan dibantu oleh GI. Soedarmo Hadiwarsito.\nBerdasarkan cerita, beliau Emiritus pada tahun 2004 dan bertempat tinggal di desa Sranten kec karanggede  Kab. Boyolali\n\n1970\nPada tahun 1970, perkembangan jemaat di Randuares terus meningkat dan bahkan sekarang Randuares sudah berani keluar untuk melakukan PI ke tempat lain dan tetap menggunakan metode ketoprak.\n\n1978\nAtas prakarsa Pdt. Soesilo darmowigoto dibelilah tanah milik SOEWITOREJO  yang selanjutnya didirikanlah sebuah bangunan gereja Randuares permanen yang bisa kita pakai sampai saat ini.\n\n2007\nGKJ RANDUARES resmi menjadi gereja Dewasa yang  didewasakan oleh GKJ SIDOMUKTI Salatiga\n\n2010\nSetelah dilakukan proses pencalonan, pemilihan, pembimbingan serta masa vikariat calon pendeta, maka pada tanggal 21 mei 2010 di tahbiskanlah pendeta I GKJ Randuares atas diri \nPdt. Adi Setyo Kristianto, S.Si\n', '1932', '2025-08-25 09:13:02');

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
-- AUTO_INCREMENT for table `jadwal_ibadah`
--
ALTER TABLE `jadwal_ibadah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kegiatan_kerohanian`
--
ALTER TABLE `kegiatan_kerohanian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keluarga`
--
ALTER TABLE `keluarga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `peserta_kegiatan`
--
ALTER TABLE `peserta_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `renungan`
--
ALTER TABLE `renungan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warta`
--
ALTER TABLE `warta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `peserta_kegiatan`
--
ALTER TABLE `peserta_kegiatan`
  ADD CONSTRAINT `peserta_kegiatan_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan_kerohanian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peserta_kegiatan_ibfk_2` FOREIGN KEY (`jemaat_id`) REFERENCES `jemaat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
