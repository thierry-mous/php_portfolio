<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$connected = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

error_log("Session check - ID: " . session_id());
error_log("Session data: " . print_r($_SESSION, true));
error_log("Connected status: " . ($connected ? "true" : "false"));