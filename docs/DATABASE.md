# DATABASE.md

# Database Sistem Pelayanan Administrasi Desa

## Informasi Database

Nama Database:

```text
db_desa
```

Tujuan database ini adalah mendukung berbagai layanan administrasi desa tanpa perlu membuat tabel baru setiap kali jenis surat ditambahkan.

---

# Entity Relationship Diagram (ERD)

```text
users
│
├── 1
│
└── N
     pengajuan
        │
        ├── N → jenis_surat
        │
        ├── 1 → data_pemohon
        │
        └── N → dokumen_pengajuan
                      │
                      └── N → persyaratan_surat
                                   │
                                   └── N → jenis_surat

template_surat
        │
        └── N → jenis_surat
```

---

# Tabel users

Menyimpan data pengguna sistem.

| Kolom      | Tipe         | Keterangan     |
| ---------- | ------------ | -------------- |
| id         | INT          | Primary Key    |
| nama       | VARCHAR(100) | Nama pengguna  |
| nik        | VARCHAR(16)  | NIK pengguna   |
| email      | VARCHAR(100) | Email          |
| password   | VARCHAR(255) | Password hash  |
| status     | ENUM         | admin/user     |
| created_at | TIMESTAMP    | Tanggal dibuat |

---

# Tabel jenis_surat

Master seluruh layanan administrasi desa.

Contoh:

* Surat Keterangan Domisili
* Surat Keterangan Usaha
* Surat Keterangan Tidak Mampu
* Surat Pengantar Nikah
* Surat Keterangan Kematian

---

# Tabel pengajuan

Menyimpan seluruh permohonan surat.

Status:

* menunggu
* disetujui
* ditolak

---

# Tabel data_pemohon

Menyimpan identitas warga yang mengajukan surat.

Data ini dipisahkan agar struktur database tetap fleksibel ketika jenis surat bertambah.

---

# Tabel persyaratan_surat

Menyimpan daftar persyaratan setiap jenis surat.

Contoh:

SKD:

* KTP
* KK
* Pas Foto

SKU:

* KTP
* KK
* Foto Tempat Usaha

---

# Tabel dokumen_pengajuan

Menyimpan file yang diunggah warga.

Keuntungan:

Tidak perlu lagi membuat kolom seperti:

* file_ktp
* file_kk
* file_pas_foto

untuk setiap jenis surat.

---

# Tabel template_surat

Digunakan untuk menyimpan template surat otomatis.

Contoh:

* Template SKD
* Template SKU
* Template SKTM

---

# Aturan Pengembangan

Saat menambah jenis surat baru:

1. Tambahkan data ke tabel jenis_surat.
2. Tambahkan persyaratan ke tabel persyaratan_surat.
3. Tambahkan template ke tabel template_surat.
4. Tidak perlu membuat tabel baru.

---

# Prinsip Database

Database menggunakan pendekatan:

* Modular
* Normalized
* Scalable
* Multi-Service

Sehingga sistem dapat berkembang menjadi platform pelayanan administrasi desa yang lengkap tanpa perubahan struktur besar pada database.
