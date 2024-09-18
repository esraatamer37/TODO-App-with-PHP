<?php
session_start();

session_unset();
session_destroy();

setcookie('user_id', '', time() - 60, "/");
setcookie('username', '', time() - 60, "/");

header("Location: login.php");
exit();
?>
