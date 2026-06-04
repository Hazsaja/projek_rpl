# DESIGN.md

# Pedoman Desain Sistem Pelayanan Administrasi Desa

## Filosofi Desain

Sistem ini dirancang berdasarkan prinsip:

> "Mudah digunakan oleh seluruh masyarakat, termasuk pengguna lanjut usia (lansia), tanpa memerlukan kemampuan teknologi yang tinggi."

Fokus utama desain adalah kesederhanaan, keterbacaan, dan kemudahan navigasi.

---

# Target Pengguna

## Masyarakat Umum

Karakteristik:

* Menggunakan smartphone maupun laptop.
* Memiliki kemampuan digital dasar.
* Membutuhkan proses pengajuan surat yang cepat.

Kebutuhan:

* Formulir yang mudah dipahami.
* Informasi status pengajuan yang jelas.
* Tampilan yang tidak membingungkan.

---

## Pengguna Lansia

Karakteristik:

* Kurang familiar dengan teknologi.
* Kesulitan membaca tulisan kecil.
* Mudah bingung dengan terlalu banyak menu.

Kebutuhan:

* Tombol berukuran besar.
* Tulisan yang jelas dan mudah dibaca.
* Navigasi sederhana.
* Sedikit pilihan dalam satu halaman.

---

# Prinsip UI/UX

## 1. Satu Halaman, Satu Tujuan

Setiap halaman hanya memiliki satu fokus utama.

Contoh:

Halaman Login:

* Login saja

Halaman Pengajuan:

* Pengajuan surat saja

Halaman Riwayat:

* Riwayat pengajuan saja

---

## 2. Kurangi Klik

Target maksimal:

* Login → 1 klik
* Pilih surat → 1 klik
* Isi formulir → Submit

Total tidak lebih dari 3-4 langkah.

---

## 3. Gunakan Bahasa Sederhana

Hindari:

❌ Verifikasi Berkas Administratif

Gunakan:

✅ Pemeriksaan Dokumen

---

## 4. Hindari Istilah Teknis

Hindari:

❌ Upload Attachment

Gunakan:

✅ Unggah Dokumen

---

# Warna Utama

## Primary Color

```css
#2E7D32
```

Hijau melambangkan pelayanan publik dan identitas pemerintahan desa.

---

## Secondary Color

```css
#F5F7FA
```

Abu-abu terang untuk area konten.

---

## Success

```css
#4CAF50
```

Status disetujui.

---

## Warning

```css
#FF9800
```

Menunggu verifikasi.

---

## Danger

```css
#E53935
```

Status ditolak.

---

# Tipografi

Font yang direkomendasikan:

```css
Poppins
```

Alternatif:

```css
Arial
```

---

Ukuran teks minimum:

```css
16px
```

Untuk lansia disarankan:

```css
18px
```

---

# Desain Tombol

## Primary Button

Contoh:

```text
[ Ajukan Surat ]
```

Karakteristik:

* Tinggi minimal 48px
* Sudut membulat
* Warna hijau

---

## Secondary Button

Contoh:

```text
[ Kembali ]
```

Warna abu-abu.

---

# Formulir

## Aturan Form

Gunakan:

* Label di atas input
* Jarak antar input yang cukup
* Placeholder yang jelas

Contoh:

Nama Lengkap

[________________]

NIK

[________________]

---

# Dashboard Masyarakat

Menu utama maksimal:

1. Ajukan Surat
2. Riwayat Pengajuan
3. Profil
4. Keluar

Tidak disarankan menampilkan lebih dari 5 menu utama.

---

# Dashboard Admin

Menu:

* Dashboard
* Data Pengguna
* Pengajuan Surat
* Laporan
* Logout

Gunakan sidebar sederhana.

---

# Ikon

Gunakan ikon yang mudah dipahami.

Contoh:

🏠 Beranda

📄 Surat

📋 Riwayat

👤 Profil

🚪 Logout

---

# Responsif Mobile

Prioritas desain:

1. Mobile
2. Tablet
3. Desktop

Karena sebagian besar masyarakat mengakses melalui smartphone.

---

# Accessibility

## Kontras Warna

Minimal rasio:

```text
4.5 : 1
```

Agar mudah dibaca oleh lansia.

---

## Ukuran Klik

Target tombol:

```css
48px × 48px
```

Minimal.

---

## Feedback Pengguna

Setelah submit:

✅ Pengajuan berhasil dikirim.

Jangan hanya menampilkan:

❌ Success.

---

# Desain yang Dihindari

Jangan menggunakan:

* Animasi berlebihan
* Carousel otomatis
* Pop-up terlalu banyak
* Warna mencolok berlebihan
* Tulisan kecil
* Menu bertingkat terlalu dalam

---

# Kesimpulan

Desain Sistem Pelayanan Administrasi Desa harus mengutamakan:

* Kesederhanaan
* Kecepatan penggunaan
* Keterbacaan
* Kemudahan bagi lansia
* Responsif pada perangkat mobile

Tujuan akhirnya adalah agar seluruh masyarakat dapat mengajukan layanan administrasi desa secara mandiri tanpa harus datang ke kantor desa.
