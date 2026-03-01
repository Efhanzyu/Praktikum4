<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan."]);
    exit;
}

include_once 'config/Database.php';
include_once 'models/Sesi.php';

$database = new Database();
$db = $database->getConnection();

$sesi = new Sesi($db);
$sesi->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $sesi->readOne();
$num = $stmt->rowCount();

if ($num > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => array(
            "id"             => $id,
            "nama_pelanggan" => $nama_pelanggan,
            "paket"          => $paket,
            "jumlah_foto"    => $jumlah_foto,
            "tanggal"        => $tanggal,
            "status"         => $status,
            "total_harga"    => $total_harga
        )
    ]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Sesi tidak ditemukan."]);
}
?>
