<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../includes/connected.php');
if(!$connected) {
    $_SESSION["destination"] = $_SERVER['PHP_SELF'];
    header('Location:login-form.php', 401);
    exit();
} 

try {
    include_once('../includes/cnx_bdd.php');
    $connexion = new PDO($db_dsn, $db_user, $db_password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // First, check if the tables exist
    $tables = $connexion->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('interests', $tables) || !in_array('user_interests', $tables)) {
        throw new Exception("Required tables do not exist. Please run the database setup script.");
    }
    
    $sql = "SELECT id, name FROM interests";
    $statement = $connexion->prepare($sql);
    $statement->execute(); 
    $interests = $statement->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT interest_id FROM user_interests WHERE user_id = :user_id";
    $statement = $connexion->prepare($sql);
    $statement->execute(['user_id' => $_SESSION['user_id'] ?? 0]); 
    $userInterests = $statement->fetchAll(PDO::FETCH_COLUMN);
    $sql = "SELECT * FROM user_profiles WHERE user_id = :user_id";
    $statement = $connexion->prepare($sql);
    $statement->execute(['user_id' => $_SESSION['user_id'] ?? 0]);
    $userProfile = $statement->fetch(PDO::FETCH_ASSOC);

    echo "<!-- Debug Info:
    Session ID: " . session_id() . "
    User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "
    Tables: " . implode(', ', $tables) . "
    -->";

} catch(\Exception $e) {
    error_log("Database Error in profile_form.php: " . $e->getMessage());
    echo "An error occurred: " . htmlspecialchars($e->getMessage());
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../assets/profile_form_style.css">
</head>
<body>
    <main>
        <h1>Mon Profil</h1>
        
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php 
                echo htmlspecialchars($_SESSION['success_message']); 
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo htmlspecialchars($_SESSION['error_message']); 
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <form action="profile.php" method="post" enctype="multipart/form-data" class="profile-form">
            <div class="form-group">
                <label for="avatar">Photo de profil</label>
                <input type="file" name="avatar" id="avatar" accept="image/*">
            </div>

            <div class="form-group">
                <label for="first_name">Prénom</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($userProfile['first_name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="last_name">Nom</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($userProfile['last_name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="bio">Biographie</label>
                <textarea name="bio" id="bio"><?php echo htmlspecialchars($userProfile['bio'] ?? ''); ?></textarea>
            </div>

            <fieldset class="form-group">
                <legend>Centres d'intérêt</legend>
                <div class="select-group">
                    <label for="interests">Sélectionnez vos centres d'intérêt</label>
                    <select name="interests[]" id="interests" multiple>
                        <?php foreach($interests as $interest): ?>
                            <option value="<?php echo htmlspecialchars($interest['id']); ?>"
                                <?php echo in_array($interest['id'], $userInterests) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($interest['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>

            <button type="submit" class="btn-submit">Enregistrer les modifications</button>
        </form>
    </main>
</body>
</html>