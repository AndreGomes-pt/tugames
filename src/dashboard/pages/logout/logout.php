<?php
session_start();
$_SESSION['user_id'] = array();
$_SESSION['username'] = array();
$_SESSION['login_time'] = array();
$_SESSION['is_admin'] = array();
session_destroy();

header('Location: ../login/login.php');
?>