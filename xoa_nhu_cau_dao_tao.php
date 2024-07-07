<?php
session_start();
require_once 'includes/auth_validate.php';
require_once './config/config.php';

$del_id = filter_input(INPUT_POST, 'del_id');

if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $nhu_cau_id = $del_id;

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $sql = "DELETE FROM nhu_cau_dao_tao WHERE nhu_cau_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        exit();
    }

    $stmt->bind_param("i", $nhu_cau_id);

    try {
        if ($stmt->execute() === true) {
            $_SESSION['info'] = "Xóa nhu cầu đào tạo thành công!";
            header('location: nhu_cau_dao_tao.php');
            exit();
        } else {
            $_SESSION['failure'] = "Không thể xóa nhu cầu đào tạo";
            header('location: nhu_cau_dao_tao.php');
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        $_SESSION['failure'] = "Đã xảy ra lỗi khi xóa nhu cầu đào tạo: " . $e->getMessage();
        header('location: nhu_cau_dao_tao.php');
        exit();
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>