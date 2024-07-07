<?php
session_start();
require_once 'includes/auth_validate.php';
require_once './config/config.php';

$del_id = filter_input(INPUT_POST, 'del_id');

if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $dang_ky_id = $del_id;

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM dang_ky_dao_tao WHERE dang_ky_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo 'Error preparing statement: ' . $conn->error;
        exit();
    }

    $stmt->bind_param("i", $dang_ky_id);

    try {
        if ($stmt->execute() === true) {
            $_SESSION['info'] = "Xóa đăng ký đào tạo thành công!";
        } else {
            $_SESSION['failure'] = "Không thể xóa đăng ký đào tạo";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1451) {
            $_SESSION['failure'] = "Không thể xóa đăng ký đào tạo: Dữ liệu đang được sử dụng trong bảng khác.";
        } else {
            $_SESSION['failure'] = "Đã xảy ra lỗi: " . $e->getMessage();
        }
    } finally {
        $stmt->close();
        $conn->close();
        header('location: dang_ky_dao_tao.php');
        exit();
    }
}
?>