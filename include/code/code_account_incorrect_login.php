<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])==='admin') {
	config::set('account_block_threshold', config::get('admin_account_block_threshold'));
	config::set('account_captcha_threshold', config::get('admin_account_captcha_threshold'));
	config::set('account_block_period', config::get('admin_account_block_period'));
	config::set('ip_block_threshold', config::get('admin_ip_block_threshold'));
	config::set('ip_captcha_threshold', config::get('admin_ip_captcha_threshold'));
	config::set('ip_block_period', config::get('admin_ip_block_period'));
}

if(config::get('account_block_threshold')===-1  and config::get('account_captcha_threshold')===-1) return;

if(!$username_exists and config::get('registeration_enabled') and config::get('ajax_check_username') and !config::get('max_ajax_check_usernames')) {
	$no_pretend_user=true;
	return;
}

$_username=$GLOBALS['reg8log_db']->quote_smart($_POST['username']);

$query="select * from `account_incorrect_logins` where `username`=$_username limit 1";

$GLOBALS['reg8log_db']->query($query);

$cookie_capacity=30;

if(!$GLOBALS['reg8log_db']->result_num()) {
	$attempts=$GLOBALS['reg8log_db']->quote_smart(pack('l10', REQUEST_TIME, 0, 0, 0, 0, 0, 0, 0, 0, 0));
	$pos=2;
	$field_values="$_username, $username_exists, $attempts, $pos, ".REQUEST_TIME;
	$query="insert into `account_incorrect_logins` (`username`, `username_exists`, `attempts`, `pos`, `last_attempt`) values($field_values)";
	$GLOBALS['reg8log_db']->query($query);

	$insert_id=mysql_insert_id();
	
	if(!isset($_COOKIE['reg8log_account_incorrect_logins'])) $cookie_contents=$insert_id.','.REQUEST_TIME;
	else {
		$cookie_contents=$_COOKIE['reg8log_account_incorrect_logins'].','.$insert_id.','.REQUEST_TIME;
		$cookie_contents=implode(',', array_slice(explode(',', $cookie_contents), -2*$cookie_capacity));
	}
	setcookie('reg8log_account_incorrect_logins', $cookie_contents, 0, '/', null, HTTPS, true);
	
	if(config::get('account_block_threshold')===1) {
		$_username2=$_POST['username'];
		require_once ROOT.'include/code/code_accomodate_block_disable.php';
		if($block_disable!=2 and $block_disable!=3) {
			$account_block=$_POST['username'];
			$block_duration=config::get('account_block_period');
			$first_attempt=REQUEST_TIME;
			require_once ROOT.'include/code/log/code_log_account_block.php';
		}
		else if(config::get('account_captcha_threshold')===1) $captcha_needed=true;
	}
	else if(config::get('account_captcha_threshold')===1) $captcha_needed=true;

	$incorrect_attempts=1;

	return;
}

$rec5=$GLOBALS['reg8log_db']->fetch_row();

$insert_id=$rec5['auto'];

$attempts = unpack("l10", $rec5['attempts']);

$count=1; //1 for current incorrect attempt
$oldest=REQUEST_TIME;
foreach($attempts as $value) if((REQUEST_TIME-$value)<config::get('account_block_period')) {
	$count++;
	if($value<$oldest) $oldest=$value;
}

$incorrect_attempts=$count;

if(config::get('account_block_threshold')!==-1 and $count>=config::get('account_block_threshold')) {
	$_username2=$_POST['username'];
	require_once ROOT.'include/code/code_accomodate_block_disable.php';
	if($block_disable!=2 and $block_disable!=3) {
		$account_block=$_POST['username'];
		$block_duration=$oldest+config::get('account_block_period')-REQUEST_TIME;
		$first_attempt=$oldest;
		require_once ROOT.'include/code/log/code_log_account_block.php';
	}
	else if(config::get('account_captcha_threshold')!==-1 and $count>=config::get('account_captcha_threshold')) $captcha_needed=true;
}
else if(config::get('account_captcha_threshold')!==-1 and $count>=config::get('account_captcha_threshold')) $captcha_needed=true;

$pos=$rec5['pos'];

$attempts[$pos]=REQUEST_TIME;

$attempts=$GLOBALS['reg8log_db']->quote_smart(pack('l10', $attempts[1], $attempts[2], $attempts[3], $attempts[4], $attempts[5], $attempts[6], $attempts[7], $attempts[8], $attempts[9], $attempts[10]));

$pos++;
if($pos>10) $pos=1;

$query="update `account_incorrect_logins` set `attempts`=$attempts, `pos`=$pos, `last_attempt`=".REQUEST_TIME." where `username`=$_username limit 1";

$GLOBALS['reg8log_db']->query($query);

if(!isset($_COOKIE['reg8log_account_incorrect_logins'])) $cookie_contents=$insert_id.','.REQUEST_TIME;
else {
	$cookie_contents=$_COOKIE['reg8log_account_incorrect_logins'].','.$insert_id.','.REQUEST_TIME;
	$cookie_contents=implode(',', array_slice(explode(',', $cookie_contents), -2*$cookie_capacity));
}
setcookie('reg8log_account_incorrect_logins', $cookie_contents, 0, '/', null, HTTPS, true);

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) {
	$table_name='account_incorrect_logins';
	require ROOT.'include/code/cleanup/code_account_incorrect_logins_expired_cleanup.php';
}

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) {
	$table_name='account_incorrect_logins';
	require ROOT.'include/code/cleanup/code_account_incorrect_logins_size_cleanup.php';
}

?>