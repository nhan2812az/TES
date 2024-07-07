<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');

$pagelimit = 15;
$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}

if (!$filter_col) {
    $filter_col = 'danh_gia_id';
}
if (!$order_by) {
    $order_by = 'Desc';
}

$db = getDbInstance();
$select = array('danh_gia_id', 'nhan_vien_id', 'chuong_trinh_id', 'loai_danh_gia', 'diem_danh_gia', 'nhan_xet');

if ($search_string) {
    $db->where('loai_danh_gia', '%' . $search_string . '%', 'like');
}

if ($order_by) {
    $db->orderBy($filter_col, $order_by);
}

$db->pageLimit = $pagelimit;
$rows = $db->arraybuilder()->paginate('danh_gia_dao_tao', $page, $select);
$total_pages = $db->totalPages;

function getTenNhanVien($nhan_vien_id)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $sql = "SELECT ten FROM tai_khoan WHERE id_tai_khoan = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Lỗi khi chuẩn bị câu lệnh: ' . $conn->error);
    }

    $stmt->bind_param("i", $nhan_vien_id);
    $stmt->execute();
    $stmt->bind_result($ten_nhan_vien);
    $stmt->fetch();

    $stmt->close();
    $conn->close();

    return $ten_nhan_vien ? $ten_nhan_vien : 'Không xác định';
}

function getTenChuongTrinh($chuong_trinh_id)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $sql = "SELECT ten_chuong_trinh FROM chuong_trinh_dao_tao WHERE chuong_trinh_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Lỗi khi chuẩn bị câu lệnh: ' . $conn->error);
    }

    $stmt->bind_param("i", $chuong_trinh_id);
    $stmt->execute();
    $stmt->bind_result($ten_chuong_trinh);
    $stmt->fetch();

    $stmt->close();
    $conn->close();

    return $ten_chuong_trinh ? $ten_chuong_trinh : 'Không xác định';
}


include BASE_PATH . '/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Đánh giá đào tạo</h1>
        </div>
        <div class="col-lg-6">
            <div class="page-action-links text-right">
                <a href="them_danh_gia_dao_tao.php?operation=create" class="btn btn-success"><i
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
                <option value="danh_gia_id" <?php if ($filter_col == 'danh_gia_id')
                    echo 'selected'; ?>>ID</option>
                <option value="nhan_vien_id" <?php if ($filter_col == 'nhan_vien_id')
                    echo 'selected'; ?>>Nhân viên ID
                </option>
                <option value="chuong_trinh_id" <?php if ($filter_col == 'chuong_trinh_id')
                    echo 'selected'; ?>>Chương
                    trình ID
                </option>
                <option value="loai_danh_gia" <?php if ($filter_col == 'loai_danh_gia')
                    echo 'selected'; ?>>Loại đánh giá
                </option>
                <option value="diem_danh_gia" <?php if ($filter_col == 'diem_danh_gia')
                    echo 'selected'; ?>>Điểm đánh giá
                </option>
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



    <?php if ($role == 'QuanTriVien' || $role == 'GiangVien'): ?>
    <div id="export-section">
        <a href="xuat_danh_gia_dao_tao.php"><button class="btn btn-sm btn-primary">Xuất CSV <i
                    class="glyphicon glyphicon-export"></i></button></a>
    </div>
    <?php endif; ?>

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Tên nhân viên</th>
                <th width="15%">Tên chương trình</th>
                <th width="15%">Loại đánh giá</th>
                <th width="10%">Điểm đánh giá</th>
                <th width="30%">Nhận xét</th>
                <th width="10%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row['danh_gia_id']; ?></td>
                <td><?php echo htmlspecialchars(getTenNhanVien($row['nhan_vien_id'])); ?></td>
                <td><?php echo htmlspecialchars(getTenChuongTrinh($row['chuong_trinh_id'])); ?></td>
                <td><?php echo htmlspecialchars($row['loai_danh_gia']); ?></td>
                <td><?php echo htmlspecialchars($row['diem_danh_gia']); ?></td>
                <td><?php echo htmlspecialchars($row['nhan_xet']); ?></td>
                <td>
                    <?php if ($role == 'NhanVien'): ?>
                    <a href="sua_danh_gia_dao_tao.php?danh_gia_id=<?php echo $row['danh_gia_id']; ?>&operation=edit"
                        class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>

                    <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                        data-target="#confirm-delete-<?php echo $row['danh_gia_id']; ?>"><i
                            class="glyphicon glyphicon-trash"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="confirm-delete-<?php echo $row['danh_gia_id']; ?>" role="dialog">
                <div class="modal-dialog">
                    <form action="xoa_danh_gia_dao_tao.php" method="POST">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Xác nhận</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="del_id" id="del_id"
                                    value="<?php echo $row['danh_gia_id']; ?>">
                                <p>Bạn có chắc chắn muốn xóa hàng này không?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default pull-left">Có</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- //Delete Confirmation Modal -->
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- //Table -->


    <!-- Pagination -->
    <div class="text-center">
        <?php echo paginationLinks($page, $total_pages, 'danh_gia_dao_tao.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>