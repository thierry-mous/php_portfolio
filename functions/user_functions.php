<?php
function updateUsername($connexion, $userId, $newUsername) {
    try {
        $checkSql = "SELECT username_changed FROM users WHERE id = :user_id";
        $checkStmt = $connexion->prepare($checkSql);
        $checkStmt->execute(['user_id' => $userId]);
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($result['username_changed']) {
            return [
                'success' => false,
                'message' => 'Le nom d\'utilisateur a déjà été modifié une fois.'
            ];
        }

        $checkUsernameSql = "SELECT id FROM users WHERE username = :username AND id != :user_id";
        $checkUsernameStmt = $connexion->prepare($checkUsernameSql);
        $checkUsernameStmt->execute([
            'username' => $newUsername,
            'user_id' => $userId
        ]);

        if ($checkUsernameStmt->rowCount() > 0) {
            return [
                'success' => false,
                'message' => 'Ce nom d\'utilisateur est déjà pris.'
            ];
        }

        $updateSql = "UPDATE users 
                     SET username = :username, username_changed = TRUE 
                     WHERE id = :user_id";
        $updateStmt = $connexion->prepare($updateSql);
        $updateStmt->execute([
            'username' => $newUsername,
            'user_id' => $userId
        ]);

        return [
            'success' => true,
            'message' => 'Nom d\'utilisateur mis à jour avec succès.'
        ];

    } catch (\PDOException $e) {
        error_log("Erreur lors de la mise à jour du nom d'utilisateur : " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Une erreur est survenue lors de la mise à jour.'
        ];
    }
}