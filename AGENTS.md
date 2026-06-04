# AGENTS.md

# Sistem Pelayanan Administrasi Desa

Dokumen ini berisi aturan dan konteks proyek yang wajib dipahami oleh AI Coding Agent sebelum melakukan analisis, perencanaan, refactoring, perbaikan bug, maupun penambahan fitur.

---

# Gambaran Proyek

Sistem Pelayanan Administrasi Desa adalah aplikasi berbasis web yang bertujuan untuk mempermudah masyarakat dalam mengurus layanan administrasi desa secara online tanpa harus datang langsung ke kantor desa.

Sistem ini digunakan oleh:

* Masyarakat
* Aparatur Desa
* Administrator Sistem

Target utama aplikasi adalah memberikan pelayanan yang mudah digunakan oleh seluruh lapisan masyarakat, termasuk pengguna lanjut usia (lansia).

---

# Tujuan Sistem

Sistem harus:

* Mempermudah proses pengajuan surat.
* Mengurangi pelayanan manual.
* Mengurangi antrean di kantor desa.
* Mempermudah verifikasi administrasi.
* Menyimpan arsip surat secara digital.
* Menyediakan informasi status pengajuan secara transparan.

---

# Prinsip Pengembangan

Urutan prioritas pengembangan:

1. Stabilitas sistem
2. Kemudahan penggunaan
3. Aksesibilitas pengguna lansia
4. Keamanan
5. Performa
6. Penambahan fitur baru

Fitur baru tidak boleh merusak fitur yang sudah berjalan.

---

# Karakteristik Pengguna

## Masyarakat Umum

Karakteristik:

* Tidak selalu memahami teknologi.
* Mengakses melalui smartphone.
* Menginginkan proses yang cepat.

## Pengguna Lansia

Karakteristik:

* Kurang familiar dengan teknologi.
* Kesulitan membaca teks kecil.
* Mudah bingung dengan navigasi kompleks.

Setiap perubahan UI harus mempertimbangkan kebutuhan pengguna lansia.

---

# Aturan UI dan UX

Website harus tetap:

* Profesional
* Sederhana
* Bersih
* Responsif
* Mudah dipahami

---

## Jangan Pernah

* Mengubah desain secara drastis.
* Menambahkan animasi berlebihan.
* Menambahkan popup yang tidak perlu.
* Menambah langkah pengajuan yang tidak penting.
* Mengurangi ukuran teks.

---

## Gunakan

* Layout sederhana.
* Tombol besar.
* Warna dengan kontras tinggi.
* Bahasa Indonesia yang mudah dipahami.
* Mobile-first design.

---

## Ukuran Minimum

Teks:

* Minimal 16px
* Disarankan 18px

Tombol:

* Minimal tinggi 48px

---

# Warna Utama Sistem

Primary Color:

```css
#2E7D32
```

Secondary Color:

```css
#F5F7FA
```

Jangan mengubah warna utama tanpa instruksi langsung dari developer.

---

# Aturan Database

Database adalah bagian kritis sistem.

AI tidak boleh:

* Menghapus tabel.
* Menghapus kolom.
* Mengubah nama kolom.
* Mengubah relasi database.

Kecuali ada instruksi eksplisit dari developer.

---

# Aturan Data

Data lama harus tetap kompatibel.

Backward compatibility wajib dijaga.

Jika perubahan database diperlukan:

* Buat migrasi.
* Jangan merusak data yang sudah ada.

---

# Aturan PHP

Gunakan:

* Prepared Statement
* Session Authentication
* password_hash()
* password_verify()

Hindari:

* Query SQL langsung dari input pengguna
* Duplikasi kode
* Hardcoded credential

---

# Aturan HTML

Gunakan:

* Semantic HTML
* Label pada seluruh input form
* Struktur yang mudah dibaca

Prioritaskan accessibility.

---

# Aturan CSS

Gunakan:

* CSS Variables
* Flexbox
* CSS Grid

Hindari:

* Inline CSS
* Penggunaan !important berlebihan

---

# Aturan JavaScript

Gunakan JavaScript hanya jika benar-benar diperlukan.

Prioritas:

1. Kemudahan penggunaan
2. Kecepatan akses
3. Stabilitas

Hindari:

* Framework tambahan yang tidak diperlukan.
* Library besar untuk fitur sederhana.

---

# Alur Bisnis

Alur utama sistem:

Masyarakat
↓
Login
↓
Pilih Layanan
↓
Isi Formulir
↓
Unggah Dokumen
↓
Kirim Pengajuan
↓
Verifikasi Admin
↓
Disetujui / Ditolak
↓
Cetak Surat

Alur ini dianggap sebagai workflow inti sistem.

Jangan diubah tanpa instruksi developer.

---

# Saat Menambah Fitur

AI harus:

1. Memahami fitur yang sudah ada.
2. Mencari fungsi yang dapat digunakan kembali.
3. Menjaga konsistensi UI.
4. Menjaga konsistensi database.
5. Menambahkan dokumentasi bila diperlukan.

---

# Saat Refactoring

AI boleh:

* Membersihkan kode.
* Mengurangi duplikasi.
* Meningkatkan keterbacaan.

AI tidak boleh:

* Mengubah perilaku sistem.
* Mengubah output sistem.
* Mengubah workflow pengguna.

---

# Saat Menemukan Bug

Prioritas:

1. Perbaiki bug dengan perubahan minimal.
2. Jangan melakukan rewrite besar.
3. Pertahankan kompatibilitas sistem.

---

# Struktur Dokumentasi

Dokumen yang harus dijadikan referensi:

1. README.md
2. docs/DESIGN.md
3. docs/DATABASE.md
4. docs/USER_GUIDE.md
5. docs/INSTALLATION.md

Jika terjadi konflik:

AGENTS.md memiliki prioritas tertinggi.

---

# Prinsip Utama

Jika ragu:

Pilih solusi yang paling sederhana.

Jika ada dua solusi:

Pilih yang paling mudah digunakan oleh masyarakat dan pengguna lansia.

Jangan mengorbankan stabilitas sistem demi fitur baru.
