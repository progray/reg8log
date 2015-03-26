<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(config::get('registerations_alert_threshold')==1 or (!config::get('registerations_alert_threshold_period') and $new_registerations>=config::get('registerations_alert_threshold'))) {
	$registerations_alert_threshold_reached=true;
	return;
}

if($new_registerations<config::get('registerations_alert_threshold')) return;

require_once ROOT.'include/code/code_db_object.php';

$query='select count(*) from `registerations_history` where `timestamp`>='.($req_time-config::get('registerations_alert_threshold_period'));

if($reg8log_db->count_star($query)>=config::get('registerations_alert_threshold')) $registerations_alert_threshold_reached=true;

?>