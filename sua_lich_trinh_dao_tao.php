<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$lich_trinh_id = filter_input(INPUT_GET, 'lich_trinh_id', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', 513);
($operation == 'edit') ? $edit = true : $edit = false;
$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lich_trinh_id = filter_input(INPUT_GET, 'lich_trinh_id', FILTER_SANITIZE_NUMBER_INT);
    $data_to_update = filter_input_array(INPUT_POST);

    $sql = "UPDATE lich_trinh_dao_tao SET ";
    foreach ($data_to_update as $key => $value) {
        $sql .= "$key = '$value', ";
    }
    $sql = rtrim($sql, ', ') . " WHERE lich_trinh_id = $lich_trinh_id";

    $result = $db->query($sql);

    if ($result) {
        $_SESSION['success'] = "Lịch trình đào tạo đã được cập nhật thành công!";
        header('location: lich_trinh_dao_tao.php');
        exit();
    }
}

if ($edit) {
    $db->where('lich_trinh_id', $lich_trinh_id);
    $lich_trinh = $db->getOne("lich_trinh_dao_tao");
}
function getChuongTrinhDaoTaoList()
{
    $db = getDbInstance();
    $chuong_trinh_dao_tao = $db->get('chuong_trinh_dao_tao', null, ['chuong_trinh_id', 'ten_chuong_trinh']);
    return $chuong_trinh_dao_tao;
}

include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật Lịch trình đào tạo</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="lich_trinh_form">
        <?php
        require_once ('./forms/lich_trinh_dao_tao_form.php');
        ?>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>