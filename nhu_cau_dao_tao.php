<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

require_once BASE_PATH . '/lib/NhuCauDaoTao/NhuCauDaoTao.php';
$nhu_cau_dao_tao = new NhuCauDaoTao();

$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');

$pagelimit = 15;

$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}

if (!$filter_col) {
    $filter_col = 'nhu_cau_id';
}
if (!$order_by) {
    $order_by = 'Desc';
}

$db = getDbInstance();
$select = array('nhu_cau_id', 'nhan_vien_id', 'loai_ky_nang', 'muc_ky_nang', 'nhan_xet_quan_ly', 'ket_qua_khao_sat');

if ($search_string) {
    $db->where('loai_ky_nang', '%' . $search_string . '%', 'like');
    $db->orwhere('muc_ky_nang', '%' . $search_string . '%', 'like');
}

if ($order_by) {
    $db->orderBy($filter_col, $order_by);
}

$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('nhu_cau_dao_tao', $page, $select);
$total_pages = $db->totalPages;

include BASE_PATH . '/includes/header.php';

function getLoaiKyNangText($value)
{
    $options = [
        'kien_thuc' => 'Kiến thức',
        'nghiep_vu' => 'Nghiệp vụ',
        'ky_nang' => 'Kỹ năng'
    ];
    return isset($options[$value]) ? $options[$value] : $value;
}
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

function getMucKyNangText($value)
{
    $options = [
        'co_ban' => 'Cơ bản',
        'trung_binh' => 'Trung bình',
        'cao' => 'Cao'
    ];
    return isset($options[$value]) ? $options[$value] : $value;
}
?>

<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Nhu cầu đào tạo</h1>
        </div>
        <div class="col-lg-6">
            <div class="page-action-links text-right">
                <a href="them_nhu_cau_dao_tao.php?operation=create" class="btn btn-success"><i
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
                foreach ($nhu_cau_dao_tao->setOrderingValues() as $opt_value => $opt_name):
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
        <a href="xuat_nhu_cau_dao_tao.php"><button class="btn btn-sm btn-primary">Xuất CSV <i
                    class="glyphicon glyphicon-export"></i></button></a>
    </div>

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Tên nhân viên</th>
                <th width="20%">Loại kỹ năng</th>
                <th width="20%">Mức kỹ năng</th>
                <th width="20%">Nhận xét của quản lý</th>
                <th width="20%">Kết quả khảo sát</th>
                <th width="10%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo $row['nhu_cau_id']; ?></td>
                    <td><?php echo getTenNhanVien(xss_clean($row['nhan_vien_id'])); ?></td>
                    <td><?php echo getLoaiKyNangText(xss_clean($row['loai_ky_nang'])); ?></td>
                    <td><?php echo getMucKyNangText(xss_clean($row['muc_ky_nang'])); ?></td>
                    <td><?php echo xss_clean($row['nhan_xet_quan_ly']); ?></td>
                    <td><?php echo xss_clean($row['ket_qua_khao_sat']); ?></td>
                    <td>
                        <div class="flex">
                            <a href="sua_nhu_cau_dao_tao.php?nhu_cau_id=<?php echo $row['nhu_cau_id']; ?>&operation=edit"
                                class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                            <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                                data-target="#confirm-delete-<?php echo $row['nhu_cau_id']; ?>"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="confirm-delete-<?php echo $row['nhu_cau_id']; ?>" role="dialog">
                    <div class="modal-dialog">
                        <form action="xoa_nhu_cau_dao_tao.php" method="POST">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Xác nhận</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="del_id" id="del_id"
                                        value="<?php echo $row['nhu_cau_id']; ?>">
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
        <?php echo paginationLinks($page, $total_pages, 'nhu_cau_dao_tao.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>