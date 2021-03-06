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
<title><?php echo func::tr('Re-send account activation email'); ?></title>
<script src="js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script>

var email_re=/^[a-z0-9_\-+\.]+@([a-z0-9\-+]+\.)+[a-z]{2,5}$/i;

function clear_form() {
if(!document.resend_form.email.readOnly) document.resend_form.email.value='';
if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
clear_cap(true);
return false;
}

function validate()
{

clear_cap(true);

msgs=new Array();
i=0;
if(!document.resend_form.email.value) msgs[i++]='<?php echo func::tr('Email field is empty!'); ?>';
else if(!email_re.test(document.resend_form.email.value)) msgs[i++]='<?php echo func::tr('Email is invalid!'); ?>';
if(captcha_exists) validate_captcha(document.resend_form.captcha.value);
if(msgs.length) {
clear_cap(false);
for(i in msgs){
cap.appendChild(document.createTextNode(msgs[i]));
cap.appendChild(document.createElement("br"));
}
return false;
}

if(captcha_exists) {
	form_obj=document.resend_form;
	check_captcha();
	return false;
}

return true;
}

function focus_captcha() {
if(captcha_exists)
document.getElementById('captcha').focus();
}

</script>
</head>
<body bgcolor="#D1D1E9" <?php if(isset($_POST['form1'])) echo 'onload="focus_captcha();"'; ?> <?php echo PAGE_DIR; ?>>
<table width="100%" >
<tr>
<td valign="top">
<?php
require ROOT.'include/page/page_sections.php';
?>
</td>
<tr>
<td align="center"><br>
<form name="resend_form" action="" method="post" >
<table bgcolor="#7587b0" >
<?php
if(isset($err_msgs)) {
echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic">';
foreach($err_msgs as $err_msg) echo '<span style="color: yellow" >', $err_msg, '</span><br />';
echo '</td></tr>';
}
?>
<tr><td ><?php echo func::tr('Enter your account\'s email'); ?>:</td>
<td colspan="2"><input type="text" name="email" <?php if(isset($_POST['email'])) echo 'value="', htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'), '"'; if(isset($_POST['form1']) or isset($_POST['email_readonly'])) echo " readonly " ?> size="30"></td></tr>
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

if(isset($_POST['form1']) or isset($_POST['email_readonly'])) echo '<input type="hidden" name="email_readonly" value="1">';

if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/page/page_captcha_form.php';
?>
<tr><td align="center" colspan="3">
<span style="color: yellow; font-style: italic" id="cap">&nbsp;</span>
<div style="margin: 0px; padding: 0px; font-size: 1px">&nbsp;</div><input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form()">
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onclick="return validate()"></td></tr>
</table><br>
<?php echo func::tr('Check your email carefully msg'); ?>
<?php
if(config::get('max_activation_emails')!==-1) {
echo '<hr width="90%">', func::tr('Maximum number of activation emails that can be sent'), ': ', config::get('max_activation_emails'), '<br>';
echo func::tr('Note that the system will not, for security reasons, tell you if the maximum number of emails is reached.');
}
?>
</td>
</tr>
</table>
</form>
<script>
if(captcha_exists) {
  document.getElementById('re_captcha_msg').style.visibility='visible';
  captcha_img_style=document.getElementById('captcha_image').style;
  captcha_img_style.cursor='hand';
  if(captcha_img_style.cursor!='hand') captcha_img_style.cursor='pointer';
}
</script>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
