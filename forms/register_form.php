<?php
require_once __DIR__ . '/../includes/connected.php';

if ($connected) {
    header('Location: ../public/profile.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de compte</title>
    <link rel="stylesheet" href="../assets/register_form_style.css">
</head>
<body>
    <?php include_once __DIR__ . '/../templates/header-not-connected.php'; ?>
    
    <main>
        <h1>Création de compte</h1>
        
        <form action="/public/register.php" method="post" class="register-form">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmation du mot de passe</label>
                <input type="password" name="password_confirm" id="password_confirm" required>
            </div>

            <button type="submit" class="btn-submit">S'inscrire</button>
        </form>

        <p>Déjà inscrit ? <a href="login_form.php">Connectez-vous ici</a></p>
    </main>
</body>
</html>