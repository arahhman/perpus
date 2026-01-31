**Perpustakaan**

   - Clone Repository (PHP minimal versi 8.1)
   - Buat Database dengan nama db_test atau jalankan ```CREATE DATABASE db_test;```
   - Copy .env.example ke .env atau jalankan ```php -r "copy('.env.example', '.env');";```
   - Sesuaikan konfigurasi database di file .env
   - Jalankan ```composer install``` untuk menginstall dependencies
   - Jalankan ```npm install``` untuk menginstall library
   - jalankan ```php artisan migrate``` untuk migrasi database
   - Jalankan ``` php artisan db:seed --class=UserSeeder``` untuk seeding database
   - Jalankan ```php artisan key:generate``` untuk generate app key
   - jalankan ```npm run build``` untuk compile asset
   - Jalankan ```php artisan serve``` untuk menjalankan aplikasi
   - buka web menggunakan url dari artisan serve
   - login admin
    - user: admin@perpus.com
    - password: admin123
   - login siswa
    - user: siswa@perpus.com
    - password: siswa123
    


**DDL (jika menggunakan migrate tidak usah dijalankan)**
    CREATE TABLE MasterBuku (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(255) NOT NULL,
        pengarang VARCHAR(255) NOT NULL,
        penerbit VARCHAR(255) NOT NULL,
        tahun_terbit YEAR NOT NULL,
        isbn VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    CREATE TABLE MasterMahasiswa (
        id_user VARCHAR(50) PRIMARY KEY,
        nim VARCHAR(50) NOT NULL,
        nama VARCHAR(255) NOT NULL,
        jenis_kelamin ENUM('L', 'P') NULL,
        alamat VARCHAR(255) NOT NULL,
        ttl DATE NULL,
        program_studi VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    CREATE TABLE TransaksiPeminjaman (
        id_user VARCHAR(50) NOT NULL,
        id_buku BIGINT NOT NULL,
        tanggal_pinjam DATE NOT NULL,
        tanggal_kembali DATE NOT NULL,
        flag_end ENUM('Y', 'N') DEFAULT 'N',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id_user, id_buku)
    );

    CREATE TABLE StokBuku (
        id_buku BIGINT PRIMARY KEY,
        jumlah_stok INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    CREATE TABLE HistoryPinjaman (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        id_user VARCHAR(50) NOT NULL,
        id_buku BIGINT NOT NULL,
        tanggal_pinjam DATE NOT NULL,
        tanggal_kembali DATE,
        flag_end ENUM('Y','N') DEFAULT 'Y',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
