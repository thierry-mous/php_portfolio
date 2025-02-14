<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/includes/cnx_bdd.php';
require_once $root . '/includes/connected.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];

    $stmt = $connexion->prepare("DELETE FROM projects WHERE id = :project_id AND user_id = :user_id");
    $stmt->execute([
        'project_id' => $project_id,
        'user_id' => $_SESSION['user_id']
    ]);

    header('Location: manage_projects.php?success=1');
    exit();
} else {
    die("No project ID provided.");
}