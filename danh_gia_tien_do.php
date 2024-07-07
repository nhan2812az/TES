<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$noi_dung_id = filter_input(INPUT_GET, 'noi_dung_id', FILTER_VALIDATE_INT);
if (!$noi_dung_id) {
    die('ID nội dung không hợp lệ.');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT noi_dung_dao_tao.*, chuong_trinh_dao_tao.ten_chuong_trinh 
        FROM noi_dung_dao_tao 
        JOIN chuong_trinh_dao_tao ON noi_dung_dao_tao.chuong_trinh_id = chuong_trinh_dao_tao.chuong_trinh_id 
        WHERE noi_dung_dao_tao.noi_dung_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $noi_dung_id);
$stmt->execute();
$result = $stmt->get_result();
$noi_dung = $result->fetch_assoc();
$stmt->close();

$sql = "SELECT tai_khoan.id_tai_khoan, tai_khoan.ten, dang_ky_dao_tao.dang_ky_id, tien_do_hoc_tap.trang_thai AS trang_thai_danh_gia
        FROM tai_khoan 
        JOIN dang_ky_dao_tao ON tai_khoan.id_tai_khoan = dang_ky_dao_tao.tai_khoan_id 
        LEFT JOIN tien_do_hoc_tap ON tai_khoan.id_tai_khoan = tien_do_hoc_tap.nhan_vien_id 
            AND tien_do_hoc_tap.noi_dung_id = ?
        WHERE dang_ky_dao_tao.chuong_trinh_id = ? 
        GROUP BY tai_khoan.id_tai_khoan";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $noi_dung_id, $noi_dung['chuong_trinh_id']);
$stmt->execute();
$nhan_viens = $stmt->get_result();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trang_thai = filter_input(INPUT_POST, 'trang_thai', FILTER_SANITIZE_STRING);
    $ghi_chu = filter_input(INPUT_POST, 'ghi_chu', FILTER_SANITIZE_STRING);

    $sql = "INSERT INTO tien_do_hoc_tap (nhan_vien_id, chuong_trinh_id, noi_dung_id, trang_thai, ngay_cap_nhat, ghi_chu)
            VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);

    foreach ($_POST['nhan_vien'] as $nhan_vien_id) {
        $stmt->bind_param("iiiss", $nhan_vien_id, $noi_dung['chuong_trinh_id'], $noi_dung_id, $trang_thai, $ghi_chu);
        $stmt->execute();
    }

    $stmt->close();

    $_SESSION['success'] = 'Đánh giá tiến độ thành công!';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$conn->close();

include BASE_PATH . '/includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Đánh giá tiến độ học tập</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Thông tin nội dung đào tạo
        </div>
        <div class="panel-body">
            <p><strong>ID Nội Dung:</strong> <?php echo $noi_dung['noi_dung_id']; ?></p>
            <p><strong>Tên Chương Trình:</strong> <?php echo $noi_dung['ten_chuong_trinh']; ?></p>
            <p><strong>Tiêu Đề:</strong> <?php echo $noi_dung['tieu_de']; ?></p>
            <p><strong>Mô Tả:</strong> <?php echo $noi_dung['mo_ta']; ?></p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Danh sách nhân viên trong khóa học
        </div>
        <div class="panel-body">
            <form method="post" action="">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>ID Nhân Viên</th>
                            <th>Tên Nhân Viên</th>
                            <th>Trạng Thái Đánh Giá</th>
                            <th>Chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($nhan_vien = $nhan_viens->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $nhan_vien['id_tai_khoan']; ?></td>
                                <td><?php echo $nhan_vien['ten']; ?></td>
                                <td>
                                    <?php if ($nhan_vien['trang_thai_danh_gia']): ?>
                                        <span class="text-success" style="font-weight: bold">Đã đánh giá</span>
                                    <?php else: ?>
                                        <span class="text-danger" style="font-weight: bold">Chưa đánh giá</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (!$nhan_vien['trang_thai_danh_gia']): ?>
                                        <input type="checkbox" name="nhan_vien[]"
                                            value="<?php echo $nhan_vien['id_tai_khoan']; ?>">
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <label for="trang_thai">Trạng Thái</label>
                    <select class="form-control" id="trang_thai" name="trang_thai" required>
                        <option value="HoanThanh">Đã hoàn thành</option>
                        <option value="ChuaHoanThanh">Chưa hoàn thành</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ghi_chu">Ghi Chú</label>
                    <textarea class="form-control" id="ghi_chu" name="ghi_chu"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Hoàn thành</button>
                <a href="noi_dung_dao_tao.php" class="btn btn-default">Hủy</a>
            </form>
        </div>
    </div>
</div>
<?php include BASE_PATH . '/includes/footer.php'; ?>