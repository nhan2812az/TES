<fieldset>
    <div class="form-group">
        <label for="ten_chuong_trinh">Tên chương trình *</label>
        <input type="text" name="ten_chuong_trinh"
            value="<?php echo isset($chuong_trinh_dao_tao['ten_chuong_trinh']) ? htmlspecialchars($chuong_trinh_dao_tao['ten_chuong_trinh'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Tên chương trình" class="form-control" required="required" id="ten_chuong_trinh">
    </div>

    <div class="form-group">
        <label for="doi_tuong">Đối tượng *</label>
        <select name="doi_tuong" class="form-control" required="required" id="doi_tuong">
            <?php
            $doi_tuong_options = [
                'Nhân viên' => 'Nhân viên',
                'Giáo viên' => 'Giáo viên',
                'Quản lý' => 'Quản lý',
                'Khác' => 'Khác'
            ];
            foreach ($doi_tuong_options as $value => $label) {
                $selected = (isset($chuong_trinh_dao_tao['doi_tuong']) && $chuong_trinh_dao_tao['doi_tuong'] == $value) ? 'selected' : '';
                echo "<option value='{$value}' $selected>{$label}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="thoi_luong">Thời lượng (phút) *</label>
        <input type="number" name="thoi_luong"
            value="<?php echo isset($chuong_trinh_dao_tao['thoi_luong']) ? htmlspecialchars($chuong_trinh_dao_tao['thoi_luong'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Thời lượng (phút)" class="form-control" required="required" id="thoi_luong" min="1">
    </div>

    <div class="form-group">
        <label for="hinh_thuc">Hình thức *</label>
        <select name="hinh_thuc" class="form-control" required="required" id="hinh_thuc">
            <?php
    $hinh_thuc_options = [
        'Trực tuyến' => 'Trực tuyến',
        'Tại chỗ' => 'Tại chỗ',
        'Kết hợp' => 'Kết hợp',
        'Khác' => 'Khác'
    ];
    foreach ($hinh_thuc_options as $value => $label) {
        $selected = (isset($chuong_trinh_dao_tao['hinh_thuc']) && $chuong_trinh_dao_tao['hinh_thuc'] == $value) ? 'selected' : '';
        echo "<option value='{$value}' $selected>{$label}</option>";
    }
    ?>
        </select>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-warning">Lưu <span class="glyphicon glyphicon-send"></span></button>
    </div>
</fieldset>