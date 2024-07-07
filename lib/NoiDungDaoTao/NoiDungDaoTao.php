<?php
class NoiDungDaoTao
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
            'noi_dung_id' => 'ID',
            'chuong_trinh_id' => 'Chương trình ID',
            'loai_noi_dung' => 'Loại nội dung',
            'tieu_de' => 'Tiêu đề',
            'mo_ta' => 'Mô tả',
            'duong_dan_tap_tin' => 'Đường dẫn tệp tin',
            'chu_de' => 'Chủ đề',
        ];

        return $ordering;
    }
}
?>