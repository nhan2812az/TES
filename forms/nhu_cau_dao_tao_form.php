<fieldset>
    <div class="form-group">
        <label for="nhan_vien_id">Mã nhân viên *</label>
        <select name="nhan_vien_id" class="form-control" required="required" id="nhan_vien_id">
            <option value="">Chọn nhân viên</option>
            <?php foreach ($nhan_vien_list as $nhan_vien): ?>
            <option value="<?php echo htmlspecialchars($nhan_vien['id_tai_khoan'], ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo (isset($nhu_cau_dao_tao['nhan_vien_id']) && $nhu_cau_dao_tao['nhan_vien_id'] == $nhan_vien['id_tai_khoan']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($nhan_vien['ten'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="form-group">
        <label for="loai_ky_nang">Loại kỹ năng *</label>
        <select name="loai_ky_nang" class="form-control" required="required" id="loai_ky_nang">
            <option value="">Chọn loại kỹ năng</option>
            <?php foreach ($loai_ky_nang_options as $value => $label): ?>
            <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo (isset($nhu_cau_dao_tao['loai_ky_nang']) && $nhu_cau_dao_tao['loai_ky_nang'] == $value) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="muc_ky_nang">Mức kỹ năng *</label>
        <select name="muc_ky_nang" class="form-control" required="required" id="muc_ky_nang">
            <option value="">Chọn mức kỹ năng</option>
            <?php foreach ($muc_ky_nang_options as $value => $label): ?>
            <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                <?php echo (isset($nhu_cau_dao_tao['muc_ky_nang']) && $nhu_cau_dao_tao['muc_ky_nang'] == $value) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="nhan_xet_quan_ly">Nhận xét của quản lý</label>
        <textarea name="nhan_xet_quan_ly" placeholder="Nhận xét của quản lý" class="form-control"
            id="nhan_xet_quan_ly"><?php echo isset($nhu_cau_dao_tao['nhan_xet_quan_ly']) ? htmlspecialchars($nhu_cau_dao_tao['nhan_xet_quan_ly'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
    </div>

    <div class="form-group">
        <label for="ket_qua_khao_sat">Kết quả khảo sát</label>
        <textarea name="ket_qua_khao_sat" placeholder="Kết quả khảo sát" class="form-control"
            id="ket_qua_khao_sat"><?php echo isset($nhu_cau_dao_tao['ket_qua_khao_sat']) ? htmlspecialchars($nhu_cau_dao_tao['ket_qua_khao_sat'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-warning">Lưu <span class="glyphicon glyphicon-send"></span></button>
    </div>
</fieldset>