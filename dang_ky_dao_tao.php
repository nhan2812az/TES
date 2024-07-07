<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

require_once BASE_PATH . '/lib/DangKyDaoTao/DangKyDaoTao.php';
$dang_ky_dao_tao = new DangKyDaoTao();

$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');

$pagelimit = 15;

$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}

if (!$filter_col) {
    $filter_col = 'dang_ky_id';
}
if (!$order_by) {
    $order_by = 'Desc';
}

$db = getDbInstance();
$select = array('dang_ky_id', 'tai_khoan_id', 'chuong_trinh_id', 'trang_thai', 'ngay_dang_ky');

if ($search_string) {
    $db->where('tai_khoan_id', '%' . $search_string . '%', 'like');
    $db->orwhere('chuong_trinh_id', '%' . $search_string . '%', 'like');
}

if ($order_by) {
    $db->orderBy($filter_col, $order_by);
}

$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('dang_ky_dao_tao', $page, $select);
$total_pages = $db->totalPages;

include BASE_PATH . '/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Đăng ký Đào tạo</h1>
        </div>
        <!-- <div class="col-lg-6">
            <div class="page-action-links text-right">
                <a href="them_dang_ky_dao_tao.php?operation=create" class="btn btn-success"><i
                        class="glyphicon glyphicon-plus"></i> Thêm mới</a>
            </div>
        </div> -->
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
                <?php
                foreach ($dang_ky_dao_tao->setOrderingValues() as $opt_value => $opt_name):
                    ($order_by === $opt_value) ? $selected = 'selected' : $selected = '';
                    echo ' <option value="' . $opt_value . '" ' . $selected . '>' . $opt_name . '</option>';
                endforeach;
                ?>
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
        <a href="xuat_dang_ky_dao_tao.php"><button class="btn btn-sm btn-primary">Xuất CSV <i
                    class="glyphicon glyphicon-export"></i></button></a>
    </div>

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Nhân viên</th>
                <th width="25%">Chương trình đào tạo</th>
                <th width="20%">Trạng thái</th>
                <th width="15%">Ngày đăng ký</th>
                <th width="10%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo $row['dang_ky_id']; ?></td>
                    <td><?php echo xss_clean(getUserNameFromTaiKhoanId($row['tai_khoan_id'])); ?></td>
                    <td><?php echo xss_clean(getTenChuongTrinhFromChuongTrinhId($row['chuong_trinh_id'])); ?></td>
                    <td><?php echo xss_clean($row['trang_thai']); ?></td>
                    <td><?php echo xss_clean($row['ngay_dang_ky']); ?></td>
                    <td>
                        <div class="flex">
                            <a href="sua_dang_ky_dao_tao.php?dang_ky_id=<?php echo $row['dang_ky_id']; ?>&operation=edit"
                                class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                            <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                                data-target="#confirm-delete-<?php echo $row['dang_ky_id']; ?>"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                            <a href="phe_duyet_dang_ky.php?dang_ky_id=<?php echo $row['dang_ky_id']; ?>"
                                class="btn btn-success"><i class="glyphicon glyphicon-check"></i> Phê duyệt</a>
                        </div>
                    </td>

                </tr>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="confirm-delete-<?php echo $row['dang_ky_id']; ?>" role="dialog">
                    <div class="modal-dialog">
                        <form action="xoa_dang_ky_dao_tao.php" method="POST">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Xác nhận</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="del_id" id="del_id"
                                        value="<?php echo $row['dang_ky_id']; ?>">
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
        <?php echo paginationLinks($page, $total_pages, 'dang_ky_dao_tao.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php
function getUserNameFromTaiKhoanId($id_tai_khoan)
{
    $db = getDbInstance();
    $db->where('id_tai_khoan', $id_tai_khoan);
    $nhan_vien = $db->getOne('tai_khoan', 'ten');

    if ($nhan_vien) {
        return $nhan_vien['ten'];
    } else {
        return 'Không xác định';
    }
}
function getTenChuongTrinhFromChuongTrinhId($chuong_trinh_id)
{
    $db = getDbInstance();
    $db->where('chuong_trinh_id', $chuong_trinh_id);
    $chuong_trinh = $db->getOne('chuong_trinh_dao_tao', 'ten_chuong_trinh');

    if ($chuong_trinh) {
        return $chuong_trinh['ten_chuong_trinh'];
    } else {
        return 'Không xác định';
    }
}



?>

<?php include BASE_PATH . '/includes/footer.php'; ?>