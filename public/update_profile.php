<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/includes/cnx_bdd.php';
require_once $root . '/includes/connected.php';
require_once $root . '/functions/user_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $connexion = new PDO($db_dsn, $db_user, $db_password);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $connexion->beginTransaction();

        $updates = [];
        $params = ['id' => $_SESSION['user_id']];

        if (!empty($_POST['email'])) {
            $updates[] = "email = :email";
            $params['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        }

        if (!empty($_POST['username'])) {
            $stmt = $connexion->prepare("SELECT username, username_changed FROM user WHERE id = :user_id");
            $stmt->execute(['user_id' => $_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                die("User not found.");
            }
            var_dump($user);

            if (!isset($user['username']) || $user['username'] === null) {
                die("Username not found.");
            }

            if ($user['username_changed'] == 1 && $_POST['username'] !== $user['username']) {
                die("Vous ne pouvez changer votre nom d'utilisateur qu'une seule fois.");
            }

            $checkUsernameStmt = $connexion->prepare("SELECT id FROM user WHERE username = :username AND id != :id");
            $checkUsernameStmt->execute([
                'username' => $_POST['username'],
                'id' => $_SESSION['user_id']
            ]);

            if ($checkUsernameStmt->rowCount() === 0) {
                $updates[] = "username = :username";
                $updates[] = "username_changed = 1";
                $params['username'] = trim($_POST['username']);
            } else {
                $_SESSION['error_message'] = "Ce nom d'utilisateur est déjà pris.";
                header('Location: profile.php');
                exit();
            }
        }

        if (!empty($_POST['password']) && $_POST['password'] === $_POST['confirm_password']) {
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $connexion->prepare("UPDATE user SET password = :password WHERE id = :user_id");
            $stmt->execute([
                'password' => $hashed_password,
                'user_id' => $_SESSION['user_id']
            ]);
        } elseif (!empty($_POST['password'])) {
            $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
            header('Location: profile.php');
            exit();
        }

// Handle interests update
if (isset($_POST['interests'])) {
    // Delete existing interests
    $deleteStmt = $connexion->prepare("DELETE FROM user_interests WHERE user_id = :user_id");
    $deleteStmt->execute(['user_id' => $user_id]);

    // Insert new interests with levels
    $insertStmt = $connexion->prepare(
        "INSERT INTO user_interests (user_id, interest_id, level) VALUES (:user_id, :interest_id, :level)"
    );

    foreach ($_POST['interests'] as $interestId) {
        $level = isset($_POST['level'][$interestId]) ? $_POST['level'][$interestId] : 1;
        $insertStmt->execute([
            'user_id' => $user_id,
            'interest_id' => $interestId,
            'level' => $level
        ]);
    }
}

// After successful update
$_SESSION['success_message'] = "Profil mis à jour avec succès";
header('Location: profile.php');
exit();

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_picture'];
            $upload_dir = $root . '/uploads/profile_pictures/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $new_filename = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
                // Update the database with the new profile picture path
                $profile_picture_path = 'uploads/profile_pictures/' . $new_filename; // Store relative path
                $stmt = $connexion->prepare("UPDATE user SET profile_picture = :profile_picture WHERE id = :user_id");
                $stmt->execute([
                    'profile_picture' => $profile_picture_path,
                    'user_id' => $_SESSION['user_id']
                ]);
            } else {
                echo "Failed to move uploaded file.";
            }
        } else {
            echo "No file uploaded or there was an upload error.";
        }

        if (!empty($updates)) {
            $sql = "UPDATE user SET " . implode(', ', $updates) . " WHERE id = :id";
            error_log("SQL Query: " . $sql); // Debug log
            $stmt = $connexion->prepare($sql);
            $stmt->execute($params);
        }

        $stmt = $connexion->prepare("UPDATE user SET email = :email, bio = :bio WHERE id = :user_id");
        $stmt->execute([
            'email' => $_POST['email'],
            'bio' => $_POST['bio'],
            'user_id' => $_SESSION['user_id']
        ]);

        $connexion->commit();
        $_SESSION['success_message'] = "Profil mis à jour avec succès";
        header('Location: profile.php');
        exit();

    } catch(\Exception $e) {
        $connexion->rollBack();
        error_log($e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue lors de la mise à jour du profil";
        header('Location: profile.php');
        exit();
    }
}

header('Location: profile.php');
exit();