<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

return array(
	'get_autologin_ages'=>'func_autologin_ages.php',
	'captcha_show_image'=>'func_captcha.php',
	'captcha_verify_word'=>'func_captcha.php',
	'duration2friendly_str'=>'func_duration2friendly_str.php',
	'encrypt'=>'func_encryption_with_site8client_keys.php',
	'decrypt'=>'func_encryption_with_site8client_keys.php',
	'get_relative_root_path'=>'func_get_relative_root_path.php',
	'inet_pton2'=>'func_inet.php',
	'inet_ntop2'=>'func_inet.php',
	'fix_kaaf8yeh'=>'func_kaaf8yeh.php',
	'my_exit'=>'func_my_exit.php',
	'crypt_random'=>'func_random.php',
	'random_bytes'=>'func_random.php',
	'random_string'=>'func_random.php',
	'shutdown_session'=>'func_shutdown_session.php',
	'verify_hmac'=>'func_site8client_keys_hmac_verifier.php',
	'tr'=>'func_tr.php',
	'utf8_strlen'=>'func_utf8.php',
);

?>