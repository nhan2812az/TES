<div class="form-group">
    <label for="ngay_vao_dao_tao">Ngày vào đào tạo *</label>
    <input type="date" name="ngay_vao_dao_tao"
        value="<?php echo isset($instructor['ngay_vao_dao_tao']) ? htmlspecialchars($instructor['ngay_vao_dao_tao'], ENT_QUOTES, 'UTF-8') : ''; ?>"
        class="form-control" required="required" id="ngay_vao_dao_tao">
</div>

<div class="form-group">
    <label for="chuyen_mon">Chuyên môn *</label>
    <input type="text" name="chuyen_mon"
        value="<?php echo isset($instructor['chuyen_mon']) ? htmlspecialchars($instructor['chuyen_mon'], ENT_QUOTES, 'UTF-8') : ''; ?>"
        placeholder="Chuyên môn" class="form-control" required="required" id="chuyen_mon">
</div>

<div class="form-group">
    <label for="trinh_do_hoc_van">Trình độ học vấn</label>
    <input type="text" name="trinh_do_hoc_van"
        value="<?php echo isset($instructor['trinh_do_hoc_van']) ? htmlspecialchars($instructor['trinh_do_hoc_van'], ENT_QUOTES, 'UTF-8') : ''; ?>"
        placeholder="Trình độ học vấn" class="form-control" id="trinh_do_hoc_van">
</div>

<div class="form-group">
    <label for="kinh_nghiem_giang_day">Kinh nghiệm giảng dạy (năm)</label>
    <input type="number" name="kinh_nghiem_giang_day"
        value="<?php echo isset($instructor['kinh_nghiem_giang_day']) ? htmlspecialchars($instructor['kinh_nghiem_giang_day'], ENT_QUOTES, 'UTF-8') : ''; ?>"
        placeholder="Kinh nghiệm giảng dạy" class="form-control" id="kinh_nghiem_giang_day">
</div>

<div class="form-group">
    <label for="noi_cong_tac">Nơi công tác</label>
    <input type="text" name="noi_cong_tac"
        value="<?php echo isset($instructor['noi_cong_tac']) ? htmlspecialchars($instructor['noi_cong_tac'], ENT_QUOTES, 'UTF-8') : ''; ?>"
        placeholder="Nơi công tác" class="form-control" id="noi_cong_tac">
</div>

<div class="form-group">
    <label for="dia_chi">Địa chỉ</label>
    <input type="text" name="dia_chi"
        value="<?php echo isset($instructor['dia_chi']) ? htmlspecialchars($instructor['dia_chi'], ENT_QUOTES, 'UTF-8') : ''; ?>"
        placeholder="Địa chỉ" class="form-control" id="dia_chi">
</div>

<div class="form-group">
    <label for="tai_khoan_id">Tài khoản *</label>
    <select name="tai_khoan_id" class="form-control" required="required" id="tai_khoan_id">
        <option value="">Chọn tài khoản</option>
        <?php foreach ($accounts as $account): ?>
            <option value="<?php echo $account['id_tai_khoan']; ?>" <?php echo isset($instructor['tai_khoan_id']) && $instructor['tai_khoan_id'] == $account['id_tai_khoan'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($account['ten'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group text-center">
    <label></label>
    <button type="submit" class="btn btn-warning">Lưu <span class="glyphicon glyphicon-send"></span></button>
</div>