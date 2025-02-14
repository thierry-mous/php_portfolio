<?php
function isAdmin($connexion, $userId) {
    try {
        $stmt = $connexion->prepare("SELECT is_admin FROM user WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user && $user['is_admin'] == true;
    } catch(\Exception $e) {
        return false;
    }
}