<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

// Harga paket
$harga = [
    "Basic"    => 25000,
    "Standard" => 50000,
    "Premium"  => 85000
];

if (
    !empty($data->nama_pelanggan) &&
    !empty($data->paket) &&
    !empty($data->jumlah_foto) &&
    !empty($data->tanggal)
) {
    $sesi->nama_pelanggan = htmlspecialchars(strip_tags($data->nama_pelanggan));
    $sesi->paket          = htmlspecialchars(strip_tags($data->paket));
    $sesi->jumlah_foto    = (int)$data->jumlah_foto;
    $sesi->tanggal        = htmlspecialchars(strip_tags($data->tanggal));
    $sesi->status         = "Menunggu";
    $sesi->total_harga    = isset($harga[$sesi->paket]) ? $harga[$sesi->paket] : 0;

    if ($sesi->create()) {
        http_response_code(201);
        echo json_encode([
            "status"  => "success",
            "message" => "Sesi photo booth berhasil dibuat.",
            "data"    => [
                "nama_pelanggan" => $sesi->nama_pelanggan,
                "paket"          => $sesi->paket,
                "total_harga"    => $sesi->total_harga
            ]
        ]);
    } else {
        http_response_code(503);
        echo json_encode(["status" => "error", "message" => "Gagal membuat sesi."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap. Wajib isi: nama_pelanggan, paket, jumlah_foto, tanggal."]);
}
?>
