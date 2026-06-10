# Pemisahan Arsitektur Frontend dan Backend

Tujuan dari perubahan ini adalah untuk mengubah aplikasi dari bentuk monolithic (di mana Laravel merender tampilan dan menyajikan frontend) menjadi arsitektur **Headless API**. Dalam arsitektur ini, backend Laravel hanya bertugas melayani API request (mengembalikan data JSON), sementara frontend berjalan sebagai aplikasi Single Page Application (SPA) mandiri menggunakan React + Vite.

## > [!IMPORTANT]
> **Persetujuan Pengguna Diperlukan**
> Tolong tinjau rencana di bawah ini. Setelah pemisahan dilakukan, untuk menjalankan aplikasi ini Anda benar-benar harus menjalankan `php artisan serve` di folder root dan `npm run dev` di dalam folder `frontend`. 

## Langkah-Langkah Implementasi

### 1. Inisialisasi Standalone Frontend
- Membuat folder baru bernama `frontend` di dalam root project.
- Menginisialisasi proyek **Vite + React** yang 100% mandiri di dalam folder tersebut.
- Mengatur konfigurasi `package.json` untuk frontend, mencakup instalasi library pendukung (`react`, `react-dom`, `tailwindcss`, `lucide-react`, `axios`).

### 2. Memigrasi Kode Tampilan
- Memindahkan semua komponen React dari folder `resources/js` ke `frontend/src`.
- Memindahkan file stylesheet dari `resources/css/app.css` ke `frontend/src/index.css`.
- Menyesuaikan *entry point* React (`main.jsx`) dan `index.html` agar selaras dengan standar Vite.

### 3. Mengonfigurasi Proxy API
- Mengedit `frontend/vite.config.js` untuk menerapkan **Proxy**. Ini penting agar API calls yang sebelumnya menggunakan path absolut seperti `/api/user` dan pemuatan foto di `/storage/...` dapat diteruskan langsung ke server Laravel lokal di port `8000`. Ini akan mencegah error CORS di tahap *development*.

### 4. Membersihkan Backend (Laravel)
- Menghapus dependency Node.js dari root Laravel (`package.json`, `package-lock.json`, `node_modules`).
- Menghapus file konfigurasi Vite bawaan Laravel (`vite.config.js`).
- Menghapus direktori sumber frontend lama yang sudah tidak dipakai (`resources/js` dan `resources/css`).
- Memperbarui `routes/web.php` untuk tidak lagi mengembalikan file `welcome.blade.php`, melainkan hanya status API JSON sederhana (misal: `{ "status": "SIAKAD API Running" }`).
- Menghapus `resources/views/welcome.blade.php`.

## Verifikasi Plan
Setelah langkah-langkah di atas dieksekusi, cara kerjanya akan diverifikasi dengan:
1. Menjalankan `php artisan serve` pada terminal 1.
2. Menjalankan `cd frontend && npm install && npm run dev` pada terminal 2.
3. Membuka URL frontend (biasanya http://localhost:5173).
4. Melakukan login dan menelusuri menu mahasiswa/dosen untuk memastikan bahwa proxy berfungsi dengan baik dan sistem dapat berinteraksi kembali dengan backend Laravel secara independen.
