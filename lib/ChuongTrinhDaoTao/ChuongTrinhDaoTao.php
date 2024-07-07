<?php
class ChuongTrinhDaoTao
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
            'chuong_trinh_id' => 'ID',
            'ten_chuong_trinh' => 'Tên chương trình',
            'doi_tuong' => 'Đối tượng',
            'thoi_luong' => 'Thời lượng',
            'hinh_thuc' => 'Hình thức'
        ];

        return $ordering;
    }
}
?>