<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$db = getDbInstance();

$select = array('danh_gia_id', 'nhan_vien_id', 'chuong_trinh_id', 'loai_danh_gia', 'diem_danh_gia', 'nhan_xet');

$chunk_size = 100;
$offset = 0;

$data = $db->withTotalCount()->get('danh_gia_dao_tao');
$total_count = $db->totalCount;

$handle = fopen('php://memory', 'w');

fwrite($handle, "\xEF\xBB\xBF");

fputcsv($handle, $select);

$filename = 'export_danh_gia_dao_tao.csv';

$num_queries = ceil($total_count / $chunk_size);

for ($i = 0; $i < $num_queries; $i++) {
    $rows = $db->get('danh_gia_dao_tao', array($offset, $chunk_size), $select);
    $offset += $chunk_size;
    foreach ($rows as $row) {
        $row = array_map(function ($value) {
            return is_string($value) ? '"' . $value . '"' : $value;
        }, $row);
        fputcsv($handle, array_values($row));
    }
}

fseek($handle, 0);

header('Content-Type: application/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

fpassthru($handle);

fclose($handle);
?>