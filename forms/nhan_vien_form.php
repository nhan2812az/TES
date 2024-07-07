<fieldset>
    <div class="form-group">
        <label for="ten">Họ và tên *</label>
        <input type="text" name="ten"
            value="<?php echo isset($employee['ten']) ? htmlspecialchars($employee['ten'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Họ và tên" class="form-control" required="required" id="ten">
    </div>

    <div class="form-group">
        <label for="phong_ban">Phòng ban *</label>
        <input type="text" name="phong_ban"
            value="<?php echo isset($employee['phong_ban']) ? htmlspecialchars($employee['phong_ban'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Phòng ban" class="form-control" required="required" id="phong_ban">
    </div>

    <div class="form-group">
        <label for="vi_tri">Vị trí *</label>
        <input type="text" name="vi_tri"
            value="<?php echo isset($employee['vi_tri']) ? htmlspecialchars($employee['vi_tri'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Vị trí" class="form-control" required="required" id="vi_tri">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email"
            value="<?php echo isset($employee['email']) ? htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Địa chỉ Email" class="form-control" id="email">
    </div>

    <div class="form-group">
        <label for="so_dien_thoai">Số điện thoại</label>
        <input name="so_dien_thoai"
            value="<?php echo isset($employee['so_dien_thoai']) ? htmlspecialchars($employee['so_dien_thoai'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="987654321" class="form-control" type="text" id="so_dien_thoai">
    </div>

    <div class="form-group">
        <label for="ten_dang_nhap">Tên đăng nhập *</label>
        <input type="text" name="ten_dang_nhap"
            value="<?php echo isset($employee['ten_dang_nhap']) ? htmlspecialchars($employee['ten_dang_nhap'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Tên đăng nhập" class="form-control" required="required" id="ten_dang_nhap">
    </div>

    <div class="form-group">
        <label for="mat_khau">Mật khẩu *</label>
        <input type="password" name="mat_khau"
            value="<?php echo isset($employee['mat_khau']) ? htmlspecialchars($employee['mat_khau'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            placeholder="Mật khẩu" class="form-control" required="required" id="mat_khau">
    </div>

    <div class="form-group">
        <label for="vai_tro">Vai trò *</label>
        <select name="vai_tro" class="form-control" required="required" id="vai_tro">
            <option value="TaiKhoan"
                <?php echo (isset($employee['vai_tro']) && $employee['vai_tro'] == 'Quản lý') ? 'selected' : ''; ?>>Quản
                lý</option>
            <option value="NhanVien"
                <?php echo (isset($employee['vai_tro']) && $employee['vai_tro'] == 'NhanVien') ? 'selected' : ''; ?>>
                Nhân viên</option>
            <option value="GiangVien"
                <?php echo (isset($employee['vai_tro']) && $employee['vai_tro'] == 'GiangVien') ? 'selected' : ''; ?>>
                Giảng viên</option>
        </select>
    </div>

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning">Lưu <span class="glyphicon glyphicon-send"></span></button>
    </div>
</fieldset>