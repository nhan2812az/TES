<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$dang_ky_id = filter_input(INPUT_GET, 'dang_ky_id', FILTER_SANITIZE_NUMBER_INT);
if ($dang_ky_id) {
    $db = getDbInstance();
    $db->where('dang_ky_id', $dang_ky_id);
    $data_to_update = array('trang_thai' => 'Đã duyệt');
    $result = $db->update('dang_ky_dao_tao', $data_to_update);
    if ($result) {
        $_SESSION['success'] = "Đăng ký đào tạo đã được phê duyệt thành công!";
    } else {
        $_SESSION['failure'] = "Có lỗi xảy ra khi phê duyệt đăng ký đào tạo.";
    }
} else {
    $_SESSION['failure'] = "ID đăng ký không hợp lệ.";
}

header('location: dang_ky_dao_tao.php');
exit();