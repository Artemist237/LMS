<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Enseignant - LMS Academic</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #18bc9c;
            --bg-color: #f4f6f9;
            --text-color: #333;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar h1 { margin: 0; font-size: 24px; }
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .logout-btn:hover { background-color: #c0392b; }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .form-card h2 {
            margin-top: 0;
            color: var(--primary-color);
            border-bottom: 2px solid var(--bg-color);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background-color: #128f76;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>LMS Academic — Panel Enseignant</h1>
        <a href="index.php?action=login" class="logout-btn">Déconnexion</a>
    </div>

    <div class="container">
        <div class="welcome-card">
            <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['name']); ?> !</h2>
            <p>Depuis cet espace, vous pouvez publier instantanément vos supports de cours pour vos étudiants.</p>
        </div>

        <div class="form-card">
            <h2>Créer et publier un nouveau cours</h2>
            <form action="index.php?action=add_course" method="POST" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="title">Titre du cours :</label>
        <input type="text" id="title" name="title" required placeholder="Ex: Architecture des Réseaux Multi-aires">
    </div>

    <div class="form-group">
        <label for="description">Contenu ou description du cours :</label>
        <textarea id="description" name="description" required placeholder="Écrivez le résumé, les chapitres..."></textarea>
    </div>

    <div class="form-group">
        <label for="course_file">Joindre un support (PDF, Optionnel) :</label>
        <input type="file" id="course_file" name="course_file" accept=".pdf">
    </div>

    <button type="submit" class="submit-btn">Publier le cours</button>
</form>
        </div>
    </div>

</body>
</html>