<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';

function getChuongTrinhDaoTaoList()
{
    $db = getDbInstance();
    $chuong_trinh_dao_tao = $db->get('chuong_trinh_dao_tao', null, ['chuong_trinh_id', 'ten_chuong_trinh']);
    return $chuong_trinh_dao_tao;
}

function getLoaiNoiDungOptions()
{
    return [
        'theo_chuong_trinh' => 'Theo chương trình đào tạo',
        'theo_chu_de' => 'Theo chủ đề'
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_to_store = array_filter($_POST);

    $required_fields = array('chuong_trinh_id', 'loai_noi_dung', 'tieu_de', 'mo_ta', 'chu_de');
    foreach ($required_fields as $field) {
        if (empty($data_to_store[$field])) {
            echo 'Thiếu trường bắt buộc: ' . $field;
            exit();
        }
    }

    if (!isset($_FILES['duong_dan_tap_tin']) || $_FILES['duong_dan_tap_tin']['error'] !== UPLOAD_ERR_OK) {
        echo 'Thiếu tệp tin hoặc tệp tin bị lỗi';
        exit();
    }

    $upload_dir = 'uploads/';
    $file_name = basename($_FILES['duong_dan_tap_tin']['name']);
    $target_file = $upload_dir . $file_name;

    if (!move_uploaded_file($_FILES['duong_dan_tap_tin']['tmp_name'], $target_file)) {
        echo 'Có lỗi khi tải lên tệp tin';
        exit();
    }

    $data_to_store['duong_dan_tap_tin'] = $target_file;

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $sql = "INSERT INTO noi_dung_dao_tao (chuong_trinh_id, loai_noi_dung, tieu_de, mo_ta, duong_dan_tap_tin, chu_de) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        exit();
    }

    $stmt->bind_param("isssss", $data_to_store['chuong_trinh_id'], $data_to_store['loai_noi_dung'], $data_to_store['tieu_de'], $data_to_store['mo_ta'], $data_to_store['duong_dan_tap_tin'], $data_to_store['chu_de']);

    try {
        if ($stmt->execute() === true) {
            $_SESSION['success'] = "Nội dung đào tạo đã được thêm thành công!";
            header('location: noi_dung_dao_tao.php');
            exit();
        } else {
            echo 'Thêm không thành công: ' . $stmt->error;
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $_SESSION['failure'] = "Không thể thêm nội dung đào tạo: Nội dung đã tồn tại.";
        } elseif ($e->getCode() == 1452) {
            $_SESSION['failure'] = "Không thể thêm nội dung đào tạo: Giá trị không hợp lệ cho một trong các trường.";
        } else {
            $_SESSION['failure'] = "Đã xảy ra lỗi khi thêm nội dung đào tạo: " . $e->getMessage();
        }
        header('location: noi_dung_dao_tao.php');
        exit();
    } finally {
        $stmt->close();
        $conn->close();
    }
}

$edit = false;

require_once 'includes/header.php';
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Thêm mới Nội dung đào tạo</h2>
        </div>
    </div>
    <form class="form" action="" method="post" id="training_form" enctype="multipart/form-data">
        <?php include_once ('./forms/noi_dung_dao_tao_form.php'); ?>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
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
            duong_dan_tap_tin: {
                required: true
            },
            chu_de: {
                required: true
            }
        }
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>