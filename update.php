<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan."]);
    exit;
}

include_once 'config/Database.php';
include_once 'models/Sesi.php';

$database = new Database();
$db = $database->getConnection();

$sesi = new Sesi($db);
$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID sesi wajib diisi."]);
    exit;
}

$harga = [
    "Basic"    => 25000,
    "Standard" => 50000,
    "Premium"  => 85000
];

$sesi->id             = $data->id;
$sesi->nama_pelanggan = isset($data->nama_pelanggan) ? htmlspecialchars(strip_tags($data->nama_pelanggan)) : '';
$sesi->paket          = isset($data->paket) ? htmlspecialchars(strip_tags($data->paket)) : '';
$sesi->jumlah_foto    = isset($data->jumlah_foto) ? (int)$data->jumlah_foto : 0;
$sesi->tanggal        = isset($data->tanggal) ? htmlspecialchars(strip_tags($data->tanggal)) : '';
$sesi->status         = isset($data->status) ? htmlspecialchars(strip_tags($data->status)) : 'Menunggu';
$sesi->total_harga    = isset($harga[$sesi->paket]) ? $harga[$sesi->paket] : 0;

if ($sesi->update()) {
    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "message" => "Data sesi berhasil diperbarui."
    ]);
} else {
    http_response_code(503);
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui data sesi."]);
}
?>
