<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/includes/cnx_bdd.php';
require_once $root . '/includes/connected.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    $stmt = $connexion->prepare("SELECT * FROM projects WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $project_id, 'user_id' => $_SESSION['user_id']]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        echo "Project not found.";
        exit();
    }
} else {
    echo "No project ID provided.";
    exit();
}

include_once $root . '/templates/header-connected.php';
?>
<link rel="stylesheet" href="../assets/manage_projects.css">
<div class="container">
    <h1>Modifier le Projet</h1>
    <form method="POST" action="update_project.php" enctype="multipart/form-data">
        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['id']) ?>">
        
        <div>
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($project['title']) ?>" required>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($project['description']) ?></textarea>
        </div>

        <div>
            <label for="project_link">Lien du Projet</label>
            <input type="url" id="project_link" name="project_link" value="<?= htmlspecialchars($project['project_link']) ?>" required>
        </div>

        <div>
            <label for="image">Image</label>
            <input type="file" id="image" name="image">
        </div>

        <button type="submit">Mettre à jour le Projet</button>
    </form>
</div>

<?php
if (file_exists($root . '/templates/footer.php')) {
    include_once $root . '/templates/footer.php';
}
?>