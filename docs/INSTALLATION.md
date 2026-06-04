# INSTALLATION GUIDE

## Kebutuhan Sistem

### Software

- PHP 8.0+
- MySQL 5.7+
- Apache
- XAMPP atau Laragon

---

## Langkah Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/proyek-desa.git
```

### 2. Pindahkan ke Folder Web Server

XAMPP:

```text
htdocs/proyek-desa
```

### 3. Buat Database

Nama database:

```sql
db_desa
```

### 4. Import Database

Import file SQL melalui phpMyAdmin.

### 5. Konfigurasi Database

Edit file:

```php
koneksi.php
```

Sesuaikan:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_desa";
```

### 6. Jalankan Aplikasi

```text
http://localhost/proyek-desa
```