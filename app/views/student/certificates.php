<?php
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
    <title>Mes Certifications - LMS</title>
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

        .grid-layout {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            padding: 10px 0;
        }

        .cert-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border: 2px solid #e2e8f0;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .cert-card::before {
            content: "🏅";
            position: absolute;
            right: -10px;
            top: -10px;
            font-size: 80px;
            opacity: 0.1;
        }

        .cert-card h3 {
            color: #0f172a;
            margin-top: 0;
            font-size: 18px;
        }

        .btn-view {
            display: block;
            text-align: center;
            background: var(--accent-success);
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            margin-top: 15px;
            transition: background 0.2s;
        }
        .btn-view:hover { background: #059669; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div>
            <h2>LMS Academic</h2>
            <nav style="margin-top: 30px;">
                <a href="index.php?action=dashboard">📚 Mes Cours</a>
                <a href="index.php?action=certificates" class="active">🏅 Mes Certificats</a>
            </nav>
        </div>
        <a href="index.php?action=logout" style="background-color: #ef4444; color: white; text-align: center;">🚪 Déconnexion</a>
    </aside>

    <main class="main-content">
        <div style="margin-bottom: 40px;">
            <h1 style="margin: 0; color: #0f172a;">Vos Certifications Honorifiques 🏅</h1>
            <p style="color: #64748b; margin-top: 5px;">Félicitations pour votre assiduité. Retrouvez ici vos diplômes d'excellence.</p>
        </div>

        <div class="grid-layout">
            <?php if (!empty($certifications)): ?>
                <?php foreach ($certifications as $cert): ?>
                    <div class="cert-card">
                        <div>
                            <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: var(--accent); letter-spacing: 1px;">Certificat de Réussite</span>
                            <h3><?php echo htmlspecialchars($cert['course_title']); ?></h3>
                            <p style="font-size: 13px; color: #64748b; margin: 5px 0;">
                                <strong>Délivré par :</strong> <?php echo htmlspecialchars($cert['teacher_name']); ?>
                            </p>
                            <p style="font-size: 12px; color: #94a3b8; font-family: monospace;">
                                ID: <?php echo htmlspecialchars($cert['code_verification']); ?>
                            </p>
                        </div>
                        
                        <div>
                            <p style="font-size: 12px; color: #64748b; margin-top: 15px;">
                                Obtenu le : <?php echo date('d/m/Y', strtotime($cert['obtained_at'])); ?>
                            </p>
                            <a href="#" class="btn-view" onclick="alert('Génération visuelle du certificat bientôt disponible (Prochaine étape avec PDF) !')">
                                👁️ Visualiser le Diplôme
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; color: #94a3b8; font-style: italic; margin-top: 40px;">
                    Vous n'avez pas encore obtenu de certificat. Complétez vos cours à 100% pour les débloquer !
                </p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>