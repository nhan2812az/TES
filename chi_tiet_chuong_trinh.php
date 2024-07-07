<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$chuong_trinh_id = filter_input(INPUT_GET, 'chuong_trinh_id', FILTER_VALIDATE_INT);
if (!$chuong_trinh_id) {
    header('Location: chuong_trinh_dao_tao.php');
    exit;
}

$db = getDbInstance();

$db->where('chuong_trinh_id', $chuong_trinh_id);
$chuong_trinh = $db->getOne('chuong_trinh_dao_tao');
if (!$chuong_trinh) {
    header('Location: chuong_trinh_dao_tao.php');
    exit;
}

$db->where('chuong_trinh_id', $chuong_trinh_id);
$noi_dung_list = $db->get('noi_dung_dao_tao');

// Thay đổi câu truy vấn để lấy thông tin giảng viên và tên từ bảng tai_khoan
$db->join('giang_vien gv', 'pgv.giang_vien_id = gv.giang_vien_id', 'LEFT');
$db->join('tai_khoan tk', 'gv.tai_khoan_id = tk.id_tai_khoan', 'LEFT');
$db->where('pgv.chuong_trinh_id', $chuong_trinh_id);
$giang_vien_list = $db->get('phan_cong_giang_vien pgv', null, 'gv.*, tk.ten as ten_giang_vien');

$nhan_vien_id = $_SESSION['id_tai_khoan'];

include BASE_PATH . '/includes/header.php';
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Chi tiết chương trình: <?php echo xss_clean($chuong_trinh['ten_chuong_trinh']); ?>
            </h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Thông tin chương trình
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td><?php echo xss_clean($chuong_trinh['chuong_trinh_id']); ?></td>
                </tr>
                <tr>
                    <th>Tên chương trình</th>
                    <td><?php echo xss_clean($chuong_trinh['ten_chuong_trinh']); ?></td>
                </tr>
                <tr>
                    <th>Đối tượng</th>
                    <td><?php echo xss_clean($chuong_trinh['doi_tuong']); ?></td>
                </tr>
                <tr>
                    <th>Thời lượng</th>
                    <td><?php echo xss_clean($chuong_trinh['thoi_luong']); ?></td>
                </tr>
                <tr>
                    <th>Hình thức</th>
                    <td><?php echo xss_clean($chuong_trinh['hinh_thuc']); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <?php if (empty($giang_vien_list)): ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin giảng viên
            </div>
            <div class="panel-body">
                <p class="text-center">Khóa học này chưa được phân công cho giảng viên nào.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin giảng viên
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Ngày vào đào tạo</th>
                            <th>Chuyên môn</th>
                            <th>Trình độ học vấn</th>
                            <th>Kinh nghiệm giảng dạy</th>
                            <th>Nơi công tác</th>
                            <th>Địa chỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($giang_vien_list as $giang_vien): ?>
                            <tr>
                                <td><?php echo xss_clean($giang_vien['giang_vien_id']); ?></td>
                                <td><?php echo xss_clean($giang_vien['ten_giang_vien']); ?></td>
                                <td><?php echo xss_clean($giang_vien['ngay_vao_dao_tao']); ?></td>
                                <td><?php echo xss_clean($giang_vien['chuyen_mon']); ?></td>
                                <td><?php echo xss_clean($giang_vien['trinh_do_hoc_van']); ?></td>
                                <td><?php echo xss_clean($giang_vien['kinh_nghiem_giang_day']); ?></td>
                                <td><?php echo xss_clean($giang_vien['noi_cong_tac']); ?></td>
                                <td><?php echo xss_clean($giang_vien['dia_chi']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            Nội dung đào tạo
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Loại nội dung</th>
                        <th>Tiêu đề</th>
                        <th>Mô tả</th>
                        <th>Chủ đề</th>
                        <th>Đường dẫn tệp tin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($noi_dung_list as $noi_dung): ?>
                        <tr>
                            <td><?php echo xss_clean($noi_dung['loai_noi_dung']); ?></td>
                            <td><?php echo xss_clean($noi_dung['tieu_de']); ?></td>
                            <td><?php echo xss_clean($noi_dung['mo_ta']); ?></td>
                            <td><?php echo xss_clean($noi_dung['chu_de']); ?></td>
                            <td>
                                <?php if (!empty($noi_dung['duong_dan_tap_tin'])): ?>
                                    <a href="<?php echo xss_clean($noi_dung['duong_dan_tap_tin']); ?>" target="_blank">Xem
                                        tệp</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($noi_dung_list)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Không có nội dung đào tạo.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-right">
        <a href="chuong_trinh_dao_tao.php" class="btn btn-default">Quay lại</a>
        <a href="xem_tien_do_hoc_vien.php?nhan_vien_id=<?php echo $nhan_vien_id; ?>&chuong_trinh_dao_tao=<?php echo $chuong_trinh_id; ?>"
            class="btn btn-primary">Xem tiến độ
            học tập</a>
    </div>
</div>

<?php include BASE_PATH . '/includes/footer.php'; ?>