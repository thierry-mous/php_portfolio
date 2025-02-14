<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/includes/cnx_bdd.php';
require_once $root . '/includes/connected.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['project_image'];
            
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mime_type, $allowed_types)) {
                throw new Exception('Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.');
            }
            
            if ($file['size'] > $max_size) {
                throw new Exception('Image trop grande. Maximum 5MB.');
            }
            
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_' . time() . '.' . $extension;
            
            $upload_dir = $root . '/uploads/projects';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $destination = $upload_dir . '/' . $new_filename;
            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                throw new Exception('Erreur lors du téléchargement de l\'image.');
            }
            
            $stmt = $connexion->prepare("
                INSERT INTO projects (user_id, title, description, image_path, project_link, created_at) 
                VALUES (:user_id, :title, :description, :image_path, :project_link, NOW())
            ");
            
            $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'image_path' => '/uploads/projects/' . $new_filename,
                'project_link' => $_POST['project_link']
            ]);
            
            header("Location: index.php?success=1");
            exit();
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = "error";
    }
}

include_once $root . '/templates/header-connected.php';
?>

<div class="container">
    <h1>Ajouter un Projet</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="add-project">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" required maxlength="255">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="project_image">Image</label>
                <input type="file" id="project_image" name="project_image" required accept="image/jpeg,image/png,image/gif">
                <small>JPG, PNG ou GIF. Max 5MB.</small>
            </div>

            <div class="form-group">
                <label for="project_link">Lien du projet</label>
                <input type="url" id="project_link" name="project_link" placeholder="https://...">
            </div>

            <button type="submit">Ajouter le projet</button>
        </form>
    </div>
</div>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.alert {
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>