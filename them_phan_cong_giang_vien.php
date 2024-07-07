<?php
session_start();
require_once './config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

function themThongBao($tai_khoan_id, $noi_dung)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO thong_bao (tai_khoan_id, noi_dung) VALUES (?, ?)");
    if ($stmt === false) {
        $_SESSION['failure'] = 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        header('location: giang_vien.php');
        exit();
    }
    $stmt->bind_param("is", $tai_khoan_id, $noi_dung);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function getTaiKhoanIdFromGiangVien($giang_vien_id)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT tai_khoan_id FROM giang_vien WHERE giang_vien_id = ?");
    if ($stmt === false) {
        $_SESSION['failure'] = 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        header('location: giang_vien.php');
        exit();
    }
    $stmt->bind_param("i", $giang_vien_id);
    $stmt->execute();
    $stmt->bind_result($tai_khoan_id);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    return $tai_khoan_id;
}

function getGiangVienList()
{
    $db = getDbInstance();
    $db->join("tai_khoan t", "g.tai_khoan_id = t.id_tai_khoan", "LEFT");
    $giang_vien = $db->get('giang_vien g', null, ['g.giang_vien_id', 't.ten']);
    return $giang_vien;
}

function getChuongTrinhDaoTaoList()
{
    $db = getDbInstance();
    $chuong_trinh_dao_tao = $db->get('chuong_trinh_dao_tao', null, ['chuong_trinh_id', 'ten_chuong_trinh']);
    return $chuong_trinh_dao_tao;
}

$giang_vien_list = getGiangVienList();
$chuong_trinh_dao_tao_list = getChuongTrinhDaoTaoList();

$selected_giang_vien_id = filter_input(INPUT_GET, 'giang_vien_id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_to_store = array_map('trim', $_POST);
    $required_fields = array('chuong_trinh_id', 'giang_vien_id');

    foreach ($required_fields as $field) {
        if (empty($data_to_store[$field])) {
            $_SESSION['failure'] = 'Thiếu trường bắt buộc: ' . $field;
            header('location: giang_vien.php');
            exit();
        }
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $query_check_assignment = "SELECT * FROM phan_cong_giang_vien WHERE chuong_trinh_id = ? AND giang_vien_id = ?";
    $stmt_check = $conn->prepare($query_check_assignment);
    if ($stmt_check === false) {
        $_SESSION['failure'] = 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        header('location: giang_vien.php');
        exit();
    }
    $stmt_check->bind_param("ii", $data_to_store['chuong_trinh_id'], $data_to_store['giang_vien_id']);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['failure'] = 'Giảng viên đã có phân công cho chương trình đào tạo này.';
        header('location: giang_vien.php');
        exit();
    }

    $sql = "INSERT INTO phan_cong_giang_vien (chuong_trinh_id, giang_vien_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $_SESSION['failure'] = 'Lỗi khi chuẩn bị câu lệnh: ' . $conn->error;
        header('location: giang_vien.php');
        exit();
    }
    $stmt->bind_param("ii", $data_to_store['chuong_trinh_id'], $data_to_store['giang_vien_id']);

    try {
        if ($stmt->execute() === true) {
            $tai_khoan_id = getTaiKhoanIdFromGiangVien($data_to_store['giang_vien_id']);
            themThongBao($tai_khoan_id, 'Bạn đã được phân công vào chương trình đào tạo mới.');
            $_SESSION['success'] = "Phân công giảng viên đã được thêm thành công!";
            header('location: giang_vien.php');
            exit();
        } else {
            $_SESSION['failure'] = 'Thêm không thành công: ' . $stmt->error;
            header('location: giang_vien.php');
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1452) {
            $_SESSION['failure'] = "Không thể thêm phân công giảng viên: Giá trị không hợp lệ cho một trong các trường.";
        } else {
            $_SESSION['failure'] = "Đã xảy ra lỗi khi thêm phân công giảng viên: " . $e->getMessage();
        }
        header('location: giang_vien.php');
        exit();
    } finally {
        $stmt->close();
        $stmt_check->close();
        $conn->close();
    }
}

$edit = false;

require_once BASE_PATH . '/includes/header.php';
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Thêm mới Phân công giảng viên</h1>
        </div>
    </div>
    <form class="form" action="" method="post" id="phan_cong_form">
        <div class="form-group">
            <label for="chuong_trinh_id">Chương trình đào tạo</label>
            <select name="chuong_trinh_id" id="chuong_trinh_id" class="form-control" required>
                <option value="">Chọn chương trình đào tạo</option>
                <?php foreach ($chuong_trinh_dao_tao_list as $chuong_trinh): ?>
                    <option value="<?php echo $chuong_trinh['chuong_trinh_id']; ?>">
                        <?php echo $chuong_trinh['ten_chuong_trinh']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="giang_vien_id">Giảng viên</label>
            <select name="giang_vien_id" id="giang_vien_id" class="form-control" readonly>
                <option value="">Chọn giảng viên</option>
                <?php foreach ($giang_vien_list as $giang_vien): ?>
                    <?php
                    $selected = '';
                    if ($giang_vien['giang_vien_id'] == $selected_giang_vien_id) {
                        $selected = 'selected';
                    }
                    ?>
                    <option value="<?php echo $giang_vien['giang_vien_id']; ?>" <?php echo $selected; ?>>
                        <?php echo $giang_vien['ten']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="giang_vien_id" id="giang_vien_id_hidden"
                value="<?php echo $selected_giang_vien_id; ?>">
        </div>

        <div class="form-group">
            <input type="submit" value="Lưu" class="btn btn-primary">
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // Update hidden input value when the select value changes
        $('#giang_vien_id').change(function () {
            $('#giang_vien_id_hidden').val($(this).val());
        });

        $("#phan_cong_form").validate({
            rules: {
                chuong_trinh_id: {
                    required: true,
                    digits: true
                }
            }
        });
    });
</script>

<?php include BASE_PATH . '/includes/footer.php'; ?>