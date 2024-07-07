<?php
class NhuCauDaoTao
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
            'nhu_cau_id' => 'ID',
            'nhan_vien_id' => 'Mã nhân viên',
            'loai_ky_nang' => 'Loại kỹ năng',
            'muc_ky_nang' => 'Mức kỹ năng',
            'nhan_xet_quan_ly' => 'Nhận xét của quản lý',
            'ket_qua_khao_sat' => 'Kết quả khảo sát',
        ];

        return $ordering;
    }
}
?>