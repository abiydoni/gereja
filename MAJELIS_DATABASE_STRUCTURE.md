# 🏛️ Struktur Database Organisasi Majelis Gereja

## 📋 **Overview**

Dokumentasi ini menjelaskan struktur database yang telah dibuat untuk mengelola organisasi majelis gereja secara digital.

## 🗄️ **Struktur Tabel**

### 1. **majelis_jabatan** - Tabel Jabatan/Posisi

Struktur jabatan dalam organisasi majelis gereja.

| Field            | Type         | Description                  | Constraints               |
| ---------------- | ------------ | ---------------------------- | ------------------------- |
| `id`             | INT          | Primary Key                  | AUTO_INCREMENT            |
| `nama_jabatan`   | VARCHAR(100) | Nama jabatan                 | NOT NULL                  |
| `deskripsi`      | TEXT         | Deskripsi jabatan            | NULL                      |
| `level_hierarki` | INT          | Level hierarki (1=tertinggi) | DEFAULT 0                 |
| `urutan_tampil`  | INT          | Urutan tampil di frontend    | DEFAULT 0                 |
| `status_aktif`   | ENUM         | Status aktif/nonaktif        | DEFAULT 'aktif'           |
| `created_at`     | TIMESTAMP    | Waktu pembuatan              | DEFAULT CURRENT_TIMESTAMP |
| `updated_at`     | TIMESTAMP    | Waktu update                 | AUTO UPDATE               |

**Data Awal yang Sudah Ditambahkan:**

- Pendeta (Level 1)
- Ketua Majelis Jemaat (Level 2)
- Wakil Ketua Majelis Jemaat (Level 3)
- Sekretaris Majelis Jemaat (Level 4)
- Bendahara Majelis Jemaat (Level 5)
- Anggota Majelis Jemaat (Level 6)
- Ketua Komisi (Level 7)
- Anggota Komisi (Level 8)

### 2. **majelis_anggota** - Tabel Anggota Majelis

Data lengkap anggota majelis gereja.

| Field               | Type          | Description       | Constraints               |
| ------------------- | ------------- | ----------------- | ------------------------- |
| `id`                | INT           | Primary Key       | AUTO_INCREMENT            |
| `nama_lengkap`      | VARCHAR(150)  | Nama lengkap      | NOT NULL                  |
| `nama_panggilan`    | VARCHAR(50)   | Nama panggilan    | NULL                      |
| `tempat_lahir`      | VARCHAR(100)  | Tempat lahir      | NULL                      |
| `tanggal_lahir`     | DATE          | Tanggal lahir     | NULL                      |
| `jenis_kelamin`     | ENUM('L','P') | Jenis kelamin     | NOT NULL                  |
| `alamat`            | TEXT          | Alamat lengkap    | NULL                      |
| `no_telepon`        | VARCHAR(20)   | Nomor telepon     | NULL                      |
| `email`             | VARCHAR(100)  | Email             | NULL                      |
| `status_pernikahan` | ENUM          | Status pernikahan | DEFAULT 'belum_menikah'   |
| `tanggal_bergabung` | DATE          | Tanggal bergabung | NULL                      |
| `status_aktif`      | ENUM          | Status aktif      | DEFAULT 'aktif'           |
| `foto`              | VARCHAR(255)  | Path foto         | NULL                      |
| `created_at`        | TIMESTAMP     | Waktu pembuatan   | DEFAULT CURRENT_TIMESTAMP |
| `updated_at`        | TIMESTAMP     | Waktu update      | AUTO UPDATE               |

### 3. **majelis_struktur** - Tabel Struktur Organisasi

Relasi antara jabatan dan anggota dalam periode tertentu.

| Field             | Type      | Description             | Constraints               |
| ----------------- | --------- | ----------------------- | ------------------------- |
| `id`              | INT       | Primary Key             | AUTO_INCREMENT            |
| `jabatan_id`      | INT       | ID jabatan              | FOREIGN KEY               |
| `anggota_id`      | INT       | ID anggota              | FOREIGN KEY               |
| `periode_mulai`   | DATE      | Tanggal mulai periode   | NOT NULL                  |
| `periode_selesai` | DATE      | Tanggal selesai periode | NULL                      |
| `status`          | ENUM      | Status jabatan          | DEFAULT 'aktif'           |
| `catatan`         | TEXT      | Catatan tambahan        | NULL                      |
| `created_at`      | TIMESTAMP | Waktu pembuatan         | DEFAULT CURRENT_TIMESTAMP |
| `updated_at`      | TIMESTAMP | Waktu update            | AUTO UPDATE               |

**Constraints:**

- `FOREIGN KEY (jabatan_id)` → `majelis_jabatan(id)`
- `FOREIGN KEY (anggota_id)` → `majelis_anggota(id)`
- `UNIQUE KEY (jabatan_id, periode_mulai)`

### 4. **majelis_komisi** - Tabel Komisi/Bidang Pelayanan

Komisi-komisi pelayanan dalam gereja.

| Field            | Type         | Description      | Constraints               |
| ---------------- | ------------ | ---------------- | ------------------------- |
| `id`             | INT          | Primary Key      | AUTO_INCREMENT            |
| `nama_komisi`    | VARCHAR(100) | Nama komisi      | NOT NULL                  |
| `deskripsi`      | TEXT         | Deskripsi komisi | NULL                      |
| `ketua_id`       | INT          | ID ketua komisi  | FOREIGN KEY               |
| `wakil_ketua_id` | INT          | ID wakil ketua   | FOREIGN KEY               |
| `sekretaris_id`  | INT          | ID sekretaris    | FOREIGN KEY               |
| `bendahara_id`   | INT          | ID bendahara     | FOREIGN KEY               |
| `status_aktif`   | ENUM         | Status aktif     | DEFAULT 'aktif'           |
| `created_at`     | TIMESTAMP    | Waktu pembuatan  | DEFAULT CURRENT_TIMESTAMP |
| `updated_at`     | TIMESTAMP    | Waktu update     | AUTO UPDATE               |

**Data Awal yang Sudah Ditambahkan:**

- Komisi Pelayanan
- Komisi Koinonia
- Komisi Marturia
- Komisi Diakonia
- Komisi Musik dan Liturgi
- Komisi Pemuda dan Remaja
- Komisi Anak
- Komisi Wanita

**Constraints:**

- `FOREIGN KEY (ketua_id)` → `majelis_anggota(id)`
- `FOREIGN KEY (wakil_ketua_id)` → `majelis_anggota(id)`
- `FOREIGN KEY (sekretaris_id)` → `majelis_anggota(id)`
- `FOREIGN KEY (bendahara_id)` → `majelis_anggota(id)`

### 5. **majelis_anggota_komisi** - Tabel Anggota Komisi

Relasi antara komisi dan anggota dengan peran tertentu.

| Field             | Type         | Description        | Constraints               |
| ----------------- | ------------ | ------------------ | ------------------------- |
| `id`              | INT          | Primary Key        | AUTO_INCREMENT            |
| `komisi_id`       | INT          | ID komisi          | FOREIGN KEY               |
| `anggota_id`      | INT          | ID anggota         | FOREIGN KEY               |
| `peran`           | VARCHAR(100) | Peran dalam komisi | NULL                      |
| `periode_mulai`   | DATE         | Tanggal mulai      | NULL                      |
| `periode_selesai` | DATE         | Tanggal selesai    | NULL                      |
| `status`          | ENUM         | Status aktif       | DEFAULT 'aktif'           |
| `created_at`      | TIMESTAMP    | Waktu pembuatan    | DEFAULT CURRENT_TIMESTAMP |
| `updated_at`      | TIMESTAMP    | Waktu update       | AUTO UPDATE               |

**Constraints:**

- `FOREIGN KEY (komisi_id)` → `majelis_komisi(id)`
- `FOREIGN KEY (anggota_id)` → `majelis_anggota(id)`
- `UNIQUE KEY (komisi_id, anggota_id, periode_mulai)`

### 6. **majelis_periode** - Tabel Periode Kepengurusan

Periode kepengurusan majelis gereja.

| Field             | Type         | Description       | Constraints               |
| ----------------- | ------------ | ----------------- | ------------------------- |
| `id`              | INT          | Primary Key       | AUTO_INCREMENT            |
| `nama_periode`    | VARCHAR(100) | Nama periode      | NOT NULL                  |
| `tahun_mulai`     | INT          | Tahun mulai       | NOT NULL                  |
| `tahun_selesai`   | INT          | Tahun selesai     | NOT NULL                  |
| `tanggal_mulai`   | DATE         | Tanggal mulai     | NULL                      |
| `tanggal_selesai` | DATE         | Tanggal selesai   | NULL                      |
| `status`          | ENUM         | Status periode    | DEFAULT 'akan_datang'     |
| `deskripsi`       | TEXT         | Deskripsi periode | NULL                      |
| `created_at`      | TIMESTAMP    | Waktu pembuatan   | DEFAULT CURRENT_TIMESTAMP |
| `updated_at`      | TIMESTAMP    | Waktu update      | AUTO UPDATE               |

**Data Awal yang Sudah Ditambahkan:**

- Periode 2025 (Status: Aktif)

### 7. **majelis_riwayat_jabatan** - Tabel Riwayat Jabatan

Riwayat lengkap jabatan yang pernah diemban anggota.

| Field             | Type      | Description     | Constraints               |
| ----------------- | --------- | --------------- | ------------------------- |
| `id`              | INT       | Primary Key     | AUTO_INCREMENT            |
| `anggota_id`      | INT       | ID anggota      | FOREIGN KEY               |
| `jabatan_id`      | INT       | ID jabatan      | FOREIGN KEY               |
| `periode_id`      | INT       | ID periode      | FOREIGN KEY               |
| `tanggal_mulai`   | DATE      | Tanggal mulai   | NOT NULL                  |
| `tanggal_selesai` | DATE      | Tanggal selesai | NULL                      |
| `alasan_berhenti` | TEXT      | Alasan berhenti | NULL                      |
| `status`          | ENUM      | Status jabatan  | DEFAULT 'aktif'           |
| `created_at`      | TIMESTAMP | Waktu pembuatan | DEFAULT CURRENT_TIMESTAMP |
| `updated_at`      | TIMESTAMP | Waktu update    | AUTO UPDATE               |

**Constraints:**

- `FOREIGN KEY (anggota_id)` → `majelis_anggota(id)`
- `FOREIGN KEY (jabatan_id)` → `majelis_jabatan(id)`
- `FOREIGN KEY (periode_id)` → `majelis_periode(id)`

## 🔗 **Relasi Antar Tabel**

```
majelis_jabatan (1) ←→ (N) majelis_struktur ←→ (1) majelis_anggota
majelis_jabatan (1) ←→ (N) majelis_riwayat_jabatan ←→ (1) majelis_anggota
majelis_periode (1) ←→ (N) majelis_struktur
majelis_periode (1) ←→ (N) majelis_riwayat_jabatan
majelis_komisi (1) ←→ (N) majelis_anggota_komisi ←→ (1) majelis_anggota
```

## 📊 **Status Saat Ini**

✅ **Tabel**: 7 tabel berhasil dibuat  
✅ **Jabatan**: 8 jabatan dasar ditambahkan  
✅ **Komisi**: 8 komisi pelayanan ditambahkan  
✅ **Periode**: Periode 2025 aktif  
✅ **Relasi**: Foreign keys berhasil dikonfigurasi  
✅ **Data**: Siap untuk pengelolaan struktur organisasi

## 🚀 **Langkah Selanjutnya**

### 1. **Halaman Admin** 🌐

- Buat CRUD untuk mengelola anggota majelis
- Buat CRUD untuk mengelola struktur organisasi
- Buat CRUD untuk mengelola komisi pelayanan
- Buat CRUD untuk mengelola periode kepengurusan

### 2. **Data Anggota** 👥

- Tambahkan data anggota majelis yang sebenarnya
- Upload foto anggota (opsional)
- Isi informasi lengkap anggota

### 3. **Struktur Organisasi** 🏗️

- Buat struktur organisasi sesuai periode 2025
- Assign jabatan ke anggota yang sesuai
- Buat struktur komisi pelayanan

### 4. **Frontend Display** 🎨

- Buat halaman struktur organisasi
- Tampilkan organigram yang menarik
- Buat halaman detail komisi pelayanan

### 5. **Responsive Design** 📱

- Pastikan tampilan responsive di semua device
- Optimasi untuk mobile dan tablet
- Test user experience di berbagai browser

## 💡 **Fitur yang Bisa Ditambahkan**

### **Advanced Features:**

- **Dashboard Analytics**: Statistik anggota, komisi, periode
- **Export Data**: Export ke PDF, Excel, atau CSV
- **Search & Filter**: Pencarian dan filter data
- **Audit Trail**: Log perubahan data
- **Backup & Restore**: Backup database otomatis

### **User Management:**

- **Role-based Access**: Admin, Editor, Viewer
- **User Authentication**: Login/logout system
- **Permission Control**: Kontrol akses berdasarkan role

### **Integration:**

- **Email Notifications**: Notifikasi perubahan struktur
- **Calendar Integration**: Integrasi dengan kalender gereja
- **Mobile App**: Aplikasi mobile untuk akses cepat

## 🔧 **Maintenance & Monitoring**

### **Regular Tasks:**

- Backup database secara berkala
- Monitor performance database
- Update data anggota secara rutin
- Review dan update struktur organisasi

### **Security:**

- Validasi input data
- Sanitasi data sebelum disimpan
- Log aktivitas admin
- Backup dan recovery plan

---

**📅 Dibuat pada:** <?php echo date('d F Y H:i:s'); ?>  
**👨‍💻 Dibuat oleh:** AI Assistant  
**🏛️ Untuk:** Gereja Kristen Jawa  
**📋 Versi:** 1.0  
**✅ Status:** Production Ready
