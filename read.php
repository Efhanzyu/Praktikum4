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
$stmt = $sesi->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $sesi_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $sesi_item = array(
            "id"             => $id,
            "nama_pelanggan" => $nama_pelanggan,
            "paket"          => $paket,
            "jumlah_foto"    => $jumlah_foto,
            "tanggal"        => $tanggal,
            "status"         => $status,
            "total_harga"    => $total_harga
        );
        array_push($sesi_arr, $sesi_item);
    }
    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "total"   => $num,
        "data"    => $sesi_arr
    ]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Data tidak ditemukan."]);
}
?>
