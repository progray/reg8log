<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_prevent_xsrf.php';

$ajax=true;
require $index_dir.'include/code/admin/code_require_admin.php';

if(!isset($_GET['new_account_blocks'])) exit('new_account_blocks parameter is not set');
if(!isset($_GET['new_ip_blocks'])) exit('new_ip_blocks parameter is not set');
if(!isset($_GET['new_regs'])) exit('new_regs parameter is not set');

if(!is_numeric($_GET['new_account_blocks'])) exit('new_account_blocks parameter is not numeric');
if(!is_numeric($_GET['new_ip_blocks'])) exit('new_ip_blocks parameter is not numeric');
if(!is_numeric($_GET['new_regs'])) exit('new_regs parameter is not numeric');

if($_GET['new_account_blocks']) {
	$query="update `admin_alerts` set `new_account_blocks`=`new_account_blocks`-{$_GET['new_account_blocks']} where `for`='visit' and `new_account_blocks`>={$_GET['new_account_blocks']} limit 1";
	$reg8log_db->query($query);
}

if($_GET['new_ip_blocks']) {
	$query="update `admin_alerts` set `new_ip_blocks`=`new_ip_blocks`-{$_GET['new_ip_blocks']} where `for`='visit' and `new_ip_blocks`>={$_GET['new_ip_blocks']} limit 1";
	$reg8log_db->query($query);
}

if($_GET['new_regs']) {
	$query="update `admin_reg_alerts` set `new_regs`=`new_regs`-{$_GET['new_regs']} where `for`='visit' and `new_regs`>={$_GET['new_regs']} limit 1";
	$reg8log_db->query($query);
}

echo 'ok';

?>