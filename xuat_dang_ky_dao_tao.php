<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$db = getDbInstance();
$select = array('dang_ky_id', 'tai_khoan_id', 'chuong_trinh_id', 'trang_thai', 'ngay_dang_ky');

$rows = $db->arraybuilder()->get('dang_ky_dao_tao', null, $select);

if ($rows) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="export_dang_ky_dao_tao.csv"');

    $output = fopen('php://output', 'w');

    fwrite($output, "\xEF\xBB\xBF");

    fputcsv($output, array('ID', 'Nhân viên', 'Chương trình đào tạo', 'Trạng thái', 'Ngày đăng ký'));

    foreach ($rows as $row) {
        $row['nhan_vien'] = getUserNameFromTaiKhoanId($row['tai_khoan_id']);
        $row['chuong_trinh'] = getTenChuongTrinhFromChuongTrinhId($row['chuong_trinh_id']);

        $formatted_date = date('Y-m-d', strtotime($row['ngay_dang_ky']));

        fputcsv(
            $output,
            array(
                $row['dang_ky_id'],
                $row['nhan_vien'],
                $row['chuong_trinh'],
                $row['trang_thai'],
                '"' . $formatted_date . '"' 
            )
        );
    }

    fclose($output);

    exit();
} else {
    $_SESSION['failure'] = "Không có dữ liệu để xuất.";
    header('location: dang_ky_dao_tao.php');
    exit();
}

function getUserNameFromTaiKhoanId($id_tai_khoan)
{
    $db = getDbInstance();
    $db->where('id_tai_khoan', $id_tai_khoan);
    $nhan_vien = $db->getOne('tai_khoan', 'ten');

    if ($nhan_vien) {
        return $nhan_vien['ten'];
    } else {
        return 'Không xác định';
    }
}

function getTenChuongTrinhFromChuongTrinhId($chuong_trinh_id)
{
    $db = getDbInstance();
    $db->where('chuong_trinh_id', $chuong_trinh_id);
    $chuong_trinh = $db->getOne('chuong_trinh_dao_tao', 'ten_chuong_trinh');

    if ($chuong_trinh) {
        return $chuong_trinh['ten_chuong_trinh'];
    } else {
        return 'Không xác định';
    }
}
?>