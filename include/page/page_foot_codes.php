<?phpif(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");if(isset($admin_alert_visit_msg) and $admin_alert_visit_msg) {	echo '<div id="alert_div" style="position: absolute; top: 0px; left: 0px; border: medium double #000; padding: 8px; background: #eee; visibility: hidden;"><div id="alert_title_div" style="font-size: bigger; font-weight: bold" align="center"></div><br><div id="alert_contents_div"></div><br><center>Don\'t disturb me again:<input type="checkbox" onclick="dont_disturb(this.checked)"><br><a href="javascript: hide_alert()" style="margin-top: 5px;">Close</a></center></div>';	if(!isset($new_account_blocks, $account_blocks_alert_threshold_reached)) $new_account_blocks=0;	if(!isset($new_ip_blocks, $ip_blocks_alert_threshold_reached)) $new_ip_blocks=0;	if(!isset($new_regs) or $new_regs<$registerations_alert_threshold) $new_regs=0;	echo "<script src='{$index_dir}js/my_alert.js'></script>";		echo "<script>	function admin_alert(msg) {	my_alert('Account/IP Block report', msg);	if(window.XMLHttpRequest) xhr2 = new XMLHttpRequest();	else if(window.ActiveXObject) xhr2 = new ActiveXObject('Microsoft.XMLHTTP');\n";	echo "xhr2.open('GET', '{$index_dir}ajax/admin_alerts_viewed.php?new_account_blocks=$new_account_blocks&new_ip_blocks=$new_ip_blocks&new_regs=$new_regs&antixsrf_token={$_COOKIE['reg8log_antixsrf_token']}', true);\n";	echo "xhr2.onreadystatechange=function() {	//if(xhr2.readyState == 4) alert(xhr2.responseText);	}\n";	echo "xhr2.send(null);\n}\n";	$admin_alert_visit_msg=addslashes($admin_alert_visit_msg);	$admin_alert_visit_msg=str_replace("\n", '\n', $admin_alert_visit_msg);	echo "admin_alert('$admin_alert_visit_msg');";	echo '</script>';	}?>