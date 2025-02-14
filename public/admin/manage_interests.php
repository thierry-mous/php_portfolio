<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/includes/cnx_bdd.php';
require_once $root . '/includes/connected.php';
require_once $root . '/includes/admin_check.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$isAdmin = isAdmin($connexion, $_SESSION['user_id']);
if (!$isAdmin) {
    header('Location: ../index.php');
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_interest'])) {
        $name = trim($_POST['interest_name']);
        if (!empty($name)) {
            try {
                $stmt = $connexion->prepare("INSERT INTO interests (name) VALUES (:name)");
                $result = $stmt->execute(['name' => $name]);
                
                if ($result) {
                    $message = "Compétence ajoutée avec succès!";
                    $messageType = "success";
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                    exit();
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $message = "Cette compétence existe déjà!";
                } else {
                    $message = "Une erreur est survenue lors de l'ajout: " . $e->getMessage();
                }
                $messageType = "error";
            }
        }
    }
    
    if (isset($_POST['delete_interest'])) {
        $id = $_POST['interest_id'];
        try {
            $stmt = $connexion->prepare("DELETE FROM interests WHERE id = :id");
            $stmt->execute(['id' => $id]);
            header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=1");
            exit();
        } catch (PDOException $e) {
            $message = "Une erreur est survenue lors de la suppression.";
            $messageType = "error";
        }
    }
}

if (isset($_GET['success'])) {
    $message = "Compétence ajoutée avec succès!";
    $messageType = "success";
}
if (isset($_GET['deleted'])) {
    $message = "Compétence supprimée avec succès!";
    $messageType = "success";
}

$stmt = $connexion->query("SELECT * FROM interests ORDER BY name");
$interests = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once $root . '/templates/header-connected.php';
?>
<link rel="stylesheet" href="../../assets/manage_interest.css">

<div class="container">
    <h1>Gérer les compétences</h1>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="add-interest">
        <h2>Ajouter une compétence</h2>
        <form method="POST">
            <input type="text" name="interest_name" required placeholder="Nom de la compétence" maxlength="100">
            <button type="submit" name="add_interest">Ajouter</button>
        </form>
    </div>

    <div class="interest-list">
        <h2>Compétences existantes</h2>
        <?php if (empty($interests)): ?>
            <p>Aucune compétence n'a été ajoutée pour le moment.</p>
        <?php else: ?>
            <?php foreach ($interests as $interest): ?>
                <div class="interest-item">
                    <span><?= htmlspecialchars($interest['name']) ?></span>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="interest_id" value="<?= $interest['id'] ?>">
                        <button type="submit" name="delete_interest" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette compétence ?')">
                            Supprimer
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
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
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .interest-item {
        margin: 10px 0;
        padding: 10px;
        background: #f5f5f5;
        border-radius: 4px;
    }
</style>

<?php
if (file_exists($root . '/templates/footer.php')) {
    include_once $root . '/templates/footer.php';
}
?> 