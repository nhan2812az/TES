<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$employee_id = filter_input(INPUT_GET, 'id_tai_khoan', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', 513);
($operation == 'edit') ? $edit = true : $edit = false;
$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = filter_input(INPUT_GET, 'id_tai_khoan', FILTER_SANITIZE_NUMBER_INT);
    $data_to_update = filter_input_array(INPUT_POST);

    if (!empty($data_to_update['mat_khau'])) {
        $data_to_update['mat_khau'] = md5($data_to_update['mat_khau']);
    } else {
        unset($data_to_update['mat_khau']);
    }

    $db->where('id_tai_khoan', $employee_id);
    $result = $db->update('tai_khoan', $data_to_update);

    if ($result) {
        $_SESSION['success'] = "Nhân viên đã được cập nhật thành công!";
        header('location: nhan_vien.php');
        exit();
    } else {
        $_SESSION['failure'] = "Cập nhật nhân viên thất bại!";
    }
}

if ($edit) {
    $db->where('id_tai_khoan', $employee_id);
    $employee = $db->getOne("tai_khoan");
}

include_once 'includes/header.php';
?>

<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật nhân viên</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="employee_form">
        <?php
        require_once ('./forms/nhan_vien_form.php');
        ?>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>