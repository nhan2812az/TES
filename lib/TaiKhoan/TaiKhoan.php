<?php
class TaiKhoan
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
    }

    public function setOrderingValues()
    {
        $ordering = [
            'id_tai_khoan' => 'ID',
            'ten' => 'Tên',
            'phong_ban' => 'Phòng ban',
            'vi_tri' => 'Vị trí',
            'email' => 'Email',
            'so_dien_thoai' => 'Số điện thoại',
            'vai_tro' => 'Vai trò',
            'ten_dang_nhap' => 'Tên đăng nhập',
            'mat_khau' => 'Mật khẩu'
        ];

        return $ordering;
    }
}
?>