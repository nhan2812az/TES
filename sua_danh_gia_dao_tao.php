<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$evaluation_id = filter_input(INPUT_GET, 'danh_gia_id', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', 513);
($operation == 'edit') ? $edit = true : $edit = false;
$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $evaluation_id = filter_input(INPUT_GET, 'danh_gia_id', FILTER_SANITIZE_NUMBER_INT);
    $data_to_update = filter_input_array(INPUT_POST);

    $sql = "UPDATE danh_gia_dao_tao SET ";
    foreach ($data_to_update as $key => $value) {
        $sql .= "$key = '$value', ";
    }
    $sql = rtrim($sql, ', ') . " WHERE danh_gia_id = $evaluation_id";

    $result = $db->query($sql);

    if ($result) {
        $_SESSION['success'] = "Đánh giá đào tạo đã được cập nhật thành công!";
        header('location: danh_gia_dao_tao.php');
        exit();
    }
}

if ($edit) {
    $db->where('danh_gia_id', $evaluation_id);
    $evaluation = $db->getOne("danh_gia_dao_tao");
}

function getChuongTrinhDaoTaoList()
{
    $db = getDbInstance();
    $chuong_trinh_dao_tao = $db->get('chuong_trinh_dao_tao', null, ['chuong_trinh_id', 'ten_chuong_trinh']);
    return $chuong_trinh_dao_tao;
}

$chuong_trinh_dao_tao_list = getChuongTrinhDaoTaoList();
$loai_danh_gia_options = [
    'Thực hành' => 'Thực hành',
    'Lý thuyết' => 'Lý thuyết',
    'Tổng hợp' => 'Tổng hợp'
];


include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật Đánh giá đào tạo</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="evaluation_form">
        <?php
        require_once ('./forms/danh_gia_dao_tao_form.php');
        ?>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>