<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$dang_ky_id = filter_input(INPUT_GET, 'dang_ky_id', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_STRING);
$edit = ($operation == 'edit');

$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data_to_update = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $required_fields = array('lich_trinh_id', 'ngay_dang_ky', 'trang_thai');
    foreach ($required_fields as $field) {
        if (empty($data_to_update[$field])) {
            $_SESSION['failure'] = 'Thiếu trường bắt buộc: ' . $field;
            header("location: sua_dang_ky_dao_tao.php?dang_ky_id=$dang_ky_id&operation=edit");
            exit();
        }
    }

    $data_to_update['ngay_dang_ky'] = date('Y-m-d', strtotime($data_to_update['ngay_dang_ky']));

    $db->where('dang_ky_id', $dang_ky_id);
    $result = $db->update('dang_ky_dao_tao', $data_to_update);

    if ($result) {
        $_SESSION['success'] = "Đăng ký đào tạo đã được cập nhật thành công!";
        header('location: dang_ky_dao_tao.php');
        exit();
    } else {
        $_SESSION['failure'] = 'Cập nhật không thành công: ' . $db->getLastError();
        header("location: sua_dang_ky_dao_tao.php?dang_ky_id=$dang_ky_id&operation=edit");
        exit();
    }
}

if ($edit) {
    $db->where('dang_ky_id', $dang_ky_id);
    $dang_ky_dao_tao = $db->getOne("dang_ky_dao_tao");
}

include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật Đăng ký đào tạo</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="dang_ky_dao_tao_form">
        <?php
        require_once ('./forms/dang_ky_dao_tao_form.php');
        ?>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>