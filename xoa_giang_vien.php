<?php
session_start();
require_once 'includes/auth_validate.php';
require_once './config/config.php';

$del_id = filter_input(INPUT_POST, 'del_id');

if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $giang_vien_id = $del_id;

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM giang_vien WHERE giang_vien_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo 'Error preparing statement: ' . $conn->error;
        exit();
    }

    $stmt->bind_param("i", $giang_vien_id);

    try {
        if ($stmt->execute() === true) {
            $_SESSION['info'] = "Xóa giảng viên thành công!";
        } else {
            $_SESSION['failure'] = "Không thể xóa giảng viên";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            $_SESSION['failure'] = "Không thể xóa giảng viên: Dữ liệu đang được sử dụng trong bảng khác.";
        } else {
            $_SESSION['failure'] = "Đã xảy ra lỗi: " . $e->getMessage();
        }
    } finally {
        $stmt->close();
        $conn->close();
        header('location: giang_vien.php');
        exit();
    }
}
?>