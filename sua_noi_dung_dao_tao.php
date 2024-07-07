<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

function getChuongTrinhDaoTaoList()
{
    $db = getDbInstance();
    return $db->get('chuong_trinh_dao_tao', null, ['chuong_trinh_id', 'ten_chuong_trinh']);
}

function getLoaiNoiDungOptions()
{
    return [
        'theo_chuong_trinh' => 'Theo chương trình đào tạo',
        'theo_chu_de' => 'Theo chủ đề'
    ];
}

$noi_dung_id = filter_input(INPUT_GET, 'noi_dung_id', FILTER_SANITIZE_NUMBER_INT);
$operation = filter_input(INPUT_GET, 'operation', 513);
$edit = ($operation == 'edit');

$db = getDbInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data_to_update = array_filter($_POST);

    $required_fields = ['chuong_trinh_id', 'loai_noi_dung', 'tieu_de', 'mo_ta', 'chu_de'];
    foreach ($required_fields as $field) {
        if (empty($data_to_update[$field])) {
            echo 'Thiếu trường bắt buộc: ' . $field;
            exit();
        }
    }

    if (isset($_FILES['duong_dan_tap_tin']) && $_FILES['duong_dan_tap_tin']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['duong_dan_tap_tin']['name']);
        $target_file = $upload_dir . $file_name;

        if (!move_uploaded_file($_FILES['duong_dan_tap_tin']['tmp_name'], $target_file)) {
            echo 'Có lỗi khi tải lên tệp tin';
            exit();
        }

        $data_to_update['duong_dan_tap_tin'] = $target_file;
    }

    $db->where('noi_dung_id', $noi_dung_id);
    $success = $db->update('noi_dung_dao_tao', $data_to_update);

    if ($success) {
        $_SESSION['success'] = "Nội dung đào tạo đã được cập nhật thành công!";
    } else {
        $_SESSION['failure'] = "Cập nhật nội dung đào tạo không thành công.";
    }

    header('location: noi_dung_dao_tao.php');
    exit();
}

if ($edit) {
    $db->where('noi_dung_id', $noi_dung_id);
    $noi_dung_dao_tao = $db->getOne("noi_dung_dao_tao");
}

include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Cập nhật Nội dung đào tạo</h2>
    </div>
    <!-- Flash messages -->
    <?php include ('./includes/flash_messages.php') ?>

    <form class="form" action="" method="post" enctype="multipart/form-data" id="training_form">
        <?php
        require_once ('./forms/noi_dung_dao_tao_form.php');
        ?>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#training_form").validate({
            rules: {
                chuong_trinh_id: {
                    required: true,
                    digits: true
                },
                loai_noi_dung: {
                    required: true
                },
                tieu_de: {
                    required: true
                },
                mo_ta: {
                    required: true
                },
                chu_de: {
                    required: true
                },
                duong_dan_tap_tin: {
                    required: function (element) {
                        return $("#operation").val() === "edit" ? false : true;
                    }
                }
            }
        });
    });
</script>

<?php include_once 'includes/footer.php'; ?>