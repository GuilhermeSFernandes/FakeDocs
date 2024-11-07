<?php
session_start();
include("config.php");

if (!isset($_SESSION['temp_user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['temp_user_id'];
unset($_SESSION['temp_user_id']);
$_SESSION['usuario_id'] = $user_id;

header("Location: dashboard.php");
exit;
?>
