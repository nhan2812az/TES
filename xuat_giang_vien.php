<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';
require_once BASE_PATH . '/includes/auth_validate.php';
require_once BASE_PATH . '/lib/GiangVien/GiangVien.php';

$giang_vien = new GiangVien();

$db = getDbInstance();
$select = array(
    'giang_vien.giang_vien_id',
    'giang_vien.ngay_vao_dao_tao',
    'giang_vien.chuyen_mon',
    'giang_vien.trinh_do_hoc_van',
    'giang_vien.kinh_nghiem_giang_day',
    'giang_vien.noi_cong_tac',
    'giang_vien.dia_chi',
    'tai_khoan.ten AS tai_khoan_ten'
);

$db->join('tai_khoan', 'giang_vien.tai_khoan_id = tai_khoan.id_tai_khoan', 'LEFT');

$rows = $db->arraybuilder()->get('giang_vien', null, $select);

$csv_data = array();
$csv_data[] = array(
    'ID',
    'Ngày vào đào tạo',
    'Chuyên môn',
    'Trình độ học vấn',
    'Kinh nghiệm giảng dạy',
    'Nơi công tác',
    'Địa chỉ',
    'Tài khoản'
);

foreach ($rows as $row) {
    $ngay_vao_dao_tao = date('d-m-Y', strtotime($row['ngay_vao_dao_tao']));

    $csv_data[] = array(
        $row['giang_vien_id'],
        $ngay_vao_dao_tao,
        $row['chuyen_mon'],
        $row['trinh_do_hoc_van'],
        $row['kinh_nghiem_giang_day'],
        $row['noi_cong_tac'],
        $row['dia_chi'],
        $row['tai_khoan_ten']
    );
}

$csv_file = 'giang_vien_' . date('Y-m-d') . '.csv';
$csv_path = BASE_PATH . '/exports/' . $csv_file;

if (!file_exists(BASE_PATH . '/exports')) {
    mkdir(BASE_PATH . '/exports', 0777, true);
}

$file = fopen($csv_path, 'w');
if ($file === false) {
    die('Không thể mở file CSV để ghi.');
}

fwrite($file, "\xEF\xBB\xBF");

foreach ($csv_data as $line) {
    fputcsv($file, $line);
}

fclose($file);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $csv_file);

readfile($csv_path);

unlink($csv_path);
exit;
?>