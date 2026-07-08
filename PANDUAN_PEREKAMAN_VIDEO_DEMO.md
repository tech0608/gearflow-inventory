# 🎥 PANDUAN PEREKAMAN & NASKAH NARASI VIDEO DEMO
**Proyek Tugas Web — GearFlow Inventory System (Univ. Teknologi Bandung)**
**Domain Live:** `https://www.garasifyy.site`
**Estimasi Durasi Video:** 5 – 7 Menit

---

## 💡 Tips Sebelum Mulai Merekam:
1. **Bersihkan Layar:** Tutup tab browser lain yang tidak perlu, gunakan peramban **Google Chrome / Microsoft Edge** dalam mode Full Screen (`F11`) atau jendela maksimal.
2. **Rekam Layar:** Tekan kombinasi tombol **`Win + Alt + R`** (Windows Game Bar) atau gunakan aplikasi OBS / Zoom untuk merekam layar dalam resolusi 1080p (Full HD).
3. **Gerakan Kursor:** Gerakkan kursor mouse dengan perlahan, tenang, dan tidak terburu-buru agar penonton bisa mengikuti alur sistem dengan jelas.
4. **Pengisian Suara (Dubbing):** Kamu bisa merekam layarnya terlebih dahulu secara diam sampai selesai, lalu membaca teks naskah narasi di bawah ini saat proses editing video (menggunakan CapCut / Premiere Pro).

---

## 🎬 ADEGAN 1: Pembukaan & Pengenalan Proyek (Durasi: ~30 Detik)
* **🌐 Posisi Layar:** Buka browser dan tampilkan halaman depan **Login Page** di `https://www.garasifyy.site/login`.
* **🖱️ Aksi yang Direkam:**
  * Diamkan layar sejenak memperlihatkan desain *Dark Mode* halaman login yang mewah dan modern.
  * Gerakkan kursor perlahan ke arah logo "InvBengkel" dan judul proyek.
* **🗣️ Naskah Narasi (Suara):**
  > *"Halo semuanya, perkenalkan nama saya Luthfy Arief dari Universitas Teknologi Bandung. Pada kesempatan kali ini, saya akan mendemonstrasikan sistem aplikasi web yang telah saya kembangkan, yaitu **GearFlow Inventory System**.*
  > *Aplikasi ini adalah sistem manajemen inventaris suku cadang, alat kerja, dan operasional bengkel berbasis web modern yang dibangun menggunakan framework **Laravel 11**, PHP 8.3, dan MySQL, serta telah berhasil di-deploy secara live pada server Nginx dengan domain resmi **garasifyy.site**."*

---

## 🎬 ADEGAN 2: Keamanan Login & Kredensial Admin (Durasi: ~35 Detik)
* **🌐 Posisi Layar:** Halaman Login (`https://www.garasifyy.site/login`).
* **🖱️ Aksi yang Direkam:**
  1. Klik kotak input Username, ketik: **`admin`**.
  2. Klik kotak input Password, ketik: **`admin123`**.
  3. Arahkan kursor ke tombol biru **"Masuk ke Sistem ➔"**, lalu klik tombol tersebut.
  4. Tunggu animasi transisi masuk ke halaman **Dashboard**.
* **🗣️ Naskah Narasi (Suara):**
  > *"Dari segi keamanan, sistem ini telah dilengkapi dengan proteksi anti-SQL Injection melalui Eloquent ORM, enkripsi password bcrypt, proteksi CSRF token, serta pembatasan percobaan login (Rate Limiting) maksimal 5 kali per menit untuk mencegah serangan brute-force.*
  > *Sekarang, kita akan login menggunakan akun **Administrator** untuk melihat fitur keseluruhan sistem."*

---

## 🎬 ADEGAN 3: Dashboard Analisis & Statistik Real-Time (Durasi: ~45 Detik)
* **🌐 Posisi Layar:** Halaman **Dashboard Utama** (`/dashboard`).
* **🖱️ Aksi yang Direkam:**
  1. Tunjukkan 4 Kartu Statistik di bagian atas (*Total Barang, Total Nilai Stok, Pemasok, Stok Kritis*).
  2. Scroll perlahan ke bawah menuju **Grafik Tren Transaksi (7 Hari Terakhir)** dan **Komposisi Stok per Kategori**.
  3. Arahkan kursor mouse (hover) ke titik-titik grafik Chart.js agar muncul *tooltip* angka transaksinya.
  4. Scroll lagi ke bawah memperlihatkan tabel **Transaksi Terkini** dan daftar **Barang Stok Menipis**.
* **🗣️ Naskah Narasi (Suara):**
  > *"Setelah login, kita disambut oleh Dashboard interaktif yang menyajikan ringkasan data secara real-time. Di bagian atas terdapat kartu metrik vital seperti nilai evaluasi total aset stok yang dihitung secara otomatis.*
  > *Di bagian tengah, sistem menyediakan visualisasi grafik interaktif menggunakan Chart.js untuk memantau tren barang masuk dan keluar selama 7 hari terakhir, serta komposisi stok berdasarkan kategori. Di bagian bawah, terdapat sistem peringatan dini (Early Warning System) yang menampilkan daftar suku cadang dengan stok menipis agar admin dapat segera melakukan restok."*

---

## 🎬 ADEGAN 4: Manajemen Master Data Barang & Cetak Label QR (Durasi: ~60 Detik)
* **🌐 Posisi Layar:** Klik menu **Data Barang** di sidebar kiri (`/barang`).
* **🖱️ Aksi yang Direkam:**
  1. Tunjukkan tabel daftar barang dengan kolom SKU, Nama Barang, Kategori, Harga, Stok, dan Status (Aman / Kritis).
  2. Arahkan kursor ke kotak pencarian di kanan atas, coba ketik kata kunci, misalnya: **`Oli`** atau **`Busi`** (tunjukkan bahwa pencarian bekerja sangat cepat). Hapus kembali teks pencarian.
  3. Klik tombol filter kategori **"⚙️ Suku Cadang"** atau **"🔧 Alat Kerja"**, lalu klik **"🌟 Semua"** kembali.
  4. Klik tombol ikon **"🖨️" (Cetak QR)** pada salah satu barang (misalnya Busi NGK Iridium).
  5. Popup cetak QR Code SKU akan muncul. Tunjukkan QR code tersebut, lalu klik tombol **"Tutup"** (✕).
* **🗣️ Naskah Narasi (Suara):**
  > *"Selanjutnya adalah modul Data Barang. Di sini kita dapat mengelola seluruh katalog suku cadang, alat kerja, dan bahan habis pakai. Setiap barang memiliki kode SKU unik, spesifikasi harga, serta batas stok minimum.*
  > *Sistem ini juga dilengkapi fitur eksklusif generate dan cetak label **QR Code SKU**. Label QR ini siap dicetak dan ditempelkan pada rak kemasan suku cadang di bengkel untuk mempermudah proses pemindaian fisik nantinya."*

---

## 🎬 ADEGAN 5: Fitur Unggulan – WebRTC Camera Barcode Scanner (Durasi: ~45 Detik)
* **🌐 Posisi Layar:** Masih di halaman **Data Barang** (`/barang`).
* **🖱️ Aksi yang Direkam:**
  1. Klik tombol biru berikon kamera di atas tabel: **"📷 Scan Barcode / QR Cari"**.
  2. Kotak kamera pemindai akan terbuka di atas tabel (jika di laptopmu ada webcam, kamera akan aktif menampilkan video live).
  3. Arahkan kursor ke area kamera untuk menunjukkan bahwa scanner sedang aktif memindai.
  4. Klik tombol **"✕ Tutup Kamera"** untuk menutup fitur pemindai.
* **🗣️ Naskah Narasi (Suara):**
  > *"Salah satu inovasi terbesar dalam aplikasi ini adalah integrasi **WebRTC Camera Scanner**. Tanpa memerlukan alat pemindai barcode kasir yang mahal, mekanik atau staf bengkel cukup menekan tombol scan ini, dan kamera laptop atau smartphone akan langsung aktif untuk memindai barcode atau QR Code pada suku cadang.*
  > *Sistem akan mendeteksi kode SKU secara instan dan langsung menampilkan data barang atau mengisi formulir transaksi dalam hitungan detik."*

---

## 🎬 ADEGAN 6: Simulasi Transaksi Barang Masuk (Restok Supplier) (Durasi: ~60 Detik)
* **🌐 Posisi Layar:** Klik menu **Barang Masuk** di sidebar (`/barang-masuk`).
* **🖱️ Aksi yang Direkam:**
  1. Perlihatkan tabel riwayat barang masuk. Lalu klik tombol biru **"+ Catat Barang Masuk"** di kanan atas.
  2. Di dalam modal form yang muncul:
     * Pilih **Pemasok**: *PT Indopart Jaya* (atau pemasok lain).
     * Pilih **Barang**: *Busi NGK Iridium (SKU-SP-003)* atau barang yang stoknya ingin ditambah.
     * Isi **Jumlah Masuk**: Ketik angka **`25`**.
     * **Tanggal**: Biarkan tanggal hari ini.
     * **Keterangan**: Ketik: **`Restok rutin mingguan`**.
  3. Klik tombol **"Simpan Transaksi"**.
  4. Tunjukkan pesan sukses warna hijau yang muncul di atas layar, dan perlihatkan bahwa transaksi baru sudah masuk di baris pertama tabel.
* **🗣️ Naskah Narasi (Suara):**
  > *"Sekarang mari kita demonstrasikan alur transaksi Barang Masuk. Ketika kiriman suku cadang tiba dari distributor, staf cukup mengklik tombol 'Catat Barang Masuk', memilih nama pemasok, memilih barang, dan memasukkan jumlah item yang diterima.*
  > *Begitu transaksi disimpan, sistem secara atomik akan mencatat riwayat transaksi dan **otomatis menambah jumlah stok barang** yang bersangkutan di dalam database tanpa perlu update manual."*

---

## 🎬 ADEGAN 7: Simulasi Transaksi Barang Keluar (Servis Kendaraan) (Durasi: ~60 Detik)
* **🌐 Posisi Layar:** Klik menu **Barang Keluar** di sidebar (`/barang-keluar`).
* **🖱️ Aksi yang Direkam:**
  1. Klik tombol biru **"+ Catat Pemakaian"**.
  2. Di dalam modal form:
     * Pilih **Barang**: Pilih barang yang sama atau suku cadang servis (misal *Busi NGK Iridium* atau *Oli Mesin*).
     * Isi **Jumlah Keluar**: Ketik angka **`4`**.
     * **Tanggal**: Biarkan tanggal hari ini.
     * **Keperluan / Catatan**: Ketik: **`Servis Toyota Avanza - D 1234 ABC`**.
  3. Klik tombol **"Simpan Transaksi"**.
  4. Tunjukkan pesan sukses hijau, dan perlihatkan bahwa transaksi pemakaian barang telah berhasil tercatat.
* **🗣️ Naskah Narasi (Suara):**
  > *"Begitu pula untuk alur operasional sehari-hari, yaitu modul Barang Keluar. Modul ini digunakan setiap kali mekanik mengambil suku cadang atau bahan habis pakai untuk perbaikan kendaraan pelanggan.*
  > *Staf cukup mencatat nama barang, jumlah yang dipakai, serta nomor polisi kendaraan pada kolom catatan. Sistem secara otomatis mengurangi stok barang. Jika stok barang yang diminta melebihi sisa stok di gudang, sistem memiliki proteksi validasi untuk menolak transaksi tersebut agar tidak terjadi defisit stok."*

---

## 🎬 ADEGAN 8: Mitra Pemasok & Role-Based Access Control (RBAC) (Durasi: ~45 Detik)
* **🌐 Posisi Layar:** Klik menu **Pemasok**, lalu klik menu **Pengguna** di sidebar.
* **🖱️ Aksi yang Direkam:**
  1. Di menu **Pemasok**: Tunjukkan singkat daftar distributor rekanan bengkel beserta nomor telepon dan alamatnya.
  2. Klik menu **Pengguna (Admin)** di sidebar:
     * Tunjukkan tabel daftar akun yang ada: **`admin` (Administrator)** dan **`staff` (Staf Bengkel)**.
     * Tunjukkan badge bertuliskan "Admin Only" pada menu ini.
* **🗣️ Naskah Narasi (Suara):**
  > *"Aplikasi ini menerapkan manajemen relasi mitra melalui modul Data Pemasok untuk menyimpan kontak supplier. Selain itu, sistem menerapkan **Role-Based Access Control (RBAC)** yang ketat.*
  > *Seperti yang kita lihat pada menu Manajemen Pengguna ini, hanya akun dengan peran Administrator yang memiliki wewenang untuk melihat, menambah, atau mengubah akun pengguna lain. Pemisahan hak akses ini menjamin integritas data operasional bengkel."*

---

## 🎬 ADEGAN 9: Ekspor Laporan Excel & Audit Trail (Log Aktivitas) (Durasi: ~50 Detik)
* **🌐 Posisi Layar:** Klik menu **Laporan**, lalu klik menu **Log Aktivitas** di sidebar.
* **🖱️ Aksi yang Direkam:**
  1. Di menu **Laporan**:
     * Tunjukkan pilihan filter Tanggal Mulai dan Tanggal Selesai.
     * Klik tombol hijau **"📥 Download Excel (.CSV)"**. (Jika browser mendownload file, tunjukkan sekilas file `laporan_inventaris.csv` yang terunduh di pojok browser).
  2. Klik menu **Log Aktivitas** di sidebar:
     * Tunjukkan tabel riwayat aktivitas yang merekam: *Waktu, Pengguna, Aktivitas, Keterangan (misal: "Login berhasil", "Menambah transaksi"), dan IP Address*.
* **🗣️ Naskah Narasi (Suara):**
  > *"Untuk mendukung kebutuhan manajerial dan pelaporan kepada pimpinan bengkel, sistem menyediakan modul Laporan dengan filter rentang tanggal serta fitur ekspor otomatis ke format **Excel (.CSV)**.*
  > *Seluruh tindakan penting di dalam aplikasi juga dipantau 24 jam melalui modul **Log Aktivitas (Audit Trail)**. Sistem mencatat siapa yang melakukan aktivitas, apa tindakan yang dilakukan, beserta alamat IP penggunanya, memberikan tingkat transparansi dan akuntabilitas yang tinggi."*

---

## 🎬 ADEGAN 10: Pengujian Akun Staff & Penutup (Durasi: ~45 Detik)
* **🌐 Posisi Layar:** Klik tombol **Logout** di topbar kanan atas, lalu login kembali sebagai Staff.
* **🖱️ Aksi yang Direkam:**
  1. Klik tombol **Logout**, kamu akan kembali ke halaman Login.
  2. Ketik Username: **`staff`** | Password: **`password`**, lalu klik Login.
  3. Setelah masuk ke Dashboard sebagai Staff, arahkan kursor ke **Sidebar Kiri** untuk menunjukkan bahwa menu **"Pengguna" sudah tidak ada / disembunyikan otomatis oleh sistem**.
  4. Arahkan kursor ke profil pengguna di kanan atas yang menunjukkan role **"Staf"**.
  5. Klik **Logout** kembali untuk mengakhiri video di halaman login.
* **🗣️ Naskah Narasi (Suara):**
  > *"Sebagai pembuktian sistem RBAC, kita mencoba logout dan masuk menggunakan akun **Staf Bengkel** dengan username `staff`. Dapat diperhatikan bahwa setelah login sebagai Staf, menu Manajemen Pengguna secara otomatis dihilangkan oleh sistem, dan staf hanya dapat mengakses modul transaksi operasional.*
  > *Demikian demonstrasi dari GearFlow Inventory System. Aplikasi ini telah menjawab seluruh kebutuhan automasi pencatatan suku cadang bengkel dengan standar keamanan tinggi dan kemudahan penggunaan. Terima kasih atas perhatiannya, Wassalamualaikum warahmatullahi wabarakatuh."*

---
*💡 **Catatan untuk Luthfy:** Kamu tidak harus membaca teks di atas secara kaku 100% sama kata per kata. Gunakan gaya bahasamu sendiri yang santai dan percaya diri agar narasi terdengar natural dan meyakinkan! Selamat merekam! 🏆🔥*
