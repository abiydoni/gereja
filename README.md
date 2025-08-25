# 🏛️ Sistem Manajemen Gereja Kristen Jawa

Sistem manajemen gereja modern dengan frontend dan backend yang lengkap untuk mengelola data jemaat, jadwal ibadah, keuangan, warta gereja, galeri, renungan, dan kegiatan kerohanian.

## 📁 Struktur Folder

```
gereja/
├── assets/
│   ├── css/
│   │   └── style.css          # Custom CSS styles
│   ├── js/
│   │   └── main.js            # Custom JavaScript functions
│   └── images/
│       ├── logo.png           # Logo gereja
│       └── bg/                # Background slideshow images
│           ├── bg1.jpg
│           ├── bg2.jpg
│           ├── bg3.jpg
│           ├── bg4.jpg
│           └── bg5.jpg
├── includes/
│   ├── config.php             # Konfigurasi database dan aplikasi
│   └── database.php           # Class Database untuk koneksi PDO
├── admin/                      # Panel admin (akan dibuat)
│   ├── login.php
│   ├── dashboard.php
│   ├── profile.php
│   └── settings.php
├── pages/                      # Halaman frontend (akan dibuat)
│   ├── jemaat.php
│   ├── jadwal-ibadah.php
│   ├── keuangan.php
│   ├── warta.php
│   ├── galeri.php
│   ├── renungan.php
│   └── kegiatan.php
├── proses/                     # File proses backend (akan dibuat)
├── index.php                   # Halaman utama frontend
└── README.md                   # Dokumentasi ini
```

## 🚀 Fitur Utama

### Frontend

- ✅ **Design Modern & Responsive** dengan Tailwind CSS
- ✅ **Animasi AOS** untuk scroll effects
- ✅ **Background Slideshow** dengan 5 gambar
- ✅ **Navbar Dinamis** berdasarkan session login
- ✅ **Tema Gereja Kristen Jawa** dengan warna kayu dan wayang
- ✅ **Mobile Friendly** untuk akses HP

### Backend (Akan Dibuat)

- 🔄 **Data Jemaat** - CRUD lengkap
- 🔄 **Jadwal Ibadah** - Manajemen jadwal
- 🔄 **Keuangan & Persembahan** - Laporan keuangan
- 🔄 **Warta Gereja** - Berita dan pengumuman
- 🔄 **Galeri** - Upload dan manajemen foto
- 🔄 **Renungan Harian** - Konten rohani
- 🔄 **Kegiatan Kerohanian** - Event dan aktivitas

## 🎨 Design System

### Warna Utama

- **Primary:** `#8B4513` (Saddle Brown)
- **Secondary:** `#A0522D` (Sienna)
- **Accent:** `#CD853F` (Peru)
- **Light:** `#D2B48C` (Tan)

### Font

- **Primary:** Inter (Google Fonts)
- **Icons:** Font Awesome 6.4.0

### Animasi

- **Scroll:** AOS (Animate On Scroll)
- **Transitions:** CSS transitions & transforms
- **Slideshow:** Custom JavaScript dengan interval 5 detik

## 🗄️ Database

### Tabel `pengaturan_sistem`

| ID  | Nama Pengaturan | Nilai                           | Kategori   | Penggunaan            |
| --- | --------------- | ------------------------------- | ---------- | --------------------- |
| 1   | `nama_gereja`   | `Gereja Kristen Jawa Randuares` | `umum`     | Title, Navbar, Footer |
| 8   | `logo_gereja`   | `logo.png`                      | `tampilan` | Logo di semua tempat  |
| NEW | `alamat_gereja` | `[alamat dari database]`        | `umum`     | Footer                |

## 🔧 Cara Update Konten

### 1. Logo Gereja

```sql
UPDATE pengaturan_sistem
SET nilai = 'logo_baru.png'
WHERE nama_pengaturan = 'logo_gereja';
```

### 2. Nama Gereja

```sql
UPDATE pengaturan_sistem
SET nilai = 'Nama Gereja Baru'
WHERE nama_pengaturan = 'nama_gereja';
```

### 3. Alamat Gereja

```sql
UPDATE pengaturan_sistem
SET nilai = 'Alamat Gereja Baru'
WHERE nama_pengaturan = 'alamat_gereja';
```

## 📱 Responsive Breakpoints

- **Mobile:** `< 768px`
- **Tablet:** `768px - 1024px`
- **Desktop:** `> 1024px`

## 🚀 Cara Menjalankan

1. **Pastikan XAMPP berjalan**
2. **Buka browser** ke `http://localhost/gereja/`
3. **Halaman utama** akan muncul dengan semua fitur

## 📝 Catatan Pengembangan

### ✅ Yang Sudah Selesai

- Halaman utama frontend dengan design modern
- Sistem logo dan nama gereja dinamis dari database
- Background slideshow dengan 5 gambar
- Navbar responsive dengan menu dinamis
- Footer dengan alamat dinamis
- CSS dan JavaScript terpisah ke file eksternal

### 🔄 Yang Akan Dibuat

- Panel admin login/logout
- CRUD untuk semua fitur utama
- Halaman detail untuk setiap fitur
- Sistem upload file untuk galeri
- Dashboard admin dengan statistik

## 🛠️ Teknologi yang Digunakan

- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **CSS Framework:** Tailwind CSS
- **Icons:** Font Awesome
- **Animations:** AOS (Animate On Scroll)
- **Alerts:** SweetAlert2
- **Backend:** PHP 7.4+
- **Database:** MySQL (XAMPP)
- **Database Layer:** PDO

## 👥 Kontributor

Dikembangkan untuk Gereja Kristen Jawa Randuares - Salatiga

---

**Status:** 🟡 Development in Progress  
**Versi:** 1.0.0  
**Update Terakhir:** Januari 2025
