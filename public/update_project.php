<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/includes/cnx_bdd.php';
require_once $root . '/includes/connected.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $project_link = $_POST['project_link'];

    $stmt = $connexion->prepare("SELECT image_path FROM projects WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $project_id, 'user_id' => $_SESSION['user_id']]);
    $current_project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $image_path = $current_project['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = $root . '/uploads/projects/';
        $new_image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . $new_image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        } else {
            echo "Error uploading the new image. Please check the directory permissions.";
            exit();
        }
    } else {
        if (isset($_FILES['image'])) {
            echo "File upload error: " . $_FILES['image']['error'];
            exit();
        }
    }

    $stmt = $connexion->prepare("UPDATE projects SET title = :title, description = :description, project_link = :project_link, image_path = :image_path WHERE id = :id AND user_id = :user_id");
    
    $params = [
        'title' => $title,
        'description' => $description,
        'project_link' => $project_link,
        'image_path' => $image_path,
        'id' => $project_id,
        'user_id' => $_SESSION['user_id']
    ];

    if ($stmt->execute($params)) {
        header('Location: manage_projects.php?success=1');
        exit();
    } else {
        echo "Error updating project.";
    }
} else {
    echo "Invalid request.";
}
?> 