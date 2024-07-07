<fieldset>
    <input type="hidden" name="nhan_vien_id" value="<?php echo $_SESSION['id_tai_khoan']; ?>">

    <div class="form-group">
        <label for="chuong_trinh_id">Chương trình ID *</label>
        <select name="chuong_trinh_id" class="form-control" required>
            <option value="">Chọn chương trình đào tạo</option>
            <?php foreach ($chuong_trinh_dao_tao_list as $chuong_trinh): ?>
            <option value="<?php echo $chuong_trinh['chuong_trinh_id']; ?>">
                <?php echo htmlspecialchars($chuong_trinh['ten_chuong_trinh'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="loai_danh_gia">Loại đánh giá:</label>
        <select name="loai_danh_gia" class="form-control" required>
            <option value="">Chọn loại đánh giá</option>
            <option value="1">Thực hành</option>
            <option value="2">Lý thuyết</option>
            <option value="3">Tổng hợp</option>
        </select>
    </div>

    <div class="form-group">
        <label for="diem_danh_gia">Điểm đánh giá *</label>
        <input type="number" name="diem_danh_gia"
            value="<?php echo isset($evaluation['diem_danh_gia']) ? htmlspecialchars($evaluation['diem_danh_gia'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Điểm đánh giá" class="form-control" required="required" id="diem_danh_gia">
    </div>

    <div class="form-group">
        <label for="nhan_xet">Nhận xét</label>
        <textarea name="nhan_xet" placeholder="Nhận xét" class="form-control"
            id="nhan_xet"><?php echo isset($evaluation['nhan_xet']) ? htmlspecialchars($evaluation['nhan_xet'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
    </div>

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning">Lưu <span class="glyphicon glyphicon-send"></span></button>
    </div>
</fieldset>