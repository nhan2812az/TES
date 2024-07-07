<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$db = getDbInstance();
$select = array('nhu_cau_id', 'nhan_vien_id', 'loai_ky_nang', 'muc_ky_nang', 'nhan_xet_quan_ly', 'ket_qua_khao_sat');

$rows = $db->arraybuilder()->get('nhu_cau_dao_tao', null, $select);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="export_nhu_cau_dao_tao.csv"');

$output = fopen('php://output', 'w');

fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, array('ID', 'Mã nhân viên', 'Loại kỹ năng', 'Mức kỹ năng', 'Nhận xét của quản lý', 'Kết quả khảo sát'));

foreach ($rows as $row) {
    fputcsv(
        $output,
        array(
            $row['nhu_cau_id'],
            $row['nhan_vien_id'],
            $row['loai_ky_nang'],
            $row['muc_ky_nang'],
            $row['nhan_xet_quan_ly'],
            $row['ket_qua_khao_sat']
        )
    );
}

fclose($output);

exit();
?>