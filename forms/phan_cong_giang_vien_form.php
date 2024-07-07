<fieldset>
    <div class="form-group">
        <label for="giang_vien_id">Giảng viên ID *</label>
        <select name="giang_vien_id_disabled" class="form-control" disabled>
            <option value="">Chọn giảng viên</option>
            <?php foreach ($giang_vien_list as $giang_vien): ?>
            <option value="<?php echo $giang_vien['giang_vien_id']; ?>"
                <?php echo ($selected_giang_vien_id == $giang_vien['giang_vien_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($giang_vien['ten'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="giang_vien_id" value="<?php echo $selected_giang_vien_id; ?>">
    </div>

    <div class="form-group">
        <label for="chuong_trinh_id">Chương trình đào tạo ID *</label>
        <select name="chuong_trinh_id" class="form-control" required>
            <option value="">Chọn chương trình đào tạo</option>
            <?php foreach ($chuong_trinh_dao_tao_list as $chuong_trinh): ?>
            <option value="<?php echo $chuong_trinh['chuong_trinh_id']; ?>">
                <?php echo htmlspecialchars($chuong_trinh['ten_chuong_trinh'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-warning">Lưu <span class="glyphicon glyphicon-send"></span></button>
    </div>
</fieldset>