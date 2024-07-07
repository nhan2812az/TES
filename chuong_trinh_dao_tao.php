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
    $filter_col = 'chuong_trinh_id';
}
if (!$order_by) {
    $order_by = 'Desc';
}

$db = getDbInstance();
$select = array('chuong_trinh_id', 'ten_chuong_trinh', 'doi_tuong', 'thoi_luong', 'hinh_thuc');

if ($search_string) {
    $db->where('ten_chuong_trinh', '%' . $search_string . '%', 'like');
}

if ($order_by) {
    $db->orderBy($filter_col, $order_by);
}

$db->pageLimit = $pagelimit;
$rows = $db->arraybuilder()->paginate('chuong_trinh_dao_tao', $page, $select);
$total_pages = $db->totalPages;

$chuong_trinh_ids = array_column($rows, 'chuong_trinh_id');
$nhan_vien_id = $_SESSION['id_tai_khoan'];
foreach ($rows as $row) {
    $db->where('tai_khoan_id', $nhan_vien_id);
    $db->where('chuong_trinh_id', $row['chuong_trinh_id']);
    $dang_ky = $db->getOne('dang_ky_dao_tao', ['trang_thai']);
    if ($dang_ky) {
        $trang_thai_dang_ky[$row['chuong_trinh_id']] = $dang_ky['trang_thai'];
    } else {
        $trang_thai_dang_ky[$row['chuong_trinh_id']] = 'Chưa đăng ký';
    }
}


include BASE_PATH . '/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Chương trình đào tạo</h1>
        </div>
        <div class="col-lg-6">
            <?php if ($role == 'QuanTriVien'): ?>
                <div class="page-action-links text-right">
                    <a href="them_chuong_trinh_dao_tao.php?operation=create" class="btn btn-success"><i
                            class="glyphicon glyphicon-plus"></i> Thêm mới</a>
                </div>
            <?php endif; ?>
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
                <option value="chuong_trinh_id" <?php echo $filter_col === 'chuong_trinh_id' ? 'selected' : ''; ?>>ID
                </option>
                <option value="ten_chuong_trinh" <?php echo $filter_col === 'ten_chuong_trinh' ? 'selected' : ''; ?>>Tên
                    chương trình</option>
            </select>
            <select name="order_by" class="form-control" id="input_order">
                <option value="Asc" <?php echo $order_by === 'Asc' ? 'selected' : ''; ?>>Tăng dần</option>
                <option value="Desc" <?php echo $order_by === 'Desc' ? 'selected' : ''; ?>>Giảm dần</option>
            </select>
            <input type="submit" value="Đi" class="btn btn-primary">
        </form>
    </div>
    <hr>
    <!-- //Filters -->

    <?php if ($role == 'QuanTriVien' || $role == 'GiangVien'): ?>
        <div id="export-section">
            <a href="xuat_chuong_trinh.php"><button class="btn btn-sm btn-primary">Xuất CSV <i
                        class="glyphicon glyphicon-export"></i></button></a>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="35%">Tên chương trình</th>
                <th width="20%">Đối tượng</th>
                <th width="20%">Thời lượng</th>
                <th width="10%">Hình thức</th>
                <?php if ($role == 'NhanVien'): ?>
                    <th width="10%">Trạng thái</th>
                <?php endif; ?>
                <th width="10%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo $row['chuong_trinh_id']; ?></td>
                    <td><?php echo xss_clean($row['ten_chuong_trinh']); ?></td>
                    <td><?php echo xss_clean($row['doi_tuong']); ?></td>
                    <td><?php echo xss_clean($row['thoi_luong']); ?></td>
                    <td><?php echo xss_clean($row['hinh_thuc']); ?></td>
                    <?php if ($role == 'NhanVien'): ?>
                        <td><?php echo isset($trang_thai_dang_ky[$row['chuong_trinh_id']]) ? xss_clean($trang_thai_dang_ky[$row['chuong_trinh_id']]) : 'Chờ duyệt'; ?>
                        </td>
                    <?php endif; ?>
                    <td>
                        <div class="flex">
                            <?php if ($role == 'QuanTriVien'): ?>
                                <a href="sua_chuong_trinh.php?chuong_trinh_id=<?php echo $row['chuong_trinh_id']; ?>&operation=edit"
                                    class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                                <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                                    data-target="#confirm-delete-<?php echo $row['chuong_trinh_id']; ?>"><i
                                        class="glyphicon glyphicon-trash"></i></a>
                            <?php endif; ?>

                            <?php if ($role == 'NhanVien'): ?>
                                <?php if (isset($trang_thai_dang_ky[$row['chuong_trinh_id']]) && $trang_thai_dang_ky[$row['chuong_trinh_id']] == 'Đã duyệt'): ?>
                                    <a href="chi_tiet_chuong_trinh.php?chuong_trinh_id=<?php echo $row['chuong_trinh_id']; ?>"
                                        class="btn btn-info"><i class="glyphicon glyphicon-info-sign"></i> Xem chi tiết</a>
                                <?php elseif (isset($trang_thai_dang_ky[$row['chuong_trinh_id']]) && $trang_thai_dang_ky[$row['chuong_trinh_id']] == 'Chờ duyệt'): ?>
                                    <a href="#" class="btn btn-success btn-disabled"><i class="glyphicon glyphicon-plus"></i> Đăng
                                        ký đào tạo</a>
                                <?php else: ?>
                                    <a href="them_dang_ky_dao_tao.php?chuong_trinh_id=<?php echo $row['chuong_trinh_id']; ?>"
                                        class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Đăng ký đào tạo</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="confirm-delete-<?php echo $row['chuong_trinh_id']; ?>" role="dialog">
                    <div class="modal-dialog">
                        <form action="xoa_chuong_trinh.php" method="POST">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Xác nhận</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="del_id" id="del_id"
                                        value="<?php echo $row['chuong_trinh_id']; ?>">
                                    <p>Bạn có chắc chắn muốn xóa hàng này không?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-default pull-left">Có</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                                </div </div>
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
        <?php echo paginationLinks($page, $total_pages, 'chuong_trinh_dao_tao.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>