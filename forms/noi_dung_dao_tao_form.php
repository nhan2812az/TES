<fieldset>
    <div class="form-group">
        <label for="chuong_trinh_id">Chương trình ID *</label>
        <select name="chuong_trinh_id" class="form-control" required="required" id="chuong_trinh_id">
            <?php
            $chuong_trinh_dao_tao_list = getChuongTrinhDaoTaoList();
            foreach ($chuong_trinh_dao_tao_list as $chuong_trinh) {
                $selected = (isset($noi_dung_dao_tao['chuong_trinh_id']) && $noi_dung_dao_tao['chuong_trinh_id'] == $chuong_trinh['chuong_trinh_id']) ? 'selected' : '';
                echo "<option value='{$chuong_trinh['chuong_trinh_id']}' $selected>{$chuong_trinh['ten_chuong_trinh']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="loai_noi_dung">Loại nội dung *</label>
        <select name="loai_noi_dung" class="form-control" required="required" id="loai_noi_dung">
            <?php
            $loai_noi_dung_options = getLoaiNoiDungOptions();
            foreach ($loai_noi_dung_options as $value => $label) {
                $selected = (isset($noi_dung_dao_tao['loai_noi_dung']) && $noi_dung_dao_tao['loai_noi_dung'] == $value) ? 'selected' : '';
                echo "<option value='{$value}' $selected>{$label}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="tieu_de">Tiêu đề *</label>
        <input type="text" name="tieu_de"
            value="<?php echo isset($noi_dung_dao_tao['tieu_de']) ? htmlspecialchars($noi_dung_dao_tao['tieu_de'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Tiêu đề" class="form-control" required="required" id="tieu_de">
    </div>

    <div class="form-group">
        <label for="mo_ta">Mô tả *</label>
        <textarea name="mo_ta" placeholder="Mô tả" class="form-control" required="required"
            id="mo_ta"><?php echo isset($noi_dung_dao_tao['mo_ta']) ? htmlspecialchars($noi_dung_dao_tao['mo_ta'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
    </div>

    <div class="form-group">
        <label for="duong_dan_tap_tin">Tập tin *</label>
        <input type="file" name="duong_dan_tap_tin" class="form-control" required="required" id="duong_dan_tap_tin">
    </div>

    <div class="form-group">
        <label for="chu_de">Chủ đề *</label>
        <input type="text" name="chu_de"
            value="<?php echo isset($noi_dung_dao_tao['chu_de']) ? htmlspecialchars($noi_dung_dao_tao['chu_de'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Chủ đề" class="form-control" required="required" id="chu_de">
    </div>

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning">Lưu <span class="glyphicon glyphicon-send"></span></button>
    </div>
</fieldset>