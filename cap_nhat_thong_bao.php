<?php
session_start();
require_once 'config/config.php';

$tai_khoan_id = $_SESSION['id_tai_khoan'];

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Kết nối thất bại: " . $conn->connect_error]));
}

$update_stmt = $conn->prepare("UPDATE thong_bao SET trang_thai = 1 WHERE tai_khoan_id = ?");
if ($update_stmt === false) {
    die(json_encode(["status" => "error", "message" => "Lỗi khi chuẩn bị câu lệnh: " . $conn->error]));
}
$update_stmt->bind_param("i", $tai_khoan_id);
if (!$update_stmt->execute()) {
    die(json_encode(["status" => "error", "message" => "Lỗi khi thực hiện câu lệnh: " . $update_stmt->error]));
}
$update_stmt->close();

$select_stmt = $conn->prepare("SELECT noi_dung, thoi_gian FROM thong_bao WHERE tai_khoan_id = ? ORDER BY thoi_gian DESC");
if ($select_stmt === false) {
    die(json_encode(["status" => "error", "message" => "Lỗi khi chuẩn bị câu lệnh: " . $conn->error]));
}
$select_stmt->bind_param("i", $tai_khoan_id);
$select_stmt->execute();
$result = $select_stmt->get_result();

$thong_bao = [];
while ($row = $result->fetch_assoc()) {
    $thong_bao[] = $row;
}

$select_stmt->close();
$conn->close();

echo json_encode(["status" => "success", "data" => $thong_bao]);
?>