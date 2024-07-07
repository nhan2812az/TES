<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$role = $_SESSION['user_role'];
if ($role != 'GiangVien' && $role != 'QuanTriVien' && $role != 'NhanVien') {
    die('Bạn không có quyền truy cập vào trang này.');
}

if ($role == 'NhanVien') {
    $chuong_trinh_id = filter_input(INPUT_GET, 'chuong_trinh_dao_tao', FILTER_VALIDATE_INT);
}

$nhan_vien_id = filter_input(INPUT_GET, 'nhan_vien_id', FILTER_VALIDATE_INT);
if (!$nhan_vien_id) {
    die('ID nhân viên không hợp lệ.');
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if ($role == 'NhanVien') {
    $chuong_trinh_id = filter_input(INPUT_GET, 'chuong_trinh_dao_tao', FILTER_VALIDATE_INT);
    if (!$chuong_trinh_id) {
        die('ID chương trình đào tạo không hợp lệ.');
    }

    // Query program details based on $chuong_trinh_id
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $sql_program = "SELECT chuong_trinh_dao_tao.*, lich_trinh_dao_tao.ngay_bat_dau, lich_trinh_dao_tao.ngay_ket_thuc 
                    FROM chuong_trinh_dao_tao 
                    LEFT JOIN lich_trinh_dao_tao 
                    ON chuong_trinh_dao_tao.chuong_trinh_id = lich_trinh_dao_tao.chuong_trinh_id 
                    WHERE chuong_trinh_dao_tao.chuong_trinh_id = ?";

    $stmt_program = $conn->prepare($sql_program);
    $stmt_program->bind_param("i", $chuong_trinh_id);
    $stmt_program->execute();
    $result_program = $stmt_program->get_result();
    $program = $result_program->fetch_assoc();
    $stmt_program->close();

    if (!$program) {
        die('Không tìm thấy chương trình đào tạo.');
    }

    // Query contents based on $chuong_trinh_id
    $sql_contents = "SELECT noi_dung_dao_tao.*, 
                            IFNULL(tien_do_hoc_tap.trang_thai, 'Chưa hoàn thành') AS trang_thai_tien_do
                     FROM noi_dung_dao_tao 
                     LEFT JOIN tien_do_hoc_tap 
                     ON noi_dung_dao_tao.noi_dung_id = tien_do_hoc_tap.noi_dung_id 
                     AND tien_do_hoc_tap.nhan_vien_id = ? 
                     WHERE noi_dung_dao_tao.chuong_trinh_id = ?";

    $stmt_contents = $conn->prepare($sql_contents);
    $stmt_contents->bind_param("ii", $nhan_vien_id, $chuong_trinh_id);
    $stmt_contents->execute();
    $result_contents = $stmt_contents->get_result();
    $contents = $result_contents->fetch_all(MYSQLI_ASSOC);
    $stmt_contents->close();

    $conn->close();
} else {
    // For GiangVien and QuanTriVien roles, query the latest program for the employee
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $sql_program = "SELECT chuong_trinh_dao_tao.*, lich_trinh_dao_tao.ngay_bat_dau, lich_trinh_dao_tao.ngay_ket_thuc 
                    FROM dang_ky_dao_tao 
                    JOIN chuong_trinh_dao_tao ON dang_ky_dao_tao.chuong_trinh_id = chuong_trinh_dao_tao.chuong_trinh_id 
                    JOIN lich_trinh_dao_tao ON dang_ky_dao_tao.chuong_trinh_id = lich_trinh_dao_tao.chuong_trinh_id 
                    WHERE dang_ky_dao_tao.tai_khoan_id = ? 
                    ORDER BY lich_trinh_dao_tao.ngay_bat_dau DESC 
                    LIMIT 1";

    $stmt_program = $conn->prepare($sql_program);
    $stmt_program->bind_param("i", $nhan_vien_id);
    $stmt_program->execute();
    $result_program = $stmt_program->get_result();
    $program = $result_program->fetch_assoc();
    $stmt_program->close();

    if (!$program) {
        die('Không tìm thấy chương trình đào tạo.');
    }

    // Query contents based on $program['chuong_trinh_id']
    $sql_contents = "SELECT noi_dung_dao_tao.*, 
                            IFNULL(tien_do_hoc_tap.trang_thai, 'Chưa hoàn thành') AS trang_thai_tien_do
                     FROM noi_dung_dao_tao 
                     LEFT JOIN tien_do_hoc_tap 
                     ON noi_dung_dao_tao.noi_dung_id = tien_do_hoc_tap.noi_dung_id 
                     AND tien_do_hoc_tap.nhan_vien_id = ? 
                     WHERE noi_dung_dao_tao.chuong_trinh_id = ?";

    $stmt_contents = $conn->prepare($sql_contents);
    $stmt_contents->bind_param("ii", $nhan_vien_id, $program['chuong_trinh_id']);
    $stmt_contents->execute();
    $result_contents = $stmt_contents->get_result();
    $contents = $result_contents->fetch_all(MYSQLI_ASSOC);
    $stmt_contents->close();

    $conn->close();
}


include BASE_PATH . '/includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tiến độ học viên</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Thông tin chương trình đào tạo hiện tại
        </div>
        <div class="panel-body">
            <p><strong>Tên Chương Trình:</strong> <?php echo $program['ten_chuong_trinh']; ?></p>
            <p><strong>Ngày Bắt Đầu:</strong> <?php echo $program['ngay_bat_dau']; ?></p>
            <p><strong>Ngày Kết Thúc:</strong> <?php echo $program['ngay_ket_thuc']; ?></p>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Các nội dung đào tạo của chương trình
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Nội Dung</th>
                        <th>Tiêu Đề</th>
                        <th>Mô Tả</th>
                        <th>Trạng Thái Tiến Độ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contents as $content): ?>
                        <tr>
                            <td><?php echo $content['noi_dung_id']; ?></td>
                            <td><?php echo $content['tieu_de']; ?></td>
                            <td><?php echo $content['mo_ta']; ?></td>
                            <td><?php echo $content['trang_thai_tien_do'] === 'Đã hoàn thành' ? '<span style="color: green;">Đã hoàn thành</span>' : '<span style="color: red;">Chưa hoàn thành</span>'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php include BASE_PATH . '/includes/footer.php'; ?>