<?php
session_start();
require_once 'includes/auth_validate.php';
require_once './config/config.php';

$del_id = filter_input(INPUT_POST, 'del_id', FILTER_SANITIZE_NUMBER_INT);

if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $employee_id = $del_id;

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $delete_thong_bao_sql = "DELETE FROM thong_bao WHERE tai_khoan_id = ?";
    $delete_thong_bao_stmt = $conn->prepare($delete_thong_bao_sql);
    if ($delete_thong_bao_stmt === false) {
        echo 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        exit();
    }
    $delete_thong_bao_stmt->bind_param("i", $employee_id);

    try {
        $conn->begin_transaction();

        if (!$delete_thong_bao_stmt->execute()) {
            throw new Exception("Không thể xóa thông báo liên quan: " . $conn->error);
        }

        $delete_tai_khoan_sql = "DELETE FROM tai_khoan WHERE id_tai_khoan = ?";
        $delete_tai_khoan_stmt = $conn->prepare($delete_tai_khoan_sql);
        if ($delete_tai_khoan_stmt === false) {
            throw new Exception("Lỗi khi chuẩn bị câu lệnh: " . $conn->error);
        }
        $delete_tai_khoan_stmt->bind_param("i", $employee_id);

        if (!$delete_tai_khoan_stmt->execute()) {
            throw new Exception("Không thể xóa nhân viên: " . $conn->error);
        }

        $conn->commit();
        $_SESSION['info'] = "Xóa nhân viên thành công!";
    } catch (Exception $e) {
        $conn->rollback();

        if ($e->getCode() == 1451) {
            $_SESSION['failure'] = "Dữ liệu đang được sử dụng không được phép xóa.";
        } else {
            $_SESSION['failure'] = "Đã xảy ra lỗi khi xóa nhân viên: " . $e->getMessage();
        }
    } finally {
        $delete_thong_bao_stmt->close();
        if (isset($delete_tai_khoan_stmt)) {
            $delete_tai_khoan_stmt->close();
        }
        $conn->close();
    }

    header('location: nhan_vien.php');
    exit();
}
?>