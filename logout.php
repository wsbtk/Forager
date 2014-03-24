<?php

session_start();
$_SESSION['login'] = false;
unset($_SESSION['user']['email']);
unset($_SESSION['user']['fname']);
unset($_SESSION['user']['lname']);
unset($_SESSION['user']['permission']);
unset($_SESSION['user']);
unset($_SESSION['login']);
//session_destroy();
header("Location: index.php");
?>
