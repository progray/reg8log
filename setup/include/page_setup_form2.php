<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html <?php echo PAGE_DIR; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('DB setup - Step 2'); ?></title>
<script src="../js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="../js/sha256.js"></script>
<script>

function clear_form() {
document.admin_register_form.password.value='';
document.admin_register_form.repass.value='';
document.admin_register_form.email.value='';
document.admin_register_form.reemail.value='';
clear_cap(true);
return false;
}

<?php
echo "fields=new Array(\n";
$f=false;
foreach($_fields as $field_name=>$specs)
if($field_name==='password' or $field_name==='email') {
  if($f) echo ",\n";
  else $f=true;
echo "new Array(\n'$field_name',\n{$specs['minlength']},\n{$specs['maxlength']},\n";
if($specs['js_re']===true) echo $specs['php_re'];
else if($specs['js_re']===false) echo 'false';
else echo $specs['js_re'];
if(config::get('lang')==='fa') echo ",\n'", func::tr($field_name), "'";
else echo ",\n'$field_name'";
echo "\n)";
}
echo "\n);\n";

echo "\nsite_salt='$site_salt';\n";
?>

function hash_password() {
document.admin_register_form.repass.value=document.admin_register_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.admin_register_form.password.value);
}

function validate() {//client side validator

msgs=new Array();

i=0;

for(j in fields) {

field_name=fields[j][0];
field_value=eval('admin_register_form.'+field_name+'.value');
min_length=fields[j][1];
max_length=fields[j][2];
re=fields[j][3];
locale_field_name=fields[j][4];

if(field_value.length<min_length) msgs[i++]=locale_field_name+"<?php echo func::tr(' is shorter than "+min_length+" characters!'); ?>";
else if(field_value.length>max_length) msgs[i++]=locale_field_name+"<?php echo func::tr(' is longer than "+max_length+" characters!'); ?>";
else if(re && field_value && !re.test(field_value)) msgs[i++]=locale_field_name+"<?php echo func::tr(' is invalid!'); ?>";
else if(field_name=='password' && admin_register_form.password.value!=document.getElementById('repass').value) msgs[i++]="<?php echo func::tr('Password fields aren\'t match!'); ?>";
else if(field_name=='email' && admin_register_form.email.value!=admin_register_form.reemail.value) msgs[i++]="<?php echo func::tr('Email fields aren\'t match!'); ?>";
}

if(msgs.length) {
clear_cap(false);
for(i in msgs){
msgs[i]=msgs[i].charAt(0).toUpperCase()+msgs[i].substring(1, msgs[i].length);
cap.appendChild(document.createTextNode(msgs[i]));
cap.appendChild(document.createElement("br"));
}
return false;
}

hash_password();

return true;
}//client side validator

</script>
</head>
<body bgcolor="#999999" <?php echo PAGE_DIR; ?>>
<table height="100%" align="center"><tr><td align="center">
<h3><small>((</small> <?php echo func::tr('Setup admin account'); ?> <small>))</small></h3>
<form action="" method="post" name="admin_register_form">
<table bgcolor="#7587b0" style="padding: 5px">
<?php
if(isset($err_msgs)) {
echo '<tr align="center"><td colspan="2"  style="border: solid thin yellow; font-style: italic;"><span style="color: #800">', func::tr('Errors'), ':</span><br />';
foreach($err_msgs as $err_msg) {
$err_msg[0]=strtoupper($err_msg[0]);
echo "<span style=\"color: yellow\" >$err_msg</span><br />";
}
echo '</td></tr>';
}

echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

?>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Username'); ?>:</td><td><input type="text" name="username" size="7" value="Admin" disabled style="text-align: center; font-weight: bold"><input name="username" type=hidden value=Admin></td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Password'); ?>:</td><td><input type="password" name="password" size="30" autocomplete="off"></td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Retype password'); ?>:</td>
<td>
<input type="password" id="repass" name="repass" autocomplete="off" size="30"></td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Email'); ?>:</td>
<td><input type="text" name="email" <?php if(isset($_POST['email'])) echo 'value="', htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'), '"'; ?> size="45"></td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Retype email'); ?>:</td>
<td><input type="text" name="reemail" <?php if(isset($_POST['reemail'])) echo 'value="', htmlspecialchars($_POST['reemail'], ENT_QUOTES, 'UTF-8'), '"'; ?> size="45"></td>
</tr>
<tr>
<td></td><td><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td align="center" colspan="2"><input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form()" />
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onClick="return validate()" /></td>
</tr>
</table>
</form>
</td></tr></table>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
