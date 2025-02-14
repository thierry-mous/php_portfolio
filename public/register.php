<?php
require_once __DIR__ . '/../includes/connected.php';
require_once __DIR__ . '/../includes/cnx_bdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Email invalide";
            header('Location: register.php');
            exit();
        }

        if ($password !== $password_confirm) {
            $_SESSION['error_message'] = "Les mots de passe ne correspondent pas";
            header('Location: register.php');
            exit();
        }

        $connexion = new PDO($db_dsn, $db_user, $db_password);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $connexion->prepare("SELECT id FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $_SESSION['error_message'] = "Cet email est déjà utilisé";
            header('Location: register.php');
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $connexion->prepare("INSERT INTO user (email, password) VALUES (:email, :password)");
        $stmt->execute([
            'email' => $email,
            'password' => $hashed_password
        ]);

        $user_id = $connexion->lastInsertId();

        $_SESSION['user_id'] = $user_id;
        $_SESSION['success_message'] = "Compte créé avec succès !";

        header('Location: profile.php');
        exit();

    } catch(\Exception $e) {
        error_log($e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue lors de l'inscription";
        header('Location: register.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de compte</title>
    <link rel="stylesheet" href="../assets/register.css">
</head>
<body>
    <main>
        <h1>Création de compte</h1>
        
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo htmlspecialchars($_SESSION['error_message']); 
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="post" class="register-form">
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

        <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici</a></p>
    </main>
</body>
</html>