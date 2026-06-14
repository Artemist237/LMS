<?php
// Exemple d'action appelée lors de la soumission d'un quiz
class ProgressController {
    private $progressModel;

    public function __construct($progressModel) {
        $this->progressModel = $progressModel;
    }

    public function submitQuiz() {
        // On force la réponse au format JSON
        header('Content-Type: application/json');

        // Sécurité : Vérifier que l'utilisateur est connecté et est un étudiant
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
            exit;
        }

        // Récupération des données POST (via Fetch/AJAX)
        $input = json_decode(file_get_contents('php://input'), true);
        $lessonId = filter_var($input['lesson_id'], FILTER_VALIDATE_INT);
        $courseId = filter_var($input['course_id'], FILTER_VALIDATE_INT);
        $moduleId = filter_var($input['module_id'], FILTER_VALIDATE_INT);
        $score = filter_var($input['score'], FILTER_VALIDATE_INT); // Note sur 100 calculée côté JS ou PHP

        $studentId = $_SESSION['user']['id'];
        $isCompleted = ($score >= 70) ? 1 : 0; // Seuil de validation à 70%

        // Sauvegarde du score
        $success = $this->progressModel->saveQuizScore($studentId, $lessonId, $score, $isCompleted);

        if ($success) {
            // Recalcul de la progression du cours
            $newProgress = $this->progressModel->calculateCourseProgress($studentId, $courseId);
            
            // Vérification de la complétion du module pour certificat
            $certIssued = $this->progressModel->checkAndIssueCertificate($studentId, $moduleId);

            echo json_encode([
                'status' => 'success',
                'progress' => $newProgress,
                'is_lesson_completed' => $isCompleted == 1,
                'certificate_earned' => is_string($certIssued) ? $certIssued : false
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'enregistrement']);
        }
    }
}