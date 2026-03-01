<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
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

$sesi->id = $data->id;

if ($sesi->delete()) {
    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "message" => "Sesi berhasil dihapus."
    ]);
} else {
    http_response_code(503);
    echo json_encode(["status" => "error", "message" => "Gagal menghapus sesi."]);
}
?>
