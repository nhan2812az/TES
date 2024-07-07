<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$db = getDbInstance();

$select = array('id_tai_khoan', 'ten', 'phong_ban', 'vi_tri', 'email', 'so_dien_thoai');

$chunk_size = 100;
$offset = 0;

$data = $db->withTotalCount()->get('tai_khoan');
$total_count = $db->totalCount;

$handle = fopen('php://memory', 'w');

fwrite($handle, "\xEF\xBB\xBF");

fputcsv($handle, $select);
$filename = 'export_tai_khoan.csv';

$num_queries = ceil($total_count / $chunk_size);

for ($i = 0; $i < $num_queries; $i++) {
    $rows = $db->get('tai_khoan', array($offset, $chunk_size), $select);
    $offset += $chunk_size;
    foreach ($rows as $row) {
        $row['so_dien_thoai'] = '"' . $row['so_dien_thoai'] . '"';
        fputcsv($handle, array_values($row));
    }
}

fseek($handle, 0);

header('Content-Type: application/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');
fpassthru($handle);
fclose($handle);