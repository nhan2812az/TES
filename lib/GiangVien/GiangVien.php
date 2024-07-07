<?php
class GiangVien
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

    public function getAssignedProgram($giang_vien_id)
    {
        $db = getDbInstance();

        $db->where('giang_vien_id', $giang_vien_id);
        $program = $db->getOne('phan_cong_giang_vien');

        if ($db->count > 0) {
            $chuong_trinh_id = $program['chuong_trinh_id'];
            $chuong_trinh = $db->where('chuong_trinh_id', $chuong_trinh_id)->getOne('chuong_trinh_dao_tao');

            if ($chuong_trinh) {
                return [
                    'chuong_trinh_id' => $chuong_trinh['chuong_trinh_id'],
                    'ten_chuong_trinh' => $chuong_trinh['ten_chuong_trinh']
                ];
            }
        }

        return null;
    }

    public function setOrderingValues()
    {
        $ordering = [
            'giang_vien_id' => 'ID',
            'ngay_vao_dao_tao' => 'Ngày vào đào tạo',
            'chuyen_mon' => 'Chuyên môn',
            'trinh_do_hoc_van' => 'Trình độ học vấn',
            'kinh_nghiem_giang_day' => 'Kinh nghiệm giảng dạy',
            'noi_cong_tac' => 'Nơi công tác',
            'dia_chi' => 'Địa chỉ',
            'tai_khoan_id' => 'Tài khoản'
        ];

        return $ordering;
    }

    public function deleteAssignment($giang_vien_id)
    {
        $db = getDbInstance();

        $db->where('giang_vien_id', $giang_vien_id);
        $db->delete('phan_cong_giang_vien');

        if ($db->count > 0) {
            return true;
        }

        return false;
    }




}
?>