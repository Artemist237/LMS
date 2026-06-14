<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - LMS</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; background: #f4f6f9; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 350px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #4f46e5; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Créer un compte</h2>
        <form action="index.php?action=register" method="POST">
            <input type="text" name="name" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <select name="role">
                <option value="student">Étudiant</option>
                <option value="teacher">Enseignant</option>
            </select>
            <button type="submit">S'inscrire</button>
        </form>
        <p style="text-align:center;"><a href="index.php?action=login">Déjà un compte ? Connectez-vous</a></p>
    </div>
</body>
</html>