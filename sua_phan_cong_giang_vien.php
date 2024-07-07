<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$assignment_id = filter_input(INPUT_GET, 'chuong_trinh_id', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', 513);

($operation == 'edit') ? $edit = true : $edit = false;
$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assignment_id = filter_input(INPUT_POST, 'chuong_trinh_id', FILTER_SANITIZE_NUMBER_INT);
    $data_to_update = filter_input_array(INPUT_POST);

    $sql = "UPDATE phan_cong_giang_vien SET ";
    foreach ($data_to_update as $key => $value) {
        $sql .= "$key = '$value', ";
    }
    $sql = rtrim($sql, ', ') . " WHERE chuong_trinh_id = $assignment_id";

    $result = $db->query($sql);

    if ($result) {
        $_SESSION['success'] = "Phân công giảng viên đã được cập nhật thành công!";
        header('location: phan_cong_giang_vien.php');
        exit();
    }
}

function getGiangVienList()
{
    $db = getDbInstance();
    $giang_vien = $db->get('giang_vien', null, ['giang_vien_id', 'ten']);
    return $giang_vien;
}

function getChuongTrinhDaoTaoList()
{
    $db = getDbInstance();
    $chuong_trinh_dao_tao = $db->get('chuong_trinh_dao_tao', null, ['chuong_trinh_id', 'ten_chuong_trinh']);
    return $chuong_trinh_dao_tao;
}

$giang_vien_list = getGiangVienList();
$chuong_trinh_dao_tao_list = getChuongTrinhDaoTaoList();

if ($edit) {
    $db->where('chuong_trinh_id', $assignment_id);
    $assignment = $db->getOne("phan_cong_giang_vien");
}

include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật Phân công giảng viên</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="assignment_form">
        <?php
        require_once ('./forms/phan_cong_giang_vien_form.php');
        ?>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>