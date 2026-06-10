# 📋 Product Requirements Document (PRD)
# SIA Universitas Suzuran — Sistem Informasi Akademik Universitas

---

## 1. Ringkasan Eksekutif

**Nama Produk:** SIA Universitas Suzuran  
**Versi:** 1.0.0  
**Tanggal Dokumen:** 10 Juni 2026  
**Status:** In Development  

SIA Universitas Suzuran adalah Sistem Informasi Akademik berbasis web yang dirancang untuk mengelola seluruh proses akademik di lingkungan Universitas Suzuran. Sistem ini mencakup manajemen data kelembagaan (fakultas, program studi), data civitas akademika (dosen, mahasiswa), manajemen perkuliahan (mata kuliah, kelas, jadwal), serta pengelolaan nilai akademik (KRS dan KHS) — semuanya terintegrasi dalam satu platform yang dapat diakses oleh tiga jenis pengguna: Admin, Dosen, dan Mahasiswa.

---

## 2. Latar Belakang & Tujuan Bisnis

### 2.1 Latar Belakang

Pengelolaan data akademik universitas yang masih bersifat manual atau terpisah-pisah pada berbagai sistem yang tidak terintegrasi menyebabkan inefisiensi, potensi kesalahan data, dan hambatan aksesibilitas bagi civitas akademika. SIA Universitas Suzuran hadir sebagai solusi terpusat yang mengotomasi dan mendigitalisasi seluruh alur proses akademik.

### 2.2 Tujuan Bisnis

1. Menyediakan satu platform terpadu untuk seluruh kebutuhan pengelolaan akademik universitas.
2. Mempersingkat alur pengisian KRS dan input nilai KHS.
3. Memberikan akses real-time kepada mahasiswa untuk memantau jadwal kuliah dan hasil studi.
4. Memberikan dosen akses langsung untuk melihat kelas yang diampu dan menginput nilai.
5. Menjamin keamanan dan integritas data melalui sistem autentikasi dan otorisasi berbasis peran (RBAC).

---

## 3. Pemangku Kepentingan (Stakeholders)

| Peran | Deskripsi |
|---|---|
| **Admin** | Administrator universitas, memiliki akses penuh ke seluruh fitur manajemen data dan pengaturan sistem. |
| **Dosen** | Tenaga pengajar, memiliki akses ke Portal Dosen aktif untuk melihat kelas yang diampu dan menginput nilai mahasiswa. |
| **Mahasiswa** | Peserta didik, memiliki akses untuk melihat jadwal kuliah, melakukan registrasi kelas (KRS), dan melihat hasil studi (KHS). |

---

## 4. Pengguna & Persona

### 4.1 Admin Akademik
- **Tugas Utama:** Mengelola seluruh data master universitas (fakultas, prodi, dosen, mahasiswa, mata kuliah, kelas kuliah), mengaktifkan tahun akademik, dan memantau statistik akademik secara menyeluruh.
- **Kebutuhan:** Antarmuka CRUD yang efisien, fitur pencarian cepat, dan tampilan statistik ringkas di dashboard.

### 4.2 Dosen Pengampu
- **Tugas Utama:** Melihat daftar kelas aktif yang diampu, melihat mahasiswa terdaftar di kelasnya, dan menginput nilai akhir serta nilai huruf.
- **Kebutuhan:** Portal dosen yang terfokus, formulir input nilai yang mudah, dan daftar mahasiswa bimbingan akademik (PA).

### 4.3 Mahasiswa
- **Tugas Utama:** Mengambil/mendaftarkan kelas (KRS), melihat jadwal kuliah mingguan, dan memantau transkrip nilai (KHS) beserta IPK/IPS.
- **Kebutuhan:** Tampilan KRS yang jelas, jadwal kuliah yang mudah dibaca, dan ringkasan prestasi akademik (IPK, IPS, SKS Lulus).

---

## 5. Ruang Lingkup Fitur

### 5.1 Modul Autentikasi & Manajemen Akun

| ID Fitur | Deskripsi | Role |
|---|---|---|
| AUTH-01 | Login dengan email & password menggunakan token Sanctum (Bearer Token) | Semua |
| AUTH-02 | Logout dan invalidasi token sesi | Semua |
| AUTH-03 | Pemeriksaan sesi aktif (auto-restore dari localStorage) | Semua |
| AUTH-04 | Update profil: email, nomor telepon, password | Semua |
| AUTH-05 | Upload foto profil (jpeg/png/jpg/webp, maks. 2MB) | Semua |
| AUTH-06 | Redirect tab awal berdasarkan peran setelah login | Semua |

### 5.2 Modul Dashboard

| ID Fitur | Deskripsi | Role |
|---|---|---|
| DASH-01 | Widget statistik: total fakultas, prodi, dosen, mahasiswa | Admin, Dosen |
| DASH-02 | Widget akademik mahasiswa: IPK, IPS, SKS Lulus, SKS Diambil | Mahasiswa |
| DASH-03 | Tampilan semester aktif yang sedang berjalan | Semua |
| DASH-04 | Statistik total kelas kuliah dan penugasan pengampu | Admin, Dosen |
| DASH-05 | Shortcut navigasi ke Portal Dosen | Admin, Dosen |

### 5.3 Modul Manajemen Fakultas

| ID Fitur | Deskripsi | Role |
|---|---|---|
| FAK-01 | Lihat daftar seluruh fakultas | Semua |
| FAK-02 | Tambah fakultas baru (kode unik, nama) | Admin |
| FAK-03 | Edit data fakultas | Admin |
| FAK-04 | Hapus fakultas (soft delete) | Admin |
| FAK-05 | Pencarian/filter fakultas berdasarkan nama atau kode | Admin |

### 5.4 Modul Manajemen Program Studi (Prodi)

| ID Fitur | Deskripsi | Role |
|---|---|---|
| PRODI-01 | Lihat daftar program studi beserta asosiasi fakultas | Semua |
| PRODI-02 | Tambah program studi (kode unik, nama, jenjang: D3/S1/S2/S3, prefix NIM, relasi ke fakultas) | Admin |
| PRODI-03 | Edit data program studi | Admin |
| PRODI-04 | Hapus program studi (soft delete) | Admin |

### 5.5 Modul Manajemen Tahun Akademik

| ID Fitur | Deskripsi | Role |
|---|---|---|
| TA-01 | Lihat daftar tahun akademik beserta status aktif | Semua |
| TA-02 | Tambah tahun akademik (kode, nama) | Admin |
| TA-03 | Edit tahun akademik | Admin |
| TA-04 | Hapus tahun akademik (soft delete) | Admin |
| TA-05 | Toggle aktivasi semester: menetapkan satu semester sebagai "AKTIF" | Admin |

### 5.6 Modul Manajemen Data Dosen

| ID Fitur | Deskripsi | Role |
|---|---|---|
| DOS-01 | Lihat daftar dosen beserta NIDN dan kelas yang diampu | Admin |
| DOS-02 | Tambah data dosen (NIDN, nama, relasi ke akun user, foto) | Admin |
| DOS-03 | Edit data dosen termasuk unggah ulang foto | Admin |
| DOS-04 | Hapus data dosen (soft delete + hapus file foto) | Admin |
| DOS-05 | Lihat detail dosen: kelas aktif yang diampu dan mahasiswa bimbingan PA | Admin |

### 5.7 Modul Manajemen Data Mahasiswa

| ID Fitur | Deskripsi | Role |
|---|---|---|
| MHS-01 | Lihat daftar mahasiswa beserta NIM, prodi, status, dan dosen PA | Admin |
| MHS-02 | Tambah mahasiswa (NIM otomatis, nama, prodi, dosen PA, tahun masuk, status, foto) | Admin |
| MHS-03 | Edit data mahasiswa termasuk unggah ulang foto | Admin |
| MHS-04 | Hapus mahasiswa (soft delete + hapus file foto) | Admin |
| MHS-05 | Status mahasiswa: AKTIF, CUTI, LULUS, DO | Admin |
| MHS-06 | Lihat transkrip nilai (KHS) mahasiswa | Admin |

### 5.8 Modul Manajemen Mata Kuliah

| ID Fitur | Deskripsi | Role |
|---|---|---|
| MK-01 | Lihat daftar mata kuliah beserta kode, SKS, dan semester plot | Semua |
| MK-02 | Tambah mata kuliah (kode unik, nama, SKS, semester plot, relasi ke prodi) | Admin |
| MK-03 | Edit mata kuliah | Admin |
| MK-04 | Hapus mata kuliah (soft delete) | Admin |

### 5.9 Modul Manajemen Kelas Kuliah

| ID Fitur | Deskripsi | Role |
|---|---|---|
| KK-01 | Lihat daftar kelas kuliah beserta mata kuliah, semester, jadwal, dan ruangan | Admin |
| KK-02 | Tambah kelas kuliah (kode kelas, nama kelas, hari, jam mulai-selesai, ruangan, relasi MK & TA) | Admin |
| KK-03 | Edit kelas kuliah | Admin |
| KK-04 | Hapus kelas kuliah (soft delete) | Admin |
| KK-05 | Penugasan dosen pengampu ke kelas (multi-dosen per kelas, melalui tabel pivot `dosen_pengampus`) | Admin |
| KK-06 | Lihat mahasiswa yang terdaftar di suatu kelas | Admin |

### 5.10 Modul KRS & KHS (Kelas Mahasiswa)

| ID Fitur | Deskripsi | Role |
|---|---|---|
| KRS-01 | Lihat daftar KRS mahasiswa (semua kelas yang diambil) beserta nilai | Admin, Mahasiswa |
| KRS-02 | Daftar kelas (registrasi KRS): mahasiswa mendaftarkan diri ke kelas tertentu | Mahasiswa |
| KRS-03 | Admin mendaftarkan mahasiswa ke kelas manapun | Admin |
| KRS-04 | Hapus entri KRS (dropout dari kelas) | Admin |
| KRS-05 | Lihat nilai akhir (angka 0–100) dan nilai huruf (A/B/C/D/E) | Admin, Mahasiswa, Dosen |
| KRS-06 | Input/edit nilai akhir dan nilai huruf mahasiswa | Admin, Dosen |

### 5.11 Modul Portal Dosen Aktif

| ID Fitur | Deskripsi | Role |
|---|---|---|
| PD-01 | Pilih dosen yang ingin dilihat portalnya (admin dapat memilih dosen manapun) | Admin |
| PD-02 | Auto-select dosen yang login untuk portal pribadinya | Dosen |
| PD-03 | Lihat daftar kelas aktif (semester aktif) yang diampu dosen terpilih | Admin, Dosen |
| PD-04 | Pilih kelas untuk melihat daftar mahasiswa terdaftar beserta nilai | Admin, Dosen |
| PD-05 | Input nilai akhir (numerik) dan nilai huruf per mahasiswa di kelas | Admin, Dosen |
| PD-06 | Simpan nilai per-mahasiswa secara individual | Admin, Dosen |
| PD-07 | Lihat daftar mahasiswa bimbingan akademik (PA) | Admin, Dosen |

### 5.12 Modul Jadwal Kuliah Mahasiswa

| ID Fitur | Deskripsi | Role |
|---|---|---|
| JDW-01 | Tampilan jadwal kuliah mingguan mahasiswa berdasarkan KRS aktif | Mahasiswa |
| JDW-02 | Informasi per kelas: nama MK, hari, jam, ruangan, dosen pengampu | Mahasiswa |
| JDW-03 | Filter berdasarkan semester aktif | Mahasiswa |

### 5.13 Modul Profil Pengguna

| ID Fitur | Deskripsi | Role |
|---|---|---|
| PROF-01 | Halaman profil mahasiswa: data akademik, foto, prodi, dosen PA | Mahasiswa |
| PROF-02 | Halaman profil dosen: data NIDN, kelas aktif, mahasiswa bimbingan | Dosen |
| PROF-03 | Halaman profil admin: statistik sistem dan data akun | Admin |
| PROF-04 | Update email, telepon, dan password dari halaman profil | Semua |
| PROF-05 | Upload dan ganti foto profil | Semua |

---

## 6. Alur Pengguna (User Flows)

### 6.1 Alur Login
```
Pengguna membuka aplikasi
  → Halaman Login (email + password)
  → [Berhasil] Token disimpan di localStorage
  → Redirect ke tab awal sesuai peran:
      Admin   → Dashboard
      Dosen   → Portal Dosen Aktif
      Mahasiswa → KRS & Nilai KHS
```

### 6.2 Alur Registrasi KRS Mahasiswa
```
Mahasiswa login
  → Buka tab "KRS & Nilai KHS"
  → Klik tombol "Ambil Kelas"
  → Pilih kelas dari daftar yang tersedia
  → Submit → Data tersimpan di tabel kelas_mahasiswas
  → Kelas muncul di daftar KRS
```

### 6.3 Alur Input Nilai Dosen
```
Dosen login
  → Auto-redirect ke "Portal Dosen Aktif"
  → Daftar kelas aktif yang diampu tampil otomatis
  → Pilih kelas → Daftar mahasiswa terdaftar tampil
  → Isi nilai_akhir (0–100) dan nilai_huruf (A/B/C/D/E)
  → Klik "Simpan Nilai" per mahasiswa
  → Nilai tersimpan → Notifikasi sukses
```

### 6.4 Alur Admin Mengelola Data Master
```
Admin login → Dashboard
  → Navigasi ke tab data (Dosen / Mahasiswa / Kelas, dll.)
  → Klik tombol "Tambah" → Modal form muncul
  → Isi data → Validasi backend
  → Berhasil: data muncul di tabel
  Atau
  → Klik ikon "Edit" / "Hapus" pada baris data
  → Konfirmasi hapus → Data di-soft-delete
```

---

## 7. Persyaratan Non-Fungsional

### 7.1 Keamanan
- **Autentikasi:** Laravel Sanctum (API Token).
- **Otorisasi:** Role-Based Access Control (RBAC) menggunakan Spatie Laravel Permission (peran: `admin`, `dosen`, `mahasiswa`).
- **Validasi Input:** Semua input divalidasi di sisi server via Laravel FormRequest.
- **File Upload:** Foto dikunci tipe MIME (jpeg/png/jpg/webp) dan ukuran maksimum 2 MB.
- **Soft Delete:** Data tidak dihapus permanen dari database, hanya ditandai `deleted_at`.

### 7.2 Performa
- Pengambilan data dilakukan secara paralel (`Promise.all`) untuk efisiensi waktu muat.
- Indikator loading ditampilkan selama proses pengambilan data berlangsung.

### 7.3 Ketersediaan
- Sistem dirancang untuk berjalan sebagai aplikasi web yang dapat diakses dari browser modern.
- Data persisten di database relasional (MySQL/PostgreSQL).

### 7.4 Usabilitas
- Antarmuka responsif dengan layout sidebar + konten utama.
- Pencarian teks real-time di setiap modul daftar data.
- Modal form dinamis yang reusable untuk semua operasi CRUD.
- Konfirmasi dialog sebelum aksi penghapusan.
- Pesan error validasi ditampilkan langsung di formulir.

---

## 8. Arsitektur Teknis

### 8.1 Stack Teknologi

| Lapisan | Teknologi |
|---|---|
| **Backend Framework** | Laravel 12 (PHP 8.2+) |
| **Autentikasi API** | Laravel Sanctum 4.x |
| **Otorisasi / RBAC** | Spatie Laravel Permission 6.x |
| **Frontend Framework** | React 19 (JSX) |
| **Build Tool** | Vite 7 + Laravel Vite Plugin |
| **UI Library** | Lucide React (icon set) |
| **Styling** | Tailwind CSS 4.x |
| **HTTP Client** | Fetch API (native browser) |
| **Database** | MySQL / SQLite (via Laravel Eloquent ORM) |
| **File Storage** | Laravel Storage (`public` disk) |

### 8.2 Arsitektur Backend

```
routes/api.php
    ↓
Controllers (Http/Controllers/)
    ↓
Services (App/Services/)        ← Business logic
    ↓
Repositories (App/Repositories/) ← Data access layer
    ↓
Models (App/Models/)            ← Eloquent ORM
    ↓
Database (Migrations + Seeders)
```

Pattern yang digunakan: **Repository-Service Pattern** memisahkan lapisan akses data (Repository) dari logika bisnis (Service) dan controller HTTP.

### 8.3 Arsitektur Frontend

```
resources/js/app.jsx            ← Entry point
    ↓
components/App.jsx              ← Root state management & routing
    ├── Login.jsx               ← Halaman autentikasi
    ├── Sidebar.jsx             ← Navigasi berbasis peran
    ├── Header.jsx              ← Bar atas dengan info semester & refresh
    ├── DashboardTab.jsx
    ├── FakultasTab.jsx
    ├── ProdiTab.jsx
    ├── TahunAkademikTab.jsx
    ├── DosenTab.jsx
    ├── MahasiswaTab.jsx
    ├── MataKuliahTab.jsx
    ├── KelasKuliahTab.jsx
    ├── KelasMahasiswaTab.jsx
    ├── LecturerPortalTab.jsx
    ├── JadwalKuliahTab.jsx
    ├── ProfilMahasiswaTab.jsx
    ├── ProfilDosenTab.jsx
    ├── ProfilAdminTab.jsx
    └── DynamicFormModal.jsx    ← Reusable CRUD form modal
```

State management dilakukan secara terpusat di `App.jsx` menggunakan React hooks (`useState`, `useEffect`).

---

## 9. Model Data & Skema Database

### 9.1 Entitas Utama

| Tabel | Kolom Kunci | Keterangan |
|---|---|---|
| `users` | `id`, `name`, `email`, `password`, `phone`, `photo` | Akun login seluruh pengguna |
| `roles` / `permissions` | (Spatie) | Manajemen peran RBAC |
| `fakultas` | `id`, `kode_fakultas` (unique), `nama_fakultas` | Data fakultas |
| `prodis` | `id`, `id_fakultas`, `kode_prodi` (unique), `nama_prodi`, `jenjang` (D3/S1/S2/S3), `prefix_nim` | Program studi |
| `tahun_akademiks` | `id`, `kode_ta`, `nama_ta`, `status` (boolean, hanya satu AKTIF) | Tahun/semester akademik |
| `dosens` | `id`, `id_user`, `nidn`, `nama`, `foto` | Data profil dosen |
| `mahasiswas` | `id`, `id_user`, `nim` (unique), `nama`, `id_prodi`, `id_dosen_pa`, `tahun_masuk`, `status_mahasiswa` (AKTIF/CUTI/LULUS/DO), `foto` | Data profil mahasiswa |
| `mata_kuliahs` | `id`, `id_prodi`, `kode_mk` (unique), `nama_mk`, `sks`, `semester_plot` | Katalog mata kuliah |
| `kelas_kuliahs` | `id`, `kode_kelas` (unique), `id_mk`, `id_ta`, `nama_kelas`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan` | Sesi kelas perkuliahan |
| `dosen_pengampus` | `id`, `id_kelas`, `id_dosen` | Pivot: penugasan dosen ke kelas (many-to-many) |
| `kelas_mahasiswas` | `id`, `id_mahasiswa`, `id_kelas`, `nilai_akhir` (float, 0-100), `nilai_huruf` (char 1) | Pivot: KRS & KHS mahasiswa |

### 9.2 Relasi Antar Entitas

```
Fakultas ──(1:N)──> Prodi ──(1:N)──> MataKuliah ──(1:N)──> KelasKuliah
                      │                                          │
                      └──(1:N)──> Mahasiswa ──(N:M via kelas_mahasiswas)
                                      │
                                Dosen ──(N:M via dosen_pengampus)──> KelasKuliah
                                  │
                          TahunAkademik ──(1:N)──> KelasKuliah

User ──(1:1)──> Mahasiswa atau Dosen
```

Semua tabel utama mengimplementasikan **Soft Deletes** (kolom `deleted_at`) untuk keamanan data.

---

## 10. Antarmuka API (REST)

### 10.1 Endpoint Publik

| Method | Endpoint | Deskripsi |
|---|---|---|
| `POST` | `/api/login` | Login dan mendapatkan token Bearer |

### 10.2 Endpoint Terautentikasi (Semua Role)

| Method | Endpoint | Deskripsi |
|---|---|---|
| `POST` | `/api/logout` | Logout sesi |
| `GET` | `/api/user` | Data pengguna yang sedang login |
| `POST` | `/api/profile/update` | Update profil & foto pengguna |
| `GET` | `/api/fakultas` | Daftar semua fakultas |
| `GET` | `/api/prodis` | Daftar semua program studi |
| `GET` | `/api/tahun-akademiks` | Daftar semua tahun akademik |
| `GET` | `/api/dosens` | Daftar semua dosen |
| `GET` | `/api/mahasiswas` | Daftar semua mahasiswa |
| `GET` | `/api/mata-kuliahs` | Daftar semua mata kuliah |
| `GET` | `/api/kelas-kuliahs` | Daftar semua kelas kuliah |
| `GET` | `/api/dosen-pengampus` | Daftar semua penugasan pengampu |
| `GET` | `/api/kelas-mahasiswas` | Daftar semua entri KRS/KHS |
| `POST` | `/api/kelas-mahasiswas` | Registrasi KRS (mahasiswa/admin) |
| `GET` | `/api/dosens/{id}/kelas-kuliah-aktif` | Kelas aktif dosen |
| `GET` | `/api/dosens/{id}/mahasiswa-bimbingan` | Mahasiswa PA dosen |

### 10.3 Endpoint Admin Only (`role:admin`)

Write operations (POST, PUT, DELETE) untuk semua resource: `fakultas`, `users`, `dosens`, `mahasiswas`, `prodis`, `mata-kuliahs`, `tahun-akademiks`, `kelas-kuliahs`, `dosen-pengampus`, dan DELETE `kelas-mahasiswas`.

### 10.4 Endpoint Admin + Dosen (`role:admin|dosen`)

| Method | Endpoint | Deskripsi |
|---|---|---|
| `PUT` | `/api/kelas-mahasiswas/{id}` | Update nilai mahasiswa |

---

## 11. Aturan Bisnis

1. **Satu Semester Aktif:** Hanya satu `tahun_akademik` boleh berstatus `status = true` pada satu waktu. Saat admin mengaktifkan semester baru, sistem otomatis menonaktifkan yang lama.

2. **NIM Otomatis:** NIM mahasiswa dibuat otomatis oleh sistem berdasarkan `prefix_nim` dari prodi dan tahun masuk (via `MahasiswaHelper::generateUniqueNim()`).

3. **Otorisasi KRS:**
   - Mahasiswa hanya dapat mendaftarkan kelas atas nama dirinya sendiri.
   - Mahasiswa tidak dapat mengubah (PUT) entri KRS yang sudah ada.
   - Dosen hanya dapat mengubah nilai untuk kelas yang secara eksplisit ia ditugaskan (via tabel `dosen_pengampus`).

4. **Nilai:** Nilai huruf maksimal 1 karakter (A/B/C/D/E). Nilai angka berkisar antara 0–100. Keduanya bersifat opsional (nullable).

5. **Normalisasi Input:** Nama mahasiswa, status, kode kelas, nama kelas, hari, dan ruangan secara otomatis dikonversi menjadi KAPITAL di sisi server sebelum disimpan.

6. **Foto Profil:** File lama secara otomatis dihapus dari storage saat foto diperbarui atau pengguna dihapus.

7. **Soft Delete:** Penghapusan data tidak menghapus rekaman secara permanen dari database; data hanya diberi timestamp `deleted_at` dan disembunyikan dari query normal.

---

## 12. Batasan & Asumsi

### 12.1 Batasan (Constraints)
- Sistem tidak mendukung multi-tenant (satu instance = satu universitas).
- Tidak ada modul pembayaran atau keuangan mahasiswa.
- Tidak ada fitur notifikasi push/email otomatis.
- Registrasi akun pengguna baru hanya dapat dilakukan oleh Admin (tidak ada self-registration publik yang aktif).

### 12.2 Asumsi
- Setiap dosen dan mahasiswa wajib memiliki satu akun user (`users`) yang terhubung.
- Sistem database yang digunakan kompatibel dengan Laravel Eloquent ORM (MySQL atau SQLite).
- Server mendukung PHP 8.2+ dan Node.js untuk proses build frontend.

---

## 13. Kriteria Penerimaan (Acceptance Criteria)

| Modul | Kriteria |
|---|---|
| Login | Token diterima, user data dengan roles ter-load, redirect sesuai peran. |
| Dashboard | Statistik tampil sesuai peran; IPK/IPS dihitung dengan benar untuk mahasiswa. |
| CRUD Data Master | Create, Read, Update, Delete berfungsi dengan validasi error yang informatif. |
| KRS | Mahasiswa dapat mendaftarkan kelas; entri muncul di daftar KRS. |
| Input Nilai | Dosen dapat menyimpan nilai_akhir dan nilai_huruf; perubahan tersimpan ke database. |
| Soft Delete | Data yang dihapus tidak muncul di daftar tetapi tetap ada di database. |
| Upload Foto | File tersimpan di `storage/app/public/foto-*`; file lama terhapus saat diperbarui. |
| RBAC | Admin tidak dapat mengakses resource yang tidak diizinkan; demikian sebaliknya. |

---

## 14. Riwayat Perubahan Dokumen

| Versi | Tanggal | Deskripsi |
|---|---|---|
| 1.0.0 | 10 Juni 2026 | Pembuatan dokumen PRD awal berdasarkan analisis kode sumber proyek. |
