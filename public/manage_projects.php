<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/includes/cnx_bdd.php';
require_once $root . '/includes/connected.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $connexion->prepare("SELECT * FROM projects WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once $root . '/templates/header-connected.php';
?>
<link rel="stylesheet" href="../assets/manage_projects.css">
<div class="container">
    <h1>Gérer mes Projets</h1>

    <?php if (empty($projects)): ?>
        <p>Aucun projet n'a été ajouté pour le moment.</p>
    <?php else: ?>
        <div class="projects-grid">
            <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <img src="<?= htmlspecialchars($project['image_path']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                    <h3><?= htmlspecialchars($project['title']) ?></h3>
                    <p><?= htmlspecialchars($project['description']) ?></p>
                    <a href="<?= htmlspecialchars($project['project_link']) ?>" target="_blank" class="project-link">Voir le projet</a>

                    <a href="modify_project.php?id=<?= htmlspecialchars($project['id']) ?>" class="btn btn-primary">Modifier</a>

                    <form method="POST" action="delete_project.php" style="display:inline;">
                        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                        <button type="submit" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">Supprimer</button>
                    </form>


                    
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.project-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    background: white;
}

.project-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 4px;
}

.edit-button, .delete-button {
    display: inline-block;
    margin-top: 10px;
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.delete-button {
    background-color: #dc3545;
}
</style>
<?php
if (file_exists($root . '/templates/footer.php')) {
    include_once $root . '/templates/footer.php';
}
?> 