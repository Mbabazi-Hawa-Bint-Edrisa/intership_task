<?php
session_start();
session_destroy();
setcookie('amtech_auth', '', time() - 3600, "/");
header("Location: auth.php");
exit();
?>