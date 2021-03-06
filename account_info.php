<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

require ROOT.'include/code/code_identify.php';

if(!isset($identified_user)) func::my_exit('<center><h3>'.func::tr('You are not authenticated msg').'.</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

if(!config::get('password_required4viewing_account_info')) {
	require ROOT.'include/page/page_account_info.php';
	exit;
}

$try_type='password';
require ROOT.'include/code/code_check_captcha_needed4user.php';

if(isset($captcha_needed)) $captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);

if(isset($_POST['password'])) {

	require ROOT.'include/code/code_prevent_repost.php';

	require ROOT.'include/code/code_prevent_xsrf.php';
	
	if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/code/code_verify_captcha.php';
	
	if($_POST['password']==='') $err_msgs[]=func::tr('Password field is empty!');
	else if(!isset($captcha_err)) {
		if(strpos($_POST['password'], 'hashed-'.SITE_SALT)!==0) $_POST['password']='hashed-'.SITE_SALT.'-'.hash('sha256', SITE_SALT.$_POST['password']);
		require ROOT.'include/code/code_verify_password.php';
		if(isset($err_msgs)) {
			$try_type='password';
			require ROOT.'include/code/code_update_user_last_ch_try.php';
		}
		else if(isset($_COOKIE['reg8log_ch_pswd_try'])) {
			if(is_numeric($_COOKIE['reg8log_ch_pswd_try'])) {
				$query='update `accounts` set `ch_pswd_tries`=`ch_pswd_tries`-'.$_COOKIE['reg8log_ch_pswd_try'].' where `username`='.$GLOBALS['reg8log_db']->quote_smart($identified_user)." and `ch_pswd_tries`>={$_COOKIE['reg8log_ch_pswd_try']} limit 1";
				$GLOBALS['reg8log_db']->query($query);
			}
			setcookie('reg8log_ch_pswd_try', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
		}
	}
	
	if(!isset($err_msgs)) {
		require ROOT.'include/page/page_account_info.php';
		exit;
	}
	
}

require ROOT.'include/page/page_password_form.php';

?>