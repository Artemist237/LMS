<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - LMS Academic</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Petit style rapide pour centrer le formulaire proprement */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8fafc;
            margin: 0;
            font-family: sans-serif;
        }
        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #334155; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 12px; background: #4f46e5; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
        .btn-login:hover { background: #4338ca; }
        .error-msg { color: #ef4444; background: #fee2e2; padding: 10px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>

<div class="login-card">
    <h2 style="text-align: center; color: #0f172a; margin-bottom: 6px;">LMS Plateforme</h2>
    <p style="text-align: center; color: #64748b; margin-top: 0; margin-bottom: 24px;">Connectez-vous à votre espace</p>

    <?php if (isset($error)): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="index.php?action=login" method="POST">
        <div class="form-group">
            <label for="email">Adresse Email</label>
            <input type="email" id="email" name="email" required placeholder="etudiant@univ.cm">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-login">Se connecter</button>
    </form>

    <p style="text-align: center; margin-top: 15px; font-size: 14px;">
    Vous n'avez pas de compte ? 
    <a href="index.php?action=register" style="color: #4f46e5; text-decoration: none; font-weight: bold;">
        Créez un compte ici
    </a>
</p>

</div>

</body>
</html>