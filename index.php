<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';

$db = getDbInstance();

$nhu_cau_dao_tao = $db->get('nhu_cau_dao_tao');
$chuong_trinh_dao_tao = $db->get('chuong_trinh_dao_tao');
$noi_dung_dao_tao = $db->get('noi_dung_dao_tao');
$lich_trinh_dao_tao = $db->get('lich_trinh_dao_tao');
$giang_vien = $db->get('giang_vien');
$tai_khoan = $db->get('tai_khoan');
$danh_gia_dao_tao = $db->get('danh_gia_dao_tao');

// Calculate counts
$nhu_cau_count = count($nhu_cau_dao_tao);
$chuong_trinh_count = count($chuong_trinh_dao_tao);
$noi_dung_count = count($noi_dung_dao_tao);
$lich_trinh_count = count($lich_trinh_dao_tao);
$giang_vien_count = count($giang_vien);
$nhan_vien_count = count($tai_khoan);
$danh_gia_count = count($danh_gia_dao_tao);

include BASE_PATH . '/includes/header.php';

$role = $_SESSION['user_role'];
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
    </div>
    <div class="row">
        <?php if ($role === 'QuanTriVien') { ?>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-bar-chart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $nhu_cau_count; ?></div>
                                <div>Nhu cầu đào tạo</div>
                            </div>
                        </div>
                    </div>
                    <a href="nhu_cau_dao_tao.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
        <?php if ($role === 'QuanTriVien' || $role === 'NhanVien') { ?>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-book fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $chuong_trinh_count; ?></div>
                                <div>Chương trình đào tạo</div>
                            </div>
                        </div>
                    </div>
                    <a href="chuong_trinh_dao_tao.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
        <?php if ($role === 'QuanTriVien' || $role === 'GiangVien') { ?>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-list-alt fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $noi_dung_count; ?></div>
                                <div>Nội dung đào tạo</div>
                            </div>
                        </div>
                    </div>
                    <a href="noi_dung_dao_tao.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
        <?php if ($role === 'QuanTriVien' || $role === 'GiangVien') { ?>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-calendar fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $lich_trinh_count; ?></div>
                                <div>Lịch trình đào tạo</div>
                            </div>
                        </div>
                    </div>
                    <a href="lich_trinh_dao_tao.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
        <?php if ($role === 'QuanTriVien') { ?>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $giang_vien_count; ?></div>
                                <div>Giảng viên</div>
                            </div>
                        </div>
                    </div>
                    <a href="giang_vien.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-secondary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $nhan_vien_count; ?></div>
                                <div>Nhân viên</div>
                            </div>
                        </div>
                    </div>
                    <a href="nhan_vien.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
        <?php if ($role === 'QuanTriVien' || $role === 'NhanVien') { ?>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-dark">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-check-circle fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $danh_gia_count; ?></div>
                                <div>Đánh giá đào tạo</div>
                            </div>
                        </div>
                    </div>
                    <a href="danh_gia_dao_tao.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include BASE_PATH . '/includes/footer.php'; ?>