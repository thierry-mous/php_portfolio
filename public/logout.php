<?php
require_once __DIR__ . '/../includes/connected.php';

session_unset();
session_destroy();

header('Location: login.php');
exit();
?>