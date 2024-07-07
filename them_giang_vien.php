<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';

function getGiangVienAccountList()
{
    $db = getDbInstance();
    $db->where('vai_tro', 'GiangVien');
    $accounts = $db->get('tai_khoan', null, ['id_tai_khoan', 'ten']);
    return $accounts;
}

$accounts = getGiangVienAccountList();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_to_store = array_filter($_POST);

    $required_fields = array('ngay_vao_dao_tao', 'chuyen_mon', 'tai_khoan_id');
    foreach ($required_fields as $field) {
        if (empty($data_to_store[$field])) {
            echo 'Missing required field: ' . $field;
            exit();
        }
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO giang_vien (ngay_vao_dao_tao, chuyen_mon, trinh_do_hoc_van, kinh_nghiem_giang_day, noi_cong_tac, dia_chi, tai_khoan_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo 'Error preparing statement: ' . $conn->error;
        exit();
    }
    $stmt->bind_param("sssissi", $data_to_store['ngay_vao_dao_tao'], $data_to_store['chuyen_mon'], $data_to_store['trinh_do_hoc_van'], $data_to_store['kinh_nghiem_giang_day'], $data_to_store['noi_cong_tac'], $data_to_store['dia_chi'], $data_to_store['tai_khoan_id']);

    if ($stmt->execute() === true) {
        $_SESSION['success'] = "Giảng viên đã được thêm thành công!";
        header('location: giang_vien.php');
        exit();
    } else {
        echo 'Insert failed: ' . $stmt->error;
        exit();
    }

    $stmt->close();
    $conn->close();
}

$edit = false;

require_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Thêm mới Giảng viên</h2>
        </div>
    </div>
    <form class="form" action="" method="post" id="instructor_form" enctype="multipart/form-data">
        <?php include_once ('./forms/giang_vien_form.php'); ?>
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