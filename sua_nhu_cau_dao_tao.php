<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$nhu_cau_id = filter_input(INPUT_GET, 'nhu_cau_id', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', 513);
($operation == 'edit') ? $edit = true : $edit = false;
$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nhu_cau_id = filter_input(INPUT_GET, 'nhu_cau_id', FILTER_SANITIZE_NUMBER_INT);
    $data_to_update = filter_input_array(INPUT_POST);

    $sql = "UPDATE nhu_cau_dao_tao SET ";
    foreach ($data_to_update as $key => $value) {
        $sql .= "$key = '$value', ";
    }
    $sql = rtrim($sql, ', ') . " WHERE nhu_cau_id = $nhu_cau_id";

    $result = $db->query($sql);

    if ($result) {
        $_SESSION['success'] = "Nhu cầu đào tạo đã được cập nhật thành công!";
        header('location: nhu_cau_dao_tao.php');
        exit();
    }
}

if ($edit) {
    $db->where('nhu_cau_id', $nhu_cau_id);
    $nhu_cau_dao_tao = $db->getOne("nhu_cau_dao_tao");
}

function getEmployeeList()
{
    $db = getDbInstance();
    $employees = $db->get('tai_khoan', null, ['id_tai_khoan', 'ten']);
    return $employees;
}


function getLoaiKyNangOptions()
{
    return [
        'kien_thuc' => 'Kiến thức',
        'nghiep_vu' => 'Nghiệp vụ',
        'ky_nang' => 'Kỹ năng'
    ];
}


function getMucKyNangOptions()
{
    return [
        'co_ban' => 'Cơ bản',
        'trung_binh' => 'Trung bình',
        'cao' => 'Cao'
    ];
}

$nhan_vien_list = getEmployeeList();
$loai_ky_nang_options = getLoaiKyNangOptions();
$muc_ky_nang_options = getMucKyNangOptions();

include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật Nhu cầu đào tạo</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="training_form">

        <?php
        require_once ('./forms/nhu_cau_dao_tao_form.php');
        ?>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>