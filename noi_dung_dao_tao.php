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
    $filter_col = 'noi_dung_id';
}
if (!$order_by) {
    $order_by = 'Desc';
}

function getLoaiNoiDungOptions()
{
    return [
        'theo_chuong_trinh' => 'Theo chương trình đào tạo',
        'theo_chu_de' => 'Theo chủ đề'
    ];
}

$db = getDbInstance();
$select = array('noi_dung_id', 'chuong_trinh_id', 'loai_noi_dung', 'tieu_de', 'mo_ta', 'duong_dan_tap_tin', 'chu_de');

if ($search_string) {
    $db->where('tieu_de', '%' . $search_string . '%', 'like');
    $db->orwhere('mo_ta', '%' . $search_string . '%', 'like');
}

if ($order_by) {
    $db->orderBy($filter_col, $order_by);
}

$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('noi_dung_dao_tao', $page, $select);
$total_pages = $db->totalPages;

include BASE_PATH . '/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Nội dung đào tạo</h1>
        </div>
        <div class="col-lg-6">
            <div class="page-action-links text-right">
                <a href="them_noi_dung_dao_tao.php?operation=create" class="btn btn-success"><i
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
                $ordering = array(
                    'noi_dung_id' => 'ID',
                    'chuong_trinh_id' => 'Chương trình',
                    'loai_noi_dung' => 'Loại nội dung',
                    'tieu_de' => 'Tiêu đề',
                    'mo_ta' => 'Mô tả',
                    'duong_dan_tap_tin' => 'Đường dẫn tệp tin',
                    'chu_de' => 'Chủ đề',
                );
                foreach ($ordering as $opt_value => $opt_name):
                    ($order_by === $opt_value) ? $selected = 'selected' : $selected = '';
                    echo ' <option value="' . $opt_value . '" ' . $selected . '>' . $opt_name . '</option>';
                endforeach;
                ?>
            </select>
            <select name="order_by" class="form-control" id="input_order">
                <option value="Asc" <?php if ($order_by == 'Asc') {
                    echo 'selected';
                } ?>>Tăng dần</option>
                <option value="Desc" <?php if ($order_by == 'Desc') {
                    echo 'selected';
                } ?>>Giảm dần</option>
            </select>
            <input type="submit" value="Đi" class="btn btn-primary">
        </form>
    </div>
    <hr>
    <!-- //Filters -->

    <div id="export-section">
        <a href="xuat_noi_dung_dao_tao.php"><button class="btn btn-sm btn-primary">Xuất CSV <i
                    class="glyphicon glyphicon-export"></i></button></a>
    </div>

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="10%">Chương trình</th>
                <th width="15%">Loại nội dung</th>
                <th width="20%">Tiêu đề</th>
                <th width="25%">Mô tả</th>
                <th width="15%">Đường dẫn tệp tin</th>
                <th width="10%">Chủ đề</th>
                <th width="10%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row['noi_dung_id']; ?></td>
                <td><?php echo xss_clean($row['chuong_trinh_id']); ?></td>
                <td><?php echo isset($row['loai_noi_dung']) ? getLoaiNoiDungOptions()[$row['loai_noi_dung']] : ''; ?>
                </td>
                <td><?php echo xss_clean($row['tieu_de']); ?></td>
                <td><?php echo xss_clean($row['mo_ta']); ?></td>
                <td><?php echo xss_clean($row['duong_dan_tap_tin']); ?></td>
                <td><?php echo xss_clean($row['chu_de']); ?></td>
                <td>
                    <div class="flex">
                        <a href="sua_noi_dung_dao_tao.php?noi_dung_id=<?php echo $row['noi_dung_id']; ?>&operation=edit"
                            class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                            data-target="#confirm-delete-<?php echo $row['noi_dung_id']; ?>"><i
                                class="glyphicon glyphicon-trash"></i></a>
                        <a href="danh_gia_tien_do.php?noi_dung_id=<?php echo $row['noi_dung_id']; ?>"
                            class="btn btn-info"><i class="glyphicon glyphicon-check"></i> Đánh giá tiến độ</a>
                    </div>
                </td>
            </tr>
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="confirm-delete-<?php echo $row['noi_dung_id']; ?>" role="dialog">
                <div class="modal-dialog">
                    <form action="xoa_noi_dung_dao_tao.php" method="POST">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Xác nhận</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="del_id" id="del_id"
                                    value="<?php echo $row['noi_dung_id']; ?>">
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
        <?php echo paginationLinks($page, $total_pages, 'noi_dung_dao_tao.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>