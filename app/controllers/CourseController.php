<?php
class CourseController {
    
    public function uploadLesson() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $contentType = $_POST['content_type']; // 'pdf' ou 'video'
            $file = $_FILES['lesson_file'];

            // Définir le dossier de destination
            $targetDir = __DIR__ . "/../../public/uploads/" . ($contentType === 'pdf' ? 'pdf/' : 'videos/');
            
            // Générer un nom de fichier unique pour éviter les doublons
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('lesson_', true) . '.' . $fileExtension;
            $targetFilePath = $targetDir . $newFileName;

            // Vérification des extensions autorisées
            $allowedExtensions = ($contentType === 'pdf') ? ['pdf'] : ['mp4', 'mkv', 'webm'];
            
            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                    // C'est ici que tu appelles ton modèle pour insérer en BDD :
                    // $this->courseModel->createLesson($title, $contentType, 'uploads/' . $contentType . '/' . $newFileName);
                    
                    header('Location: index.php?action=dashboard&success=1');
                    exit;
                } else {
                    echo "Erreur lors du déplacement du fichier.";
                }
            } else {
                echo "Format de fichier non valide.";
            }
        }
    }
}