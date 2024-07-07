<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_to_store = array_filter($_POST);

    $required_fields = array('nhan_vien_id', 'loai_ky_nang', 'muc_ky_nang', 'nhan_xet_quan_ly', 'ket_qua_khao_sat');
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

    $sql = "INSERT INTO nhu_cau_dao_tao (nhan_vien_id, loai_ky_nang, muc_ky_nang, nhan_xet_quan_ly, ket_qua_khao_sat) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        exit();
    }

    $stmt->bind_param("issss", $data_to_store['nhan_vien_id'], $data_to_store['loai_ky_nang'], $data_to_store['muc_ky_nang'], $data_to_store['nhan_xet_quan_ly'], $data_to_store['ket_qua_khao_sat']);

    try {
        if ($stmt->execute() === true) {
            $_SESSION['success'] = "Nhu cầu đào tạo đã được thêm thành công!";
            header('location: nhu_cau_dao_tao.php');
            exit();
        } else {
            echo 'Thêm không thành công: ' . $stmt->error;
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $_SESSION['failure'] = "Không thể thêm nhu cầu đào tạo: Nhu cầu đã tồn tại.";
        } elseif ($e->getCode() == 1452) {
            $_SESSION['failure'] = "Không thể thêm nhu cầu đào tạo: Giá trị không hợp lệ cho một trong các trường.";
        } else {
            $_SESSION['failure'] = "Đã xảy ra lỗi khi thêm nhu cầu đào tạo: " . $e->getMessage();
        }
        header('location: nhu_cau_dao_tao.php');
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
            <h2 class="page-header">Thêm mới Nhu cầu đào tạo</h2>
        </div>
    </div>
    <form class="form" action="" method="post" id="training_form" enctype="multipart/form-data">
        <?php include_once ('./forms/nhu_cau_dao_tao_form.php'); ?>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#training_form").validate({
            rules: {
                nhan_vien_id: {
                    required: true,
                    digits: true
                },
                loai_ky_nang: {
                    required: true
                },
                muc_ky_nang: {
                    required: true
                },
                nhan_xet_quan_ly: {
                    required: true
                },
                ket_qua_khao_sat: {
                    required: true
                }
            }
        });
    });
</script>

<?php include_once 'includes/footer.php'; ?>