<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($GLOBALS['aes'])) {
	require_once ROOT.'include/class/class_aes_cipher.php';
	$GLOBALS['aes'] = new Crypt_AES();//default mode: CBC
}

$GLOBALS['aes']->setKey(pack('H*', md5($GLOBALS['pepper'].SITE_ENCR_KEY.$GLOBALS['client_sess_key'])));

function encrypt($str) {
	return $GLOBALS['aes']->IvEncryptHmac($str);
}

function decrypt($str) {
	return $GLOBALS['aes']->IvDecryptHmac($str);
}

?>
