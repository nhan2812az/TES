<?php
session_start();
require_once './config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_to_store = array_filter($_POST);

    $required_fields = array('nhan_vien_id', 'chuong_trinh_id', 'loai_danh_gia', 'diem_danh_gia');
    foreach ($required_fields as $field) {
        if (empty($data_to_store[$field])) {
            $_SESSION['failure'] = 'Thiếu trường bắt buộc: ' . $field;
            header('location: danh_gia_dao_tao.php');
            exit();
        }
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $sql = "INSERT INTO danh_gia_dao_tao (nhan_vien_id, chuong_trinh_id, loai_danh_gia, diem_danh_gia, nhan_xet) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $_SESSION['failure'] = 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        header('location: danh_gia_dao_tao.php');
        exit();
    }
    $stmt->bind_param("iisis", $data_to_store['nhan_vien_id'], $data_to_store['chuong_trinh_id'], $data_to_store['loai_danh_gia'], $data_to_store['diem_danh_gia'], $data_to_store['nhan_xet']);

    try {
        if ($stmt->execute() === true) {
            $_SESSION['success'] = "Đánh giá đào tạo đã được thêm thành công!";
            header('location: danh_gia_dao_tao.php');
            exit();
        } else {
            $_SESSION['failure'] = 'Thêm không thành công: ' . $stmt->error;
            header('location: danh_gia_dao_tao.php');
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1452) {
            $_SESSION['failure'] = "Không thể thêm đánh giá đào tạo: Giá trị không hợp lệ cho một trong các trường.";
        } else {
            $_SESSION['failure'] = "Đã xảy ra lỗi khi thêm đánh giá đào tạo: " . $e->getMessage();
        }
        header('location: danh_gia_dao_tao.php');
        exit();
    } finally {
        $stmt->close();
        $conn->close();
    }
}

$edit = false;

require_once BASE_PATH . '/includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Thêm mới Đánh giá đào tạo</h1>
        </div>
    </div>
    <form class="form" action="" method="post" id="danh_gia_form" enctype="multipart/form-data">
        <?php include_once ('./forms/danh_gia_dao_tao_form.php'); ?>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#danh_gia_form").validate({
            rules: {
                nhan_vien_id: {
                    required: true
                },
                chuong_trinh_id: {
                    required: true
                },
                loai_danh_gia: {
                    required: true
                },
                diem_danh_gia: {
                    required: true,
                    number: true
                }
            }
        });
    });
</script>

<?php include BASE_PATH . '/includes/footer.php'; ?>