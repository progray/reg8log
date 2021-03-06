<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!config::get('log_non_existent_accounts_blocks') and !$username_exists) return;

$ip=$GLOBALS['reg8log_db']->quote_smart(func::inet_pton2($_SERVER['REMOTE_ADDR']));

$query='insert into `account_block_log` (`ext_auto`, `username`, `username_exists`, `first_attempt`, `last_attempt`, `last_ip`, `block_threshold`) values ('."$insert_id, $_username, $username_exists, $first_attempt, ".REQUEST_TIME.", $ip, ".config::get('account_block_threshold').")";

$GLOBALS['reg8log_db']->query($query);

if(config::get('exempt_admin_account_from_alert_limits') and strtolower($_POST['username'])==='admin') $no_alert_limits=true;
else $no_alert_limits=false;

if(config::get('alert_admin_about_account_blocks') and !(config::get('alert_admin_about_account_blocks')>3 and !$no_alert_limits)) {
	if(in_array(config::get('alert_admin_about_account_blocks'), array(1, 4))) {
		$query="update `admin_block_alerts` set `new_account_blocks`=`new_account_blocks`+1 where `for`='visit' limit 1";
		$GLOBALS['reg8log_db']->query($query);
	}
	else if(in_array(config::get('alert_admin_about_account_blocks'), array(2, 5))) {
		$lock_name2='reg8log--admin_account_block_email_alert--'.SITE_KEY;
		$GLOBALS['reg8log_db']->query("select get_lock('$lock_name2', -1)");
		$query="update `admin_block_alerts` set `new_account_blocks`=`new_account_blocks`+1 where `for`='email' limit 1";
		$GLOBALS['reg8log_db']->query($query);
		require ROOT.'include/code/admin/code_check_account_blocks_admin_email_alert.php';
	}
	else {
		$lock_name2='reg8log--admin_account_block_email_alert--'.SITE_KEY;
		$GLOBALS['reg8log_db']->query("select get_lock('$lock_name2', -1)");
		$query="update `admin_block_alerts` set `new_account_blocks`=`new_account_blocks`+1 limit 2";
		$GLOBALS['reg8log_db']->query($query);
		require ROOT.'include/code/admin/code_check_account_blocks_admin_email_alert.php';
	}
}

if(config::get('keep_expired_block_log_records_for')!==0 and mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/cleanup/code_account_block_log_expired_cleanup.php';

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_account_block_log_size_cleanup.php';

?>