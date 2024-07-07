<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$role = $_SESSION['user_role'];
$tai_khoan_id = $_SESSION['id_tai_khoan'];
$lich_trinh_id = filter_input(INPUT_GET, 'lich_trinh_id', FILTER_VALIDATE_INT);

if ($role != 'GiangVien' && $role != 'QuanTriVien') {
    die('Bạn không có quyền truy cập vào trang này.');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT lich_trinh_dao_tao.*, chuong_trinh_dao_tao.ten_chuong_trinh 
        FROM lich_trinh_dao_tao 
        JOIN chuong_trinh_dao_tao ON lich_trinh_dao_tao.chuong_trinh_id = chuong_trinh_dao_tao.chuong_trinh_id 
        WHERE lich_trinh_dao_tao.lich_trinh_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lich_trinh_id);
$stmt->execute();
$result = $stmt->get_result();
$lich_trinh = $result->fetch_assoc();
$chuong_trinh_id = $lich_trinh['chuong_trinh_id'];
$stmt->close();

$sql = "SELECT dang_ky_dao_tao.*, tai_khoan.ten AS ten_nhan_vien 
        FROM dang_ky_dao_tao 
        JOIN tai_khoan ON dang_ky_dao_tao.tai_khoan_id = tai_khoan.id_tai_khoan 
        WHERE dang_ky_dao_tao.chuong_trinh_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chuong_trinh_id);
$stmt->execute();
$result = $stmt->get_result();
$hoc_vien = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();

include BASE_PATH . '/includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Chi tiết lịch trình đào tạo</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Thông tin lịch trình
        </div>
        <div class="panel-body">
            <p><strong>ID Lịch Trình:</strong> <?php echo $lich_trinh['lich_trinh_id']; ?></p>
            <p><strong>Tên Chương Trình:</strong> <?php echo $lich_trinh['ten_chuong_trinh']; ?></p>
            <p><strong>Ngày Bắt Đầu:</strong> <?php echo $lich_trinh['ngay_bat_dau']; ?></p>
            <p><strong>Ngày Kết Thúc:</strong> <?php echo $lich_trinh['ngay_ket_thuc']; ?></p>
            <p><strong>Địa Điểm:</strong> <?php echo $lich_trinh['dia_diem']; ?></p>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Danh sách học viên
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Đăng Ký</th>
                        <th>Tên Học Viên</th>
                        <th>Ngày Đăng Ký</th>
                        <th>Trạng Thái</th>
                        <?php if ($role == 'GiangVien'): ?>
                            <th>Hành động</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hoc_vien as $hv): ?>
                        <tr>
                            <td><?php echo $hv['dang_ky_id']; ?></td>
                            <td><?php echo $hv['ten_nhan_vien']; ?></td>
                            <td><?php echo $hv['ngay_dang_ky']; ?></td>
                            <td><?php echo $hv['trang_thai']; ?></td>
                            <?php if ($role == 'GiangVien'): ?>
                                <td>
                                    <a href="xem_thong_tin_hoc_vien.php?nhan_vien_id=<?php echo $hv['tai_khoan_id']; ?>"
                                        class="btn btn-primary">Xem Thông Tin</a>
                                    <a href="xem_tien_do_hoc_vien.php?nhan_vien_id=<?php echo $hv['tai_khoan_id']; ?>"
                                        class="btn btn-info">Xem Tiến Độ</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include BASE_PATH . '/includes/footer.php'; ?>