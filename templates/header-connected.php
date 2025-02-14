<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($connexion)) {
    require_once __DIR__ . '/../includes/cnx_bdd.php';
}
require_once __DIR__ . '/../includes/admin_check.php';

$isAdmin = false;
if (isset($_SESSION['user_id'])) {
    $isAdmin = isAdmin($connexion, $_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Portfolio</title>
    <link rel="stylesheet" href="/assets/header.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/public/index.php">Accueil</a></li>
                <li><a href="/public/profile.php">Profil</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="/public/admin/manage_interests.php">Gérer les compétences</a></li>
                <?php endif; ?>
                <li><a href="/public/manage_projects.php" class="manage-button">Gérer mes Projets</a></li>
                <li>
                    <form action="/public/logout.php" method="post" style="display: inline;">
                        <button type="submit" class="btn-logout">Déconnexion</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>