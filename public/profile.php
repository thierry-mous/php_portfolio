<?php
require_once __DIR__ . '/../includes/connected.php';
require_once __DIR__ . '/../includes/cnx_bdd.php';
require_once __DIR__ . '/../functions/user_functions.php';

if (!$connected) {
    header('Location: login.php');
    exit();
}

$user = [];

try {
    $connexion = new PDO($db_dsn, $db_user, $db_password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $connexion->prepare("SELECT * FROM user WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmtInterests = $connexion->query("SELECT * FROM interests ORDER BY name");
    $allInterests = $stmtInterests->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $connexion->prepare("SELECT interest_id, level FROM user_interests WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $userInterests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $interestsStmt = $connexion->query("SELECT id, name FROM interests");
    $allInterests = $interestsStmt->fetchAll(PDO::FETCH_ASSOC);
    $interestLevels = [];

foreach ($userInterests as $userInterest) {
    $interestLevels[$userInterest['interest_id']] = $userInterest['level'];
}

    if (!$user) {
        throw new Exception('Utilisateur non trouvé');
    }

    $stmtProjects = $connexion->prepare("SELECT * FROM projects WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmtProjects->execute(['user_id' => $_SESSION['user_id']]);
    $projects = $stmtProjects->fetchAll(PDO::FETCH_ASSOC);

} catch(\Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error_message'] = "Erreur lors de la récupération des données";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - PHP Portfolio</title>
    <link rel="stylesheet" href="../assets/profile.css">
</head>
<body>
    <?php require_once __DIR__ . '/../templates/header-connected.php'; ?>
    
    <main>
        <div class="container">
            <h1>Profil de <?= htmlspecialchars($user['username']) ?></h1>
            
            <?php if(isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo htmlspecialchars($_SESSION['error_message']); 
                    unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo htmlspecialchars($_SESSION['success_message']); 
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Profil mis à jour avec succès!
                </div>
            <?php endif; ?>

            <div>
                <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" style="width: 150px; height: auto;">
            </div>

            <div>
                <p>Bio: <?= htmlspecialchars($user['bio'] ?? 'Aucune bio disponible.') ?></p>
            </div>
            <style>
            .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        select, button {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .interest-list {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
        }
        .interest-item {
            margin: 5px;
            padding: 5px; /* Reduced padding */
            background-color: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 4px;
            flex: 1 1 22%; /* Adjusts to fit four items per row */
            position: relative;
            text-align: center;
            font-size: 14px; /* Smaller font size */
        }
        .interest-item strong {
            display: block;
            margin-bottom: 3px; /* Reduced margin */
            font-size: 14px; /* Smaller font size */
            color: #333;
        }
        .progress-bar {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            height: 8px; /* Smaller height */
            margin-top: 3px; /* Reduced margin */
        }
        .progress-indicator {
            height: 100%;
            border-radius: 10px;
            text-align: center;
            line-height: 8px; /* Center text vertically */
            color: white;
            transition: width 0.3s;
            font-size: 10px; /* Smaller font size */
        }
        .level-1 { background-color: #4caf50; } /* Green */
        .level-2 { background-color: #ffeb3b; } /* Yellow */
        .level-3 { background-color: #ff9800; } /* Orange */
        .level-4 { background-color: #f44336; } /* Red */

        .delete-button {
        cursor: pointer;
        color: red;
        font-weight: bold;
        position: absolute; /* Position it absolutely */
        top: 5px; /* Adjust as needed */
        right: 5px; /* Adjust as needed */
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profil de <?= htmlspecialchars($user['username']) ?></h1>

        <h2>Ajouter un Intérêt</h2>
        <div class="form-group">
            <label for="interestSelect">Sélectionnez votre intérêt:</label>
            <select id="interestSelect" onchange="showLevelOptions(this)">
                <option value="">-- Choisissez un intérêt --</option>
                <?php foreach ($allInterests as $interest): ?>
                    <option value="<?= $interest['id'] ?>"><?= htmlspecialchars($interest['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="levelContainer" style="display: none;">
            <label for="levelSelect">Sélectionnez votre niveau:</label>
            <select id="levelSelect">
                <option value="1">Débutant</option>
                <option value="2">Intermédiaire</option>
                <option value="3">Avancé</option>
                <option value="4">Expert</option>
            </select>
        </div>
        <button onclick="addInterest()">Ajouter l'Intérêt</button>

        <h2>Mes Intérêts et Niveaux</h2>
        <div class="interest-list" id="interestList">
            <?php foreach ($userInterests as $userInterest): ?>
                <div class="interest-item" data-interest-id="<?= $userInterest['interest_id'] ?>">
                    <strong>
                        <?= htmlspecialchars($allInterests[array_search($userInterest['interest_id'], array_column($allInterests, 'id'))]['name']) ?>
                    </strong>
                    Niveau: <?= htmlspecialchars($userInterest['level']) ?>
                    <div class="progress-bar">
                        <div class="progress-indicator level-<?= htmlspecialchars($userInterest['level']) ?>" style="width: <?= htmlspecialchars($userInterest['level'] * 25) ?>%;"><?= htmlspecialchars($userInterest['level']) ?></div>
                    </div>
                    <span class="delete-button" onclick="deleteInterest(this)">X</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function showLevelOptions(select) {
            const levelContainer = document.getElementById('levelContainer');
            levelContainer.style.display = select.value ? 'block' : 'none';
        }

        function addInterest() {
            const interestSelect = document.getElementById('interestSelect');
            const levelSelect = document.getElementById('levelSelect');
            const interestList = document.getElementById('interestList');

            if (interestSelect.value && levelSelect.value) {
                const interestId = interestSelect.value; // Get the selected interest ID
                const level = parseInt(levelSelect.value); // Get the selected level
                const levelText = levelSelect.options[levelSelect.selectedIndex].text; // Get the level text
                const interestName = interestSelect.options[interestSelect.selectedIndex].text; // Get the interest name

                // Send the interest and level to the server
                fetch('add_interest.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ interestId, level }), // Send as JSON
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Create a new interest item
                        const interestItem = document.createElement('div');
                        interestItem.className = 'interest-item';
                        interestItem.setAttribute('data-interest-id', interestId);
                        interestItem.innerHTML = `
                            <strong>${interestName}</strong>
                            Niveau: ${levelText}
                            <div class="progress-bar">
                                <div class="progress-indicator level-${level}" style="width: ${level * 25}%;">${level}</div>
                            </div>
                            <span class="delete-button" onclick="deleteInterest(this)">X</span>
                        `;

                        interestList.appendChild(interestItem); // Append the new item to the list

                        // Reset the dropdowns
                        interestSelect.value = '';
                        levelSelect.value = '1'; // Reset to default
                        document.getElementById('levelContainer').style.display = 'none';
                    } else {
                        alert('Erreur lors de l\'ajout de l\'intérêt: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de l\'ajout de l\'intérêt.');
                });
            }
        }

        function deleteInterest(button) {
    const interestItem = button.parentElement; // Get the parent interest item
    const interestId = interestItem.getAttribute('data-interest-id'); // Get the interest ID

    // Send a request to delete the interest
    fetch('delete_interest.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ interestId }), // Send as JSON
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the interest item from the UI
            interestItem.remove();
        } else {
            alert('Erreur lors de la suppression de l\'intérêt: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de la suppression de l\'intérêt.');
    });
}
    </script>
    
            <h2>Modifier mon Profil</h2>
            <form method="POST" action="update_profile.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="profile_picture">Photo de profil</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>

                <button type="submit">Mettre à jour</button>
            </form>

            <h2>Mes Projets</h2>
            <div class="projects-grid">
                <?php if (empty($projects)): ?>
                    <p>Aucun projet n'a été ajouté pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card">
                            <img src="<?= htmlspecialchars($project['image_path']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                            <h3><?= htmlspecialchars($project['title']) ?></h3>
                            <p><?= htmlspecialchars($project['description']) ?></p>
                            <a href="<?= htmlspecialchars($project['project_link']) ?>" target="_blank" class="project-link">Voir le projet</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .profile-info {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .profile-picture {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-right: 20px;
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
    </style>

</body>
</html> 
