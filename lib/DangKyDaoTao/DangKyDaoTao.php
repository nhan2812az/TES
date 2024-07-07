<?php
class DangKyDaoTao
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
    }

    public function setOrderingValues()
    {
        $ordering = [
            'dang_ky_id' => 'ID',
            'tai_khoan_id' => 'Tài khoản ID',
            'chuong_trinh_id' => 'Chương trình ID',
            'ngay_dang_ky' => 'Ngày đăng ký',
            'trang_thai' => 'Trạng thái',
        ];

        return $ordering;
    }

}
?>