<?php
class PhanCongGiangVien
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
            'phan_cong_id' => 'ID',
            'giang_vien_id' => 'Giảng viên ID',
            'lich_trinh_id' => 'Lịch trình ID',
            'ngay_bat_dau' => 'Ngày bắt đầu',
            'ngay_ket_thuc' => 'Ngày kết thúc',
            'vai_tro' => 'Vai trò',
        ];

        return $ordering;
    }

}
?>