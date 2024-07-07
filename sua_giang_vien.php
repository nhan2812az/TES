<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$instructor_id = filter_input(INPUT_GET, 'giang_vien_id', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', 513);
$edit = ($operation == 'edit') ? true : false;
$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $instructor_id = filter_input(INPUT_GET, 'giang_vien_id', FILTER_SANITIZE_NUMBER_INT);
    $data_to_update = filter_input_array(INPUT_POST);

    $db->where('giang_vien_id', $instructor_id);
    $status = $db->update('giang_vien', $data_to_update);

    if ($status) {
        $_SESSION['success'] = "Giảng viên đã được cập nhật thành công!";
        header('location: giang_vien.php');
        exit();
    } else {
        $_SESSION['failure'] = "Cập nhật giảng viên không thành công";
    }
}

if ($edit) {
    $db->where('giang_vien_id', $instructor_id);
    $instructor = $db->getOne("giang_vien");
}

function getGiangVienAccountList()
{
    $db = getDbInstance();
    $db->where('vai_tro', 'GiangVien');
    $accounts = $db->get('tai_khoan', null, ['id_tai_khoan', 'ten']);
    return $accounts;
}

$accounts = getGiangVienAccountList();

include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật Giảng viên</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="instructor_form">
        <?php
        require_once ('./forms/giang_vien_form.php');
        ?>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#instructor_form").validate({
            rules: {
                ngay_vao_dao_tao: {
                    required: true,
                    date: true
                },
                chuyen_mon: {
                    required: true
                },
                trinh_do_hoc_van: {
                    required: false
                },
                kinh_nghiem_giang_day: {
                    required: false,
                    number: true
                },
                noi_cong_tac: {
                    required: false
                },
                dia_chi: {
                    required: false
                },
                tai_khoan_id: {
                    required: true
                }
            }
        });
    });
</script>

<?php include_once 'includes/footer.php'; ?>