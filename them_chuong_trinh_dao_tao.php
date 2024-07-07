<?php
session_start();
require_once './config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_to_store = array_filter($_POST);

    $required_fields = array('ten_chuong_trinh', 'doi_tuong', 'thoi_luong', 'hinh_thuc');
    foreach ($required_fields as $field) {
        if (empty($data_to_store[$field])) {
            echo 'Thiếu trường bắt buộc: ' . $field;
            exit();
        }
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $sql = "INSERT INTO chuong_trinh_dao_tao (ten_chuong_trinh, doi_tuong, thoi_luong, hinh_thuc) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        exit();
    }

    $stmt->bind_param("ssss", $data_to_store['ten_chuong_trinh'], $data_to_store['doi_tuong'], $data_to_store['thoi_luong'], $data_to_store['hinh_thuc']);

    try {
        if ($stmt->execute() === true) {
            $_SESSION['success'] = "Chương trình đào tạo đã được thêm thành công!";
            header('location: chuong_trinh_dao_tao.php');
            exit();
        } else {
            echo 'Thêm không thành công: ' . $stmt->error;
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $_SESSION['failure'] = "Không thể thêm chương trình đào tạo: Chương trình đã tồn tại.";
        } elseif ($e->getCode() == 1452) {
            $_SESSION['failure'] = "Không thể thêm chương trình đào tạo: Giá trị không hợp lệ cho một trong các trường.";
        } else {
            $_SESSION['failure'] = "Đã xảy ra lỗi khi thêm chương trình đào tạo: " . $e->getMessage();
        }
        header('location: chuong_trinh_dao_tao.php');
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
            <h1 class="page-header">Thêm mới Chương trình đào tạo</h1>
        </div>
    </div>
    <form class="form" action="" method="post" id="chuong_trinh_form" enctype="multipart/form-data">
        <?php include_once ('./forms/chuong_trinh_dao_tao_form.php'); ?>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#chuong_trinh_form").validate({
            rules: {
                ten_chuong_trinh: {
                    required: true,
                    minlength: 3
                },
                doi_tuong: {
                    required: true
                },
                thoi_luong: {
                    required: true
                },
                hinh_thuc: {
                    required: true
                }
            }
        });
    });
</script>

<?php include BASE_PATH . '/includes/footer.php'; ?>