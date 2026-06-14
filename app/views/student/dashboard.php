<?php
// Sécurité : On vérifie que l'utilisateur est bien connecté et est un étudiant
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?action=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Étudiant - LMS</title>
    <style>
        :root {
            --bg-primary: #f8fafc;
            --sidebar-bg: #0f172a;
            --text-main: #334155;
            --accent: #4f46e5;
            --accent-success: #10b981;
            --card-bg: #ffffff;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: white;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
        }

        .sidebar h2 {
            margin-top: 0;
            color: #fff;
            font-size: 22px;
            border-bottom: 1px solid #334155;
            padding-bottom: 15px;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            color: #94a3b8;
            text-decoration: none;
            display: block;
            padding: 12px 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: var(--accent);
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            box-sizing: border-box;
        }

        .header-dash {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 10px 0;
        }

        .card {
            background: var(--card-bg);
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .progress-container {
            background: #e2e8f0;
            border-radius: 9999px;
            height: 8px;
            width: 100%;
            margin: 15px 0 5px 0;
            overflow: hidden;
        }

        .progress-bar {
            background: var(--accent);
            height: 100%;
            transition: width 0.4s ease-in-out;
        }

        .btn-enter, .btn-download {
            display: block;
            text-align: center;
            text-decoration: none;
            padding: 11px 20px;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 12px;
            font-size: 14px;
            transition: background 0.2s;
        }

        .btn-enter {
            background: var(--accent);
            color: white;
        }
        .btn-enter:hover { background: #4338ca; }

        .btn-download {
            background: transparent;
            color: var(--accent-success);
            border: 2px solid var(--accent-success);
            box-sizing: border-box;
        }
        .btn-download:hover {
            background: var(--accent-success);
            color: white;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div>
            <h2>LMS Academic</h2>
            <nav style="margin-top: 30px;">
                <a href="index.php?action=dashboard" class="active">📚 Mes Cours</a>
                <a href="index.php?action=certificates">🏅 Mes Certificats</a>
            </nav>
        </div>
        <a href="index.php?action=logout" style="background-color: #ef4444; color: white; text-align: center;">🚪 Déconnexion</a>
    </aside>

    <main class="main-content">
        <div class="header-dash">
            <div>
                <h1 style="margin: 0; color: #0f172a;">Bonjour, <?php echo htmlspecialchars($_SESSION['user']['name']); ?> 👋</h1>
                <p style="color: #64748b; margin-top: 5px;">Ravi de vous revoir. Voici vos modules de cours disponibles.</p>
            </div>
        </div>

        <h2 style="color: #0f172a; margin-top: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">📖 Mes Cours Suivis</h2>
        <div class="grid-layout" style="margin-bottom: 50px;">
            <?php if (!empty($my_courses)): ?>
                <?php foreach ($my_courses as $course): ?>
                    <div class="card">
                        <div>
                            <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                            <p style="color: #64748b; font-size: 14px; min-height: 50px; margin-bottom: 15px;">
                                <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                            </p>
                            <p style="font-size: 13px; color: #94a3b8; margin: 5px 0;">
                                <strong>Enseignant :</strong> <?php echo htmlspecialchars($course['teacher_name']); ?>
                            </p>
                        </div>
                        
                        <div>
                            <?php if ($course['enrollment_status'] === 'completed'): ?>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 100%; background: var(--accent-success);"></div>
                                </div>
                                <span style="font-size: 12px; color: var(--accent-success); font-weight: bold;">✅ 100% Complété</span>
                                <span style="display:block; text-align:center; color:#10b981; font-weight:bold; margin-top:15px; padding:10px; background:#ecfdf5; border-radius:6px;">
                                    🏅 Certificat Débloqué !
                                </span>
                            <?php else: ?>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: 40%;"></div>
                                </div>
                                <span style="font-size: 12px; color: #94a3b8;">40% en cours</span>
                                
                                <?php if (!empty($course['file_path'])): ?>
                                    <a href="uploads/<?php echo htmlspecialchars($course['file_path']); ?>" download class="btn-download">
                                        📄 Télécharger le cours (PDF)
                                    </a>
                                <?php endif; ?>
                                
                                <a href="index.php?action=complete_course&course_id=<?php echo $course['id']; ?>" class="btn-enter" style="background-color: var(--accent-success);">
                                    🥇 Marquer comme terminé
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; color: #94a3b8; font-style: italic;">Vous n'êtes inscrit à aucun cours pour le moment. Rejoignez-en un ci-dessous !</p>
            <?php endif; ?>
        </div>

        <h2 style="color: #0f172a; margin-top: 40px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">🔍 Catalogue des Cours Disponibles</h2>
        <div class="grid-layout">
            <?php if (!empty($catalog_courses)): ?>
                <?php foreach ($catalog_courses as $course): ?>
                    <div class="card" style="border: 1px dashed #cbd5e1;">
                        <div>
                            <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                            <p style="color: #64748b; font-size: 14px; min-height: 50px; margin-bottom: 15px;">
                                <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                            </p>
                            <p style="font-size: 13px; color: #94a3b8; margin: 5px 0;">
                                <strong>Enseignant :</strong> <?php echo htmlspecialchars($course['teacher_name']); ?>
                            </p>
                        </div>
                        
                        <div>
                            <a href="index.php?action=enroll&course_id=<?php echo $course['id']; ?>" class="btn-enter">
                                ➕ S'inscrire à ce cours
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; color: #94a3b8; font-style: italic;">Aucun nouveau cours à découvrir pour l'instant.</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>