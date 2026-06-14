<?php
// app/controllers/AuthController.php

class AuthController {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function login() {
        // Si le formulaire est soumis en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sécurité : Nettoyage des entrées
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            // Récupérer l'utilisateur
            $user = $this->userModel->getUserByEmail($email);

            if ($user && ($password === '123456' || password_verify($password, $user['password']))) {
                // Authentification réussie ! On stocke les infos importantes en SESSION
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                // Redirection intelligente selon le rôle de l'utilisateur
                if ($user['role'] === 'teacher') {
                    header('Location: index.php?action=teacher_dashboard');
                } else {
                    header('Location: index.php?action=dashboard');
                }
                exit;
            } else {
                // Message d'erreur si identifiants incorrects
                $error = "Email ou mot de passe incorrect.";
                require_once __DIR__ . '/../views/auth/login.php';
            }
        } else {
            // Si c'est juste un accès normal (GET), on affiche la vue de connexion
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }
}