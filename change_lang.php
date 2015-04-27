<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

define('CHANGE_LANG_PAGE', true);

require 'include/common.php';

if(!isset($_GET['antixsrf_token']) or ANTIXSRF_TOKEN4GET!==$_GET['antixsrf_token']) exit('<h3 align=center>XSRF prevention mechanism triggered!</h3>');

if(config::get('lang')==='en') setcookie('reg8log_lang', 'fa', time()+60*60*24*365, '/', null, HTTPS, true);
else setcookie('reg8log_lang', 'en', time()+60*60*24*365, '/', null, HTTPS, true);

if(isset($_SERVER['HTTP_REFERER'])) header("Location: {$_SERVER['HTTP_REFERER']}");
else if(isset($_GET['addr'])) header("Location: {$_GET['addr']}");
else header("Location: index.php");

?>