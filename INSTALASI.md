# 🚀 Instalasi Sistem Gereja

## 📋 Persyaratan Sistem

- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Browser modern (Chrome, Firefox, Safari, Edge)

## ⚡ Langkah Instalasi Cepat

### 1. Download & Extract

- Download project ini
- Extract ke folder: `C:\xampp\htdocs\gereja`

### 2. Start XAMPP

- Buka XAMPP Control Panel
- Start **Apache** dan **MySQL**
- Pastikan keduanya berwarna hijau

### 3. Install Database

- Buka browser
- Akses: `http://localhost/gereja/install_database.php`
- Tunggu sampai muncul pesan "Instalasi Database Selesai!"

### 4. Akses Website

- **Website Utama**: `http://localhost/gereja`
- **Admin Panel**: `http://localhost/gereja/admin/login.php`

### 5. Login Admin

- **Username**: `admin`
- **Password**: `password`

## 🔧 Instalasi Manual (Jika Otomatis Gagal)

### 1. Buat Database

- Buka: `http://localhost/phpmyadmin`
- Klik "New" (Database Baru)
- Nama: `gereja_db`
- Collation: `utf8mb4_unicode_ci`
- Klik "Create"

### 2. Import SQL

- Pilih database `gereja_db`
- Klik tab "Import"
- Pilih file: `database/gereja_db.sql`
- Klik "Go"

### 3. Test Koneksi

- Edit file: `includes/config.php`
- Sesuaikan konfigurasi database jika perlu

## 🎯 Fitur yang Tersedia

### Frontend (Untuk Jemaat)

- ✅ Beranda dengan animasi modern
- ✅ Jadwal Ibadah dengan filter
- ✅ Warta Gereja
- ✅ Galeri Foto
- ✅ Renungan Harian
- ✅ Informasi Kegiatan

### Admin Panel

- ✅ Dashboard dengan statistik
- ✅ Manajemen Data Jemaat
- ✅ Kelola Jadwal Ibadah
- ✅ Sistem Keuangan & Persembahan
- ✅ Upload Warta & Galeri
- ✅ Manajemen Kegiatan

## 🚨 Troubleshooting

### Database Connection Error

```
❌ Koneksi database gagal
```

**Solusi:**

- Pastikan MySQL berjalan di XAMPP
- Cek username/password di `includes/config.php`
- Pastikan database `gereja_db` sudah dibuat

### File Upload Error

```
❌ Gagal upload file
```

**Solusi:**

- Cek permission folder upload
- Pastikan ukuran file < 5MB
- Cek tipe file yang diizinkan

### Halaman Blank/Error

```
❌ Halaman tidak tampil
```

**Solusi:**

- Cek error log Apache di XAMPP
- Pastikan PHP error reporting aktif
- Cek syntax error di file PHP

## 📱 Test Website

### 1. Test Frontend

- Buka: `http://localhost/gereja`
- Cek semua menu berfungsi
- Test responsive design di mobile

### 2. Test Admin

- Login: `http://localhost/gereja/admin/login.php`
- Username: `admin`, Password: `password`
- Cek dashboard dan menu admin

### 3. Test Database

- Buka phpMyAdmin
- Cek tabel sudah terbuat
- Cek data sample sudah masuk

## 🔐 Keamanan

### Default Credentials

- **Username**: `admin`
- **Password**: `password`

### ⚠️ PENTING: Ganti Password Default!

1. Login ke admin panel
2. Ganti password admin
3. Hapus file `install_database.php`
4. Set `error_reporting(0)` di production

## 📞 Support

Jika mengalami masalah:

1. Cek error log XAMPP
2. Pastikan semua persyaratan terpenuhi
3. Coba instalasi manual
4. Hubungi support jika masih bermasalah

## 🎉 Selamat!

Sistem Gereja berhasil terinstall! 🎊

Sekarang Anda bisa:

- Mengelola data jemaat
- Membuat jadwal ibadah
- Mengelola keuangan gereja
- Upload warta dan galeri
- Dan banyak lagi!

---

**Dibuat dengan ❤️ untuk kemajuan gereja digital**
