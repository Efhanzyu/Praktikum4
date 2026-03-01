<?php
class Sesi {
    private $conn;
    private $table_name = "sesi";

    public $id;
    public $nama_pelanggan;
    public $paket;
    public $jumlah_foto;
    public $tanggal;
    public $status;
    public $total_harga;

    public function __construct($db) {
        $this->conn = $db;
    }

    // READ - Ambil semua sesi
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ ONE - Ambil satu sesi berdasarkan ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt;
    }

    // CREATE - Tambah sesi baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                    (nama_pelanggan, paket, jumlah_foto, tanggal, status, total_harga)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute([
            $this->nama_pelanggan,
            $this->paket,
            $this->jumlah_foto,
            $this->tanggal,
            $this->status,
            $this->total_harga
        ])) {
            return true;
        }
        return false;
    }

    // UPDATE - Perbarui data sesi
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET nama_pelanggan = ?, paket = ?, jumlah_foto = ?,
                      tanggal = ?, status = ?, total_harga = ?
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute([
            $this->nama_pelanggan,
            $this->paket,
            $this->jumlah_foto,
            $this->tanggal,
            $this->status,
            $this->total_harga,
            $this->id
        ])) {
            return true;
        }
        return false;
    }

    // DELETE - Hapus sesi
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute([$this->id])) {
            return true;
        }
        return false;
    }
}
?>
