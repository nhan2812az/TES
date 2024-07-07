<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$role = $_SESSION['user_role'];
$tai_khoan_id = $_SESSION['id_tai_khoan'];

$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');

$pagelimit = 15;
$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}

if (!$filter_col) {
    $filter_col = 'lich_trinh_id';
}
if (!$order_by) {
    $order_by = 'Desc';
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$giang_vien_id = null;
if ($role == 'GiangVien') {
    $stmt = $conn->prepare("SELECT giang_vien_id FROM giang_vien WHERE tai_khoan_id = ?");
    if ($stmt === false) {
        die("Lỗi khi chuẩn bị câu lệnh: " . $conn->error);
    }
    $stmt->bind_param("i", $tai_khoan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $giang_vien = $result->fetch_assoc();
        $giang_vien_id = $giang_vien['giang_vien_id'];
    }
    $stmt->close();
}

$sql = "SELECT lich_trinh_dao_tao.lich_trinh_id, chuong_trinh_dao_tao.ten_chuong_trinh, lich_trinh_dao_tao.ngay_bat_dau, lich_trinh_dao_tao.ngay_ket_thuc, lich_trinh_dao_tao.dia_diem 
        FROM lich_trinh_dao_tao 
        JOIN chuong_trinh_dao_tao ON lich_trinh_dao_tao.chuong_trinh_id = chuong_trinh_dao_tao.chuong_trinh_id";

if ($role == 'GiangVien' && $giang_vien_id !== null) {
    $sql .= " JOIN phan_cong_giang_vien ON lich_trinh_dao_tao.chuong_trinh_id = phan_cong_giang_vien.chuong_trinh_id WHERE phan_cong_giang_vien.giang_vien_id = ?";
}

if ($search_string) {
    $sql .= ($role == 'GiangVien' && $giang_vien_id !== null) ? " AND" : " WHERE";
    $sql .= " lich_trinh_dao_tao.dia_diem LIKE ?";
}

$sql .= " ORDER BY $filter_col $order_by LIMIT ?, ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Lỗi khi chuẩn bị câu lệnh: " . $conn->error);
}

$search_param = '%' . $search_string . '%';
$offset = ($page - 1) * $pagelimit;
$limit = $pagelimit;

if ($role == 'GiangVien' && $giang_vien_id !== null) {
    if ($search_string) {
        $stmt->bind_param("isii", $giang_vien_id, $search_param, $offset, $limit);
    } else {
        $stmt->bind_param("iii", $giang_vien_id, $offset, $limit);
    }
} else {
    if ($search_string) {
        $stmt->bind_param("sii", $search_param, $offset, $limit);
    } else {
        $stmt->bind_param("ii", $offset, $limit);
    }
}

$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

$count_sql = "SELECT COUNT(*) as count FROM lich_trinh_dao_tao";
if ($role == 'GiangVien' && $giang_vien_id !== null) {
    $count_sql .= " JOIN phan_cong_giang_vien ON lich_trinh_dao_tao.chuong_trinh_id = phan_cong_giang_vien.chuong_trinh_id WHERE phan_cong_giang_vien.giang_vien_id = ?";
}
if ($search_string) {
    $count_sql .= ($role == 'GiangVien' && $giang_vien_id !== null) ? " AND" : " WHERE";
    $count_sql .= " lich_trinh_dao_tao.dia_diem LIKE ?";
}

$count_stmt = $conn->prepare($count_sql);
if ($count_stmt === false) {
    die("Lỗi khi chuẩn bị câu lệnh: " . $conn->error);
}

if ($role == 'GiangVien' && $giang_vien_id !== null) {
    if ($search_string) {
        $count_stmt->bind_param("is", $giang_vien_id, $search_param);
    } else {
        $count_stmt->bind_param("i", $giang_vien_id);
    }
} else {
    if ($search_string) {
        $count_stmt->bind_param("s", $search_param);
    }
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['count'];
$total_pages = ceil($total_rows / $pagelimit);

include BASE_PATH . '/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Lịch trình đào tạo</h1>
        </div>
        <div class="col-lg-6">
            <div class="page-action-links text-right">
                <a href="them_lich_trinh_dao_tao.php?operation=create" class="btn btn-success"><i
                        class="glyphicon glyphicon-plus"></i> Thêm mới</a>
            </div>
        </div>
    </div>
    <?php include BASE_PATH . '/includes/flash_messages.php'; ?>

    <!-- Filters -->
    <div class="well text-center filter-form">
        <form class="form form-inline" action="">
            <label for="input_search">Tìm kiếm</label>
            <input type="text" class="form-control" id="input_search" name="search_string"
                value="<?php echo isset($search_string) ? xss_clean($search_string) : ''; ?>">

            <label for="input_order">Sắp xếp theo</label>
            <select name="filter_col" class="form-control">
                <option value="lich_trinh_id" <?php if ($filter_col == 'lich_trinh_id')
                    echo 'selected'; ?>>ID</option>
                <option value="ngay_bat_dau" <?php if ($filter_col == 'ngay_bat_dau')
                    echo 'selected'; ?>>Ngày bắt đầu
                </option>
                <option value="ngay_ket_thuc" <?php if ($filter_col == 'ngay_ket_thuc')
                    echo 'selected'; ?>>Ngày kết thúc
                </option>
                <option value="dia_diem" <?php if ($filter_col == 'dia_diem')
                    echo 'selected'; ?>>Địa điểm</option>
            </select>
            <select name="order_by" class="form-control" id="input_order">
                <option value="Asc" <?php if ($order_by == 'Asc')
                    echo 'selected'; ?>>Tăng dần</option>
                <option value="Desc" <?php if ($order_by == 'Desc')
                    echo 'selected'; ?>>Giảm dần</option>
            </select>
            <input type="submit" value="Đi" class="btn btn-primary">
        </form>
    </div>
    <hr>
    <!-- //Filters -->

    <div id="export-section">
        <a href="xuat_lich_trinh_dao_tao.php"><button class="btn btn-sm btn-primary">Xuất CSV <i
                    class="glyphicon glyphicon-export"></i></button></a>
    </div>

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Chương trình</th>
                <th width="20%">Ngày bắt đầu</th>
                <th width="20%">Ngày kết thúc</th>
                <th width="20%">Địa điểm</th>
                <th width="10%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo $row['lich_trinh_id']; ?></td>
                    <td><?php echo xss_clean($row['ten_chuong_trinh']); ?></td>
                    <td><?php echo xss_clean($row['ngay_bat_dau']); ?></td>
                    <td><?php echo xss_clean($row['ngay_ket_thuc']); ?></td>
                    <td><?php echo xss_clean($row['dia_diem']); ?></td>
                    <td>
                        <?php if ($role == 'QuanTriVien'): ?>
                            <a href="sua_lich_trinh_dao_tao.php?lich_trinh_id=<?php echo $row['lich_trinh_id']; ?>&operation=edit"
                                class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                            <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                                data-target="#confirm-delete-<?php echo $row['lich_trinh_id']; ?>"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        <?php endif; ?>
                        <?php if ($role == 'GiangVien'): ?>
                            <a href="xem_chi_tiet.php?lich_trinh_id=<?php echo $row['lich_trinh_id']; ?>"
                                class="btn btn-info"><i class="glyphicon glyphicon-eye-open"></i> Xem chi tiết</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="confirm-delete-<?php echo $row['lich_trinh_id']; ?>" role="dialog">
                    <div class="modal-dialog">
                        <form action="xoa_lich_trinh_dao_tao.php" method="POST">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Xác nhận xóa</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Bạn có chắc chắn muốn xóa lịch trình đào tạo này?</p>
                                    <input type="hidden" name="lich_trinh_id" value="<?php echo $row['lich_trinh_id']; ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="text-center">
        <ul class="pagination">
            <?php
            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($i == $page) ? 'active' : '';
                echo "<li class='$active'><a href='?page=$i'>$i</a></li>";
            }
            ?>
        </ul>
    </div>
</div>

<?php include BASE_PATH . '/includes/footer.php'; ?>