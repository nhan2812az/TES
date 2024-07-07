<fieldset>
    <div class="form-group" style="display: none;">
        <label for="tai_khoan_id">Tài khoản ID *</label>
        <input type="number" name="tai_khoan_id"
            value="<?php echo isset($_SESSION['id_tai_khoan']) ? (int) $_SESSION['id_tai_khoan'] : ''; ?>"
            placeholder="Tài khoản ID" class="form-control" required="required" id="tai_khoan_id" hidden>
    </div>

    <div class="form-group">
        <label for="ten_chuong_trinh">Tên chương trình *</label>
        <input type="text" name="ten_chuong_trinh"
            value="<?php echo isset($chuong_trinh_id) ? getTenChuongTrinh($chuong_trinh_id) : ''; ?>"
            class="form-control" readonly>
    </div>

    <div class="form-group" style="display: none;">
        <label for="chuong_trinh_id">Chương trình ID *</label>
        <input type="number" name="chuong_trinh_id" value="<?php echo $chuong_trinh_id; ?>"
            placeholder="Chương trình ID" class="form-control" required="required" id="chuong_trinh_id" hidden>
    </div>

    <div class="form-group">
        <label for="ngay_dang_ky">Ngày đăng ký *</label>
        <input type="date" name="ngay_dang_ky"
            value="<?php echo isset($dang_ky['ngay_dang_ky']) ? htmlspecialchars($dang_ky['ngay_dang_ky'], ENT_QUOTES, 'UTF-8') : date('Y-m-d'); ?>"
            class="form-control" required="required" id="ngay_dang_ky" readonly>
    </div>

    <div class="form-group" style="display: none;">
        <label for="trang_thai">Trạng thái *</label>
        <select name="trang_thai" class="form-control" required="required" id="trang_thai" readonly>
            <option value="Chờ duyệt" selected>Chờ duyệt</option>
        </select>
    </div>

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning">Xác nhận đăng kí <span
                class="glyphicon glyphicon-send"></span></button>
    </div>
</fieldset>