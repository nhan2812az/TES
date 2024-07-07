<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$role = $_SESSION['user_role'];
$tai_khoan_id = $_SESSION['id_tai_khoan'];
$nhan_vien_id = filter_input(INPUT_GET, 'nhan_vien_id', FILTER_VALIDATE_INT);


if ($role != 'GiangVien' && $role != 'QuanTriVien') {
    die('Bạn không có quyền truy cập vào trang này.');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT * FROM tai_khoan WHERE id_tai_khoan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $nhan_vien_id);
$stmt->execute();
$result = $stmt->get_result();
$nhan_vien = $result->fetch_assoc();
$stmt->close();

$conn->close();

include BASE_PATH . '/includes/header.php';
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Chi tiết học viên</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Thông tin học viên
        </div>
        <div class="panel-body">
            <p><strong>ID:</strong> <?php echo $nhan_vien['id_tai_khoan']; ?></p>
            <p><strong>Tên:</strong> <?php echo $nhan_vien['ten']; ?></p>
            <p><strong>Phòng Ban:</strong> <?php echo $nhan_vien['phong_ban']; ?></p>
            <p><strong>Vị Trí:</strong> <?php echo $nhan_vien['vi_tri']; ?></p>
            <p><strong>Email:</strong> <?php echo $nhan_vien['email']; ?></p>
            <p><strong>Số Điện Thoại:</strong> <?php echo $nhan_vien['so_dien_thoai']; ?></p>
            <p><strong>Vai Trò:</strong> <?php echo $nhan_vien['vai_tro']; ?></p>
            <p><strong>Tên Đăng Nhập:</strong> <?php echo $nhan_vien['ten_dang_nhap']; ?></p>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/includes/footer.php'; ?>