<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/includes/cnx_bdd.php';
require_once $root . '/includes/connected.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $connexion->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once $root . '/templates/header-connected.php';
?>

<div class="container">
    <div class="header-actions">
        <h1>Projets</h1>
        <a href="add_project.php" class="add-button">Ajouter un projet</a>
        <link rel="stylesheet" href="../assets/styles.css">
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Projet ajouté avec succès!
        </div>
    <?php endif; ?>

    <div class="projects-grid">
        <?php if (empty($projects)): ?>
            <p>Aucun projet n'a été ajouté pour le moment.</p>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <img src="<?= htmlspecialchars($project['image_path']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                    <h3><?= htmlspecialchars($project['title']) ?></h3>
                    <p><?= htmlspecialchars($project['description']) ?></p>
                    <?php if ($project['project_link']): ?>
                        <a href="<?= htmlspecialchars($project['project_link']) ?>" target="_blank" class="project-link">Voir le projet</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.add-button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
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

.project-link {
    display: inline-block;
    margin-top: 10px;
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.alert {
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
</style>

<?php
if (file_exists($root . '/templates/footer.php')) {
    include_once $root . '/templates/footer.php';
}
?> 