<?php
function restrictTo($allowedRoles) {
    // Si l'utilisateur n'est pas connecté
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?action=login');
        exit;
    }

    // Si le rôle de l'utilisateur n'est pas dans le tableau des rôles autorisés
    if (!in_array($_SESSION['user']['role'], $allowedRoles)) {
        http_response_code(403);
        echo "<h1>403 - Accès Interdit : Vous n'avez pas les droits requis.</h1>";
        exit;
    }
}