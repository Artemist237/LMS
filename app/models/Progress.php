<?php
class Progress {
    private $db;

    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
    }

    // Enregistre ou met à jour la note de l'étudiant pour une leçon
    public function saveQuizScore($studentId, $lessonId, $score, $isCompleted) {
        $sql = "INSERT INTO student_progress (student_id, lesson_id, quiz_score, is_completed) 
                VALUES (:student_id, :lesson_id, :score, :is_completed)
                ON DUPLICATE KEY UPDATE quiz_score = :score, is_completed = :is_completed";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':student_id' => $studentId,
            ':lesson_id' => $lessonId,
            ':score' => $score,
            ':is_completed' => $isCompleted
        ]);
    }

    // Calcule dynamiquement le % de progression de l'étudiant pour un cours donné
    public function calculateCourseProgress($studentId, $courseId) {
        // 1. Nombre total de leçons dans ce cours
        $sqlTotal = "SELECT COUNT(*) as total FROM lessons WHERE course_id = :course_id";
        $stmtTotal = $this->db->prepare($sqlTotal);
        $stmtTotal->execute([':course_id' => $courseId]);
        $totalLessons = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

        if ($totalLessons == 0) return 0;

        // 2. Nombre de leçons validées par l'étudiant
        $sqlCompleted = "SELECT COUNT(*) as completed FROM student_progress sp
                         JOIN lessons l ON sp.lesson_id = l.id
                         WHERE sp.student_id = :student_id AND l.course_id = :course_id AND sp.is_completed = 1";
        $stmtCompleted = $this->db->prepare($sqlCompleted);
        $stmtCompleted->execute([':student_id' => $studentId, ':course_id' => $courseId]);
        $completedLessons = $stmtCompleted->fetch(PDO::FETCH_ASSOC)['completed'];

        // Calcul du pourcentage
        return round(($completedLessons / $totalLessons) * 100);
    }

    // Vérifie si TOUS les cours d'un module sont validés pour attribuer le certificat
    public function checkAndIssueCertificate($studentId, $moduleId) {
        // Sélectionne tous les cours du module
        $sql = "SELECT id FROM courses WHERE module_id = :module_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':module_id' => $moduleId]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($courses as $course) {
            if ($this->calculateCourseProgress($studentId, $course['id']) < 100) {
                return false; // Au moins un cours n'est pas fini à 100%
            }
        }

        // Si tout est validé, on vérifie s'il n'a pas déjà le certificat
        $sqlCheck = "SELECT id FROM certificates WHERE student_id = :student_id AND module_id = :module_id";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([':student_id' => $studentId, ':module_id' => $moduleId]);
        
        if ($stmtCheck->rowCount() == 0) {
            // Génération du certificat
            $certCode = "CERT-" . strtoupper(uniqid());
            $sqlInsert = "INSERT INTO certificates (student_id, module_id, certificate_code) VALUES (:student_id, :module_id, :code)";
            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->execute([':student_id' => $studentId, ':module_id' => $moduleId, ':code' => $certCode]);
            return $certCode;
        }
        return true;
    }
}