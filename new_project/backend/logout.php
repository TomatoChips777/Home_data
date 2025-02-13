<?php
require_once '../classes/User.php';
require_once '../classes/Session.php';

$user = new User();
$user->logout();

header('Location: ../login_page.php');
exit();
?>
