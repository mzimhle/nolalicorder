<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
error_reporting(E_ALL);
/*** Standard includes */
require_once 'config/database.php';
require_once 'auth.php';
global $zfsession;
// Clear the identity from the session
$zfsession->identity = null;
$zfsession->activeParticipant = null;
unset($zfsession->identity, $zfsession->activeParticipant);
//redirect to login page
header('Location: /');
exit;
?>