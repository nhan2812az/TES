<?php
require_once './config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = filter_input(INPUT_POST, 'username');
	$passwd = filter_input(INPUT_POST, 'passwd');

	$passwd_md5 = md5($passwd);

	$db = getDbInstance();
	$db->where("ten_dang_nhap", $username);

	$row = $db->get('tai_khoan');

	if ($db->count >= 1) {
		$db_password = $row[0]['mat_khau'];
		$user_id = (int) $row[0]['id_tai_khoan'];
		$user_name = $row[0]['ten'];
		$role = $row[0]['vai_tro'];

		if ($passwd_md5 === $db_password) {
			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['user_role'] = $role;
			$_SESSION['id_tai_khoan'] = $user_id;
			$_SESSION['user_name'] = $user_name;
			header('Location: index.php');
		} else {
			$_SESSION['login_failure'] = "Tên đăng nhập hoặc mật khẩu không đúng";
			header('Location: login.php');
		}
		exit;
	} else {
		$_SESSION['login_failure'] = "Tên đăng nhập hoặc mật khẩu không đúng";
		header('Location: login.php');
		exit;
	}
} else {
	die('Phương thức không được cho phép');
}

?>