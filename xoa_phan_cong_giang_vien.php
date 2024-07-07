<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
require_once BASE_PATH . '/lib/GiangVien/GiangVien.php';

function themThongBao($tai_khoan_id, $noi_dung)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO thong_bao (tai_khoan_id, noi_dung) VALUES (?, ?)");
    if ($stmt === false) {
        $_SESSION['failure'] = 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        header('location: giang_vien.php');
        exit();
    }
    $stmt->bind_param("is", $tai_khoan_id, $noi_dung);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function getTaiKhoanIdFromGiangVien($giang_vien_id)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT tai_khoan_id FROM giang_vien WHERE giang_vien_id = ?");
    if ($stmt === false) {
        $_SESSION['failure'] = 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        header('location: giang_vien.php');
        exit();
    }
    $stmt->bind_param("i", $giang_vien_id);
    $stmt->execute();
    $stmt->bind_result($tai_khoan_id);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    return $tai_khoan_id;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $giang_vien_id = filter_input(INPUT_POST, 'giang_vien_id', FILTER_VALIDATE_INT);

    if (!$giang_vien_id) {
        $_SESSION['failure'] = "Không tìm thấy ID giảng viên để xóa phân công.";
        header('Location: giang_vien.php');
        exit;
    }

    $giang_vien = new GiangVien();
    $result = $giang_vien->deleteAssignment($giang_vien_id);

    if ($result) {
        $tai_khoan_id = getTaiKhoanIdFromGiangVien($giang_vien_id);
        themThongBao($tai_khoan_id, 'Bạn đã bị xóa phân công khỏi chương trình đào tạo.');
        $_SESSION['success'] = "Đã xóa phân công giảng viên thành công.";
    } else {
        $_SESSION['failure'] = "Không thể xóa phân công giảng viên. Vui lòng thử lại sau.";
    }

    header('Location: giang_vien.php');
    exit;
} else {
    $_SESSION['failure'] = "Phương thức không hợp lệ.";
    header('Location: giang_vien.php');
    exit;
}
?>