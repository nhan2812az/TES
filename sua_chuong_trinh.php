<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$chuong_trinh_id = filter_input(INPUT_GET, 'chuong_trinh_id', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', 513);
($operation == 'edit') ? $edit = true : $edit = false;
$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chuong_trinh_id = filter_input(INPUT_GET, 'chuong_trinh_id', FILTER_SANITIZE_NUMBER_INT);
    $data_to_update = filter_input_array(INPUT_POST);

    $sql = "UPDATE chuong_trinh_dao_tao SET ";
    foreach ($data_to_update as $key => $value) {
        $sql .= "$key = '$value', ";
    }
    $sql = rtrim($sql, ', ') . " WHERE chuong_trinh_id = $chuong_trinh_id";

    $result = $db->query($sql);

    if ($result) {
        $_SESSION['success'] = "Chương trình đào tạo đã được cập nhật thành công!";
        header('location: chuong_trinh_dao_tao.php');
        exit();
    }
}

if ($edit) {
    $db->where('chuong_trinh_id', $chuong_trinh_id);
    $chuong_trinh_dao_tao = $db->getOne("chuong_trinh_dao_tao");
}



include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật Chương trình đào tạo</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="training_program_form">

        <?php
        require_once ('./forms/chuong_trinh_dao_tao_form.php');
        ?>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>