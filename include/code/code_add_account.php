<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/code/code_db_object.php';

require_once ROOT.'include/func/func_random.php';

$table_name='accounts';
$field_name='uid';
require ROOT.'include/code/code_generate_unique_random_id.php';

$autologin_key=random_string(43);

require_once ROOT.'include/func/func_secure_hash.php';

if($_POST['password']!=='') 
$fields['password']['value']=create_secure_hash($_POST['password']);

$field_names='`uid`, `autologin_key`, `timestamp`, ';
$field_values="'$rid', '$autologin_key', ".$req_time.', ';
$i=0;

unset($fields['captcha']);
$fields['password_hash']=$fields['password'];
unset($fields['password']);
foreach($fields as $field_name=>$specs) {
  $field_names.="`$field_name`";
  $field_values.=$reg8log_db->quote_smart($specs['value']);
  if(++$i==count($fields)) break;
  $field_names.=', ';
  $field_values.=', ';
}

$query="insert into `accounts` ($field_names) values ($field_values)";

$reg8log_db->query($query);
unset($_SESSION['reg8log']['captcha_verified'], $_SESSION['reg8log']['passed']);

$success_msg=func::tr('account created msg');
$no_specialchars=true;

?>
