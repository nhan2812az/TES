<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
require_once BASE_PATH . '/lib/GiangVien/GiangVien.php';

$giang_vien = new GiangVien();
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');

$pagelimit = 15;
$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}

if (!$filter_col) {
    $filter_col = 'giang_vien_id';
}
if (!$order_by) {
    $order_by = 'Desc';
}

$db = getDbInstance();
$select = array('giang_vien.giang_vien_id', 'giang_vien.ngay_vao_dao_tao', 'giang_vien.chuyen_mon', 'giang_vien.trinh_do_hoc_van', 'giang_vien.kinh_nghiem_giang_day', 'giang_vien.noi_cong_tac', 'giang_vien.dia_chi', 'tai_khoan.ten AS tai_khoan_ten');

if ($search_string) {
    $db->where('giang_vien.chuyen_mon', '%' . $search_string . '%', 'like');
    $db->orwhere('giang_vien.trinh_do_hoc_van', '%' . $search_string . '%', 'like');
    $db->orwhere('giang_vien.noi_cong_tac', '%' . $search_string . '%', 'like');
}

if ($order_by) {
    $db->orderBy($filter_col, $order_by);
}

$db->pageLimit = $pagelimit;
$db->join('tai_khoan', 'giang_vien.tai_khoan_id = tai_khoan.id_tai_khoan', 'LEFT');
$rows = $db->arraybuilder()->paginate('giang_vien', $page, $select);
$total_pages = $db->totalPages;

include BASE_PATH . '/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Giảng viên</h1>
        </div>
        <div class="col-lg-6">
            <div class="page-action-links text-right">
                <a href="them_giang_vien.php?operation=create" class="btn btn-success"><i
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
                <?php
                foreach ($giang_vien->setOrderingValues() as $opt_value => $opt_name):
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
        <a href="xuat_giang_vien.php"><button class="btn btn-sm btn-primary">Xuất CSV <i
                    class="glyphicon glyphicon-export"></i></button></a>
    </div>

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="10%">Ngày vào đào tạo</th>
                <th width="15%">Chuyên môn</th>
                <th width="15%">Trình độ học vấn</th>
                <th width="10%">Kinh nghiệm giảng dạy</th>
                <th width="15%">Nơi công tác</th>
                <th width="15%">Địa chỉ</th>
                <th width="10%">Tài khoản</th>
                <th width="10%">Được phân công</th>
                <th width="10%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo $row['giang_vien_id']; ?></td>
                    <td><?php echo xss_clean($row['ngay_vao_dao_tao']); ?></td>
                    <td><?php echo xss_clean($row['chuyen_mon']); ?></td>
                    <td><?php echo xss_clean($row['trinh_do_hoc_van']); ?></td>
                    <td><?php echo xss_clean($row['kinh_nghiem_giang_day']); ?></td>
                    <td><?php echo xss_clean($row['noi_cong_tac']); ?></td>
                    <td><?php echo xss_clean($row['dia_chi']); ?></td>
                    <td><?php echo xss_clean($row['tai_khoan_ten']); ?></td>
                    <td>
                        <?php
                        $assigned_program = $giang_vien->getAssignedProgram($row['giang_vien_id']);
                        if ($assigned_program) {
                            echo "Đã được phân công ở chương trình " . $assigned_program['ten_chuong_trinh'];
                        } else {
                            echo "";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="flex">
                            <a href="sua_giang_vien.php?giang_vien_id=<?php echo $row['giang_vien_id']; ?>&operation=edit"
                                class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                            <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                                data-target="#confirm-delete-<?php echo $row['giang_vien_id']; ?>"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                            <?php
                            $assigned_program = $giang_vien->getAssignedProgram($row['giang_vien_id']);
                            if ($assigned_program) {
                                echo '<a href="#" class="btn btn-warning delete_assignment_btn" data-toggle="modal" data-target="#confirm-delete-assignment-' . $row['giang_vien_id'] . '"><i class="glyphicon glyphicon-remove"></i> Xóa phân công</a>';
                            } else {
                                echo '<a href="them_phan_cong_giang_vien.php?giang_vien_id=' . $row['giang_vien_id'] . '" class="btn btn-info"><i class="glyphicon glyphicon-user"></i> Phân công</a>';
                            }
                            ?>
                        </div>

                        <div class="modal fade" id="confirm-delete-assignment-<?php echo $row['giang_vien_id']; ?>"
                            role="dialog">
                            <div class="modal-dialog">
                                <form action="xoa_phan_cong_giang_vien.php" method="POST">
                                    <input type="hidden" name="giang_vien_id" value="<?php echo $row['giang_vien_id']; ?>">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Xác nhận xóa phân công</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bạn có chắc chắn muốn xóa phân công cho giảng viên này không?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Xóa phân công</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </td>
                </tr>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="confirm-delete-<?php echo $row['giang_vien_id']; ?>" role="dialog">
                    <div class="modal-dialog">
                        <form action="xoa_giang_vien.php" method="POST">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Xác nhận</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="del_id" id="del_id"
                                        value="<?php echo $row['giang_vien_id']; ?>">
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
        <?php echo paginationLinks($page, $total_pages, 'giang_vien.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>