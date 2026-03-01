CREATE DATABASE IF NOT EXISTS photobooth;
USE photobooth;

CREATE TABLE IF NOT EXISTS sesi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(255) NOT NULL,
    paket VARCHAR(50) NOT NULL,
    jumlah_foto INT NOT NULL,
    tanggal DATETIME NOT NULL,
    status ENUM('Menunggu', 'Berlangsung', 'Selesai', 'Dibatalkan') DEFAULT 'Menunggu',
    total_harga INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
