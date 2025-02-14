<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../includes/connected.php';

if (isset($connected) && $connected) {
    header('Location: profile.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - PHP Portfolio</title>
    <link rel="stylesheet" href="../assets/login_form_style.css">
</head>
<body>
    <main>
        <h1>Connexion</h1>
        
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo htmlspecialchars($_SESSION['error_message']); 
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <form action="/public/login.php" method="post" class="login-form">
            <div class="form-group">
                <label for="login">Email ou nom d'utilisateur</label>
                <input type="text" name="login" id="login" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn-submit">Se connecter</button>
        </form>

        <p>Pas encore de compte ? <a href="/public/register.php">Créer un compte</a></p>
    </main>
</body>
</html>