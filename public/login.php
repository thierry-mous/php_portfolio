<?php

session_start();

require_once __DIR__ . '/../includes/connected.php';
require_once __DIR__ . '/../includes/cnx_bdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $login = $_POST['login'];
        $password = $_POST['password'];

        error_log("Login attempt - Login: " . $login);

        $connexion = new PDO($db_dsn, $db_user, $db_password);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $connexion->prepare("SELECT * FROM user WHERE email = :login OR username = :login");
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        error_log("User found: " . ($user ? "Yes" : "No"));

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            
            error_log("Login successful - User ID: " . $user['id']);
            error_log("Session data: " . print_r($_SESSION, true));

            header('Location: index.php');
            exit();
        } else {

            error_log("Login failed - Invalid credentials");
            $_SESSION['error_message'] = "Email/nom d'utilisateur ou mot de passe incorrect";
            header('Location: login.php');
            exit();
        }

    } catch(\Exception $e) {
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue lors de la connexion";
        header('Location: login.php');
        exit();
    }
} else {
    include __DIR__ . '/../forms/login_form.php';
    exit();
}

if (file_exists($root . '/templates/footer.html')) {
    include_once $root . '/templates/footer.html';
} 
 