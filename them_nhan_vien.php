<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_to_store = array_filter($_POST);

    $required_fields = array('ten', 'phong_ban', 'vi_tri', 'email', 'so_dien_thoai', 'vai_tro', 'ten_dang_nhap', 'mat_khau');
    foreach ($required_fields as $field) {
        if (empty($data_to_store[$field])) {
            echo 'Missing required field: ' . $field;
            exit();
        }
    }

    $data_to_store['mat_khau'] = md5($data_to_store['mat_khau']);

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if username already exists
    $check_sql = "SELECT ten_dang_nhap FROM tai_khoan WHERE ten_dang_nhap = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $data_to_store['ten_dang_nhap']);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $_SESSION['failure'] = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên đăng nhập khác.';
        header('location: nhan_vien.php');
        exit();
    }

    $sql = "INSERT INTO tai_khoan (ten, phong_ban, vi_tri, email, so_dien_thoai, vai_tro, ten_dang_nhap, mat_khau) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo 'Error preparing statement: ' . $conn->error;
        exit();
    }

    $stmt->bind_param(
        "ssssssss",
        $data_to_store['ten'],
        $data_to_store['phong_ban'],
        $data_to_store['vi_tri'],
        $data_to_store['email'],
        $data_to_store['so_dien_thoai'],
        $data_to_store['vai_tro'],
        $data_to_store['ten_dang_nhap'],
        $data_to_store['mat_khau']
    );

    if ($stmt->execute() === true) {
        $_SESSION['success'] = "Nhân viên đã được thêm thành công!";
        header('location: nhan_vien.php');
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
            <h2 class="page-header">Thêm mới Nhân viên</h2>
        </div>
    </div>
    <form class="form" action="" method="post" id="employee_form" enctype="multipart/form-data">
        <fieldset>
            <div class="form-group">
                <label for="ten">Họ và tên *</label>
                <input type="text" name="ten"
                    value="<?php echo isset($_POST['ten']) ? htmlspecialchars($_POST['ten'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    placeholder="Họ và tên" class="form-control" required="required" id="ten">
            </div>

            <div class="form-group">
                <label for="phong_ban">Phòng ban *</label>
                <input type="text" name="phong_ban"
                    value="<?php echo isset($_POST['phong_ban']) ? htmlspecialchars($_POST['phong_ban'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    placeholder="Phòng ban" class="form-control" required="required" id="phong_ban">
            </div>

            <div class="form-group">
                <label for="vi_tri">Vị trí *</label>
                <input type="text" name="vi_tri"
                    value="<?php echo isset($_POST['vi_tri']) ? htmlspecialchars($_POST['vi_tri'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    placeholder="Vị trí" class="form-control" required="required" id="vi_tri">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    placeholder="Địa chỉ Email" class="form-control" required="required" id="email">
            </div>

            <div class="form-group">
                <label for="so_dien_thoai">Số điện thoại</label>
                <input name="so_dien_thoai"
                    value="<?php echo isset($_POST['so_dien_thoai']) ? htmlspecialchars($_POST['so_dien_thoai'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    placeholder="987654321" class="form-control" type="text" id="so_dien_thoai">
            </div>

            <div class="form-group">
                <label for="vai_tro">Vai trò *</label>
                <select name="vai_tro" class="form-control" required="required" id="vai_tro">
                    <option value="NhanVien" <?php echo (isset($_POST['vai_tro']) && $_POST['vai_tro'] === 'NhanVien') ? 'selected' : ''; ?>>
                        Nhân viên</option>
                    <option value="GiangVien" <?php echo (isset($_POST['vai_tro']) && $_POST['vai_tro'] === 'GiangVien') ? 'selected' : ''; ?>>
                        Giảng viên</option>
                    <option value="QuanTriVien" <?php echo (isset($_POST['vai_tro']) && $_POST['vai_tro'] === 'QuanTriVien') ? 'selected' : ''; ?>>
                        Quản trị viên</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ten_dang_nhap">Tên đăng nhập *</label>
                <input type="text" name="ten_dang_nhap"
                    value="<?php echo isset($_POST['ten_dang_nhap']) ? htmlspecialchars($_POST['ten_dang_nhap'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    placeholder="Tên đăng nhập" class="form-control" required="required" id="ten_dang_nhap">
            </div>

            <div class="form-group">
                <label for="mat_khau">Mật khẩu *</label>
                <input type="password" name="mat_khau"
                    value="<?php echo isset($_POST['mat_khau']) ? htmlspecialchars($_POST['mat_khau'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    placeholder="Mật khẩu" class="form-control" required="required" id="mat_khau">
            </div>

            <div class="form-group text-center">
                <label></label>
                <button type="submit" class="btn btn-warning">Lưu <span
                        class="glyphicon glyphicon-send"></span></button>
            </div>
        </fieldset>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#employee_form").validate({
            rules: {
                ten: {
                    required: true,
                    minlength: 3
                },
                phong_ban: {
                    required: true
                },
                vi_tri: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                so_dien_thoai: {
                    digits: true,
                    minlength: 10
                },
                vai_tro: {
                    required: true
                },
                ten_dang_nhap: {
                    required: true
                },
                mat_khau: {
                    required: true,
                    minlength: 6
                }
            }
        });
    });
</script>

<?php include_once 'includes/footer.php'; ?>