<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$lock_name='reg8log--register--'.SITE_KEY;
$GLOBALS['reg8log_db']->query("select get_lock('$lock_name', -1)");

$tmp8=$GLOBALS['reg8log_db']->quote_smart($field_value);
$query1='select * from `accounts` where `'.$field_name.'`='.$tmp8;
if(isset($except_user)) {
	$except_user=$GLOBALS['reg8log_db']->quote_smart($except_user);
	$query1.=' and `username`!='.$except_user;
}
$query1.=' limit 1';

$expired1=REQUEST_TIME-config::get('email_verification_time');
$expired2=REQUEST_TIME-config::get('admin_confirmation_time');

$query2="select * from `pending_accounts` where `$field_name`=$tmp8  and (`email_verification_key`='' or `email_verified`=1 or `timestamp` > $expired1)  and (`admin_confirmed`=1 or `timestamp` > $expired2)";
if(isset($except_user)) $query2.=' and `username`!='.$except_user;
$query2.=' limit 1';

/* if($field_name==='username' and (!config::get('ajax_check_username') or config::get('max_ajax_check_usernames'))) {
	$expired=REQUEST_TIME-config::get('account_block_period');
	$query3="select * from `account_incorrect_logins` where `username`=$tmp8 and `last_attempt` > $expired limit 1";
} */

unset($uniqueness_err);
if(
$GLOBALS['reg8log_db']->result_num($query1) or
$GLOBALS['reg8log_db']->result_num($query2) or
(isset($query3) and $GLOBALS['reg8log_db']->result_num($query3))
) {
	$err_msgs[]=sprintf(func::tr('already registered msg'), func::tr($field_name), func::tr($field_name));
	$uniqueness_err=true;
}

unset($query3);

?>
