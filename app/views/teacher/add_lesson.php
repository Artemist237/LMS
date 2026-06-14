<div class="dashboard-container">
    <aside class="sidebar">
        <h2>Espace Enseignant</h2>
    </aside>

    <main class="main-content">
        <div class="card">
            <h2>Ajouter une nouvelle leçon</h2>
            <form action="/index.php?action=upload_lesson" method="POST" enctype="multipart/form-data">
                <label>Titre de la leçon</label><br>
                <input type="text" name="title" required style="width:100%; padding:8px; margin:8px 0;"><br>

                <label>Type de support</label><br>
                <select name="content_type" style="width:100%; padding:8px; margin:8px 0;">
                    <option value="pdf">Document PDF</option>
                    <option value="video">Vidéo (MP4)</option>
                </select><br>

                <label>Sélectionner le fichier</label><br>
                <input type="file" name="lesson_file" accept=".pdf,.mp4" required><br><br>

                <hr>
                <h3>Créer l'évaluation associée (Quiz)</h3>
                <label>Question 1</label>
                <input type="text" name="quiz_q1" placeholder="Ex: Quelle est la définition de..." style="width:100%; padding:8px;"><br><br>

                <button type="submit" style="background: #4f46e5; color:white; border:none; padding:12px 24px; border-radius:6px; cursor:pointer;">
                    Publier la leçon et le Quiz
                </button>
            </form>
        </div>
    </main>
</div>