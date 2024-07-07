<?php
class DanhGiaDaoTao
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
            'danh_gia_id' => 'ID',
            'nhan_vien_id' => 'Nhân viên ID',
            'chuong_trinh_id' => 'Chương trình ID',
            'loai_danh_gia' => 'Loại đánh giá',
            'diem_danh_gia' => 'Điểm đánh giá',
            'nhan_xet' => 'Nhận xét',
        ];

        return $ordering;
    }
}
?>