<?php
// 1. Démarrage de la session globale (indispensable pour l'authentification)
session_start();

// 2. Inclusion de toutes nos configurations et classes (Modèles et Contrôleurs)
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Progress.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ProgressController.php';

// 3. Connexion unique à la base de données
$db = Database::getConnection();

// 4. Récupération de l'action dans l'URL (si aucune action, on affiche le login par défaut)
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// 5. Instanciation de nos objets (on leur passe la connexion BDD $db)
$userModel = new User($db);
$authController = new AuthController($userModel);

$progressModel = new Progress($db);
$progressController = new ProgressController($progressModel);

// 6. Le Switch (Aiguillage des requêtes)
switch ($action) {
    
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // On vérifie le hash ici !
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                if ($user['role'] === 'teacher') {
                    header('Location: index.php?action=teacher_dashboard');
                } else {
                    header('Location: index.php?action=dashboard');
                }
                exit;
            } else {
                echo "Identifiants incorrects.";
            }
        }
        // Remplace l'ancienne ligne 51 par celle-ci :
        require_once __DIR__ . '/../app/views/auth/login.php'; 
        break;

    case 'dashboard':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header('Location: index.php?action=login');
            exit;
        }
        
        $user_id = $_SESSION['user']['id'];
        
        // 1. Cours auxquels l'étudiant est inscrit (avec le statut)
        $sql_my_courses = "SELECT c.*, u.name AS teacher_name, e.status AS enrollment_status 
                           FROM enrollments e
                           JOIN courses c ON e.course_id = c.id
                           JOIN users u ON c.teacher_id = u.id
                           WHERE e.user_id = :user_id";
        $stmt = $db->prepare($sql_my_courses);
        $stmt->execute([':user_id' => $user_id]);
        $my_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 2. Catalogue : Cours disponibles auxquels l'étudiant n'est PAS ENCORE inscrit
        $sql_catalog = "SELECT c.*, u.name AS teacher_name 
                        FROM courses c
                        JOIN users u ON c.teacher_id = u.id
                        WHERE c.id NOT IN (SELECT course_id FROM enrollments WHERE user_id = :user_id)
                        ORDER BY c.id DESC";
        $stmt = $db->prepare($sql_catalog);
        $stmt->execute([':user_id' => $user_id]);
        $catalog_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../app/views/student/dashboard.php';
        break;

    case 'submit_quiz':
        $progressController->submitQuiz();
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?action=login');
        exit;
        break; // <-- Ajouté et fixé ici pour libérer le switch !
    
    case 'teacher_dashboard':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header('Location: index.php?action=login');
            exit;
        }
        require_once __DIR__ . '/../app/views/teacher/dashboard.php';
        break;
        
    case 'add_course':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header('Location: index.php?action=login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $teacher_id = $_SESSION['user']['id']; 
            $module_id = 1;
            $file_name_db = null;
    
            if (isset($_FILES['course_file']) && $_FILES['course_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
    
                $file_tmp = $_FILES['course_file']['tmp_name'];
                $file_original_name = $_FILES['course_file']['name'];
                $file_ext = strtolower(pathinfo($file_original_name, PATHINFO_EXTENSION));
    
                if ($file_ext === 'pdf') {
                    $file_name_db = time() . '_' . uniqid() . '.pdf';
                    $destination = $upload_dir . $file_name_db;
    
                    if (!move_uploaded_file($file_tmp, $destination)) {
                        die("Erreur : Impossible de déplacer le fichier dans le dossier uploads.");
                    }
                } else {
                    die("Erreur : Seuls les fichiers PDF sont acceptés.");
                }
            } elseif (isset($_FILES['course_file']) && $_FILES['course_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                die("Code erreur d'upload PHP : " . $_FILES['course_file']['error']);
            }
    
            $sql = "INSERT INTO courses (title, description, teacher_id, module_id, file_path) 
                    VALUES (:title, :description, :teacher_id, :module_id, :file_path)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':teacher_id' => $teacher_id,
                ':module_id' => $module_id,
                ':file_path' => $file_name_db
            ]);
    
            header('Location: index.php?action=teacher_dashboard');
            exit;
        }
        break;

    case 'certificates':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header('Location: index.php?action=login');
            exit;
        }
        
        $user_id = $_SESSION['user']['id'];
        
        $sql = "SELECT cert.*, c.title AS course_title, u.name AS teacher_name
                FROM certifications cert
                JOIN courses c ON cert.course_id = c.id
                JOIN users u ON c.teacher_id = u.id
                WHERE cert.user_id = :user_id
                ORDER BY cert.obtained_at DESC";
                
        $stmt = $db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $certifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../app/views/student/certificates.php';
        break;

    case 'enroll':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header('Location: index.php?action=login');
            exit;
        }
        
        if (isset($_GET['course_id'])) {
            $course_id = intval($_GET['course_id']);
            $user_id = $_SESSION['user']['id'];
            
            $sql = "INSERT IGNORE INTO enrollments (user_id, course_id, status) VALUES (:user_id, :course_id, 'active')";
            $stmt = $db->prepare($sql);
            $stmt->execute([':user_id' => $user_id, ':course_id' => $course_id]);
        }
        header('Location: index.php?action=dashboard');
        exit;
        break;
        
    case 'complete_course':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header('Location: index.php?action=login');
            exit;
        }
        
        if (isset($_GET['course_id'])) {
            $course_id = intval($_GET['course_id']);
            $user_id = $_SESSION['user']['id'];
            
            $sql_update = "UPDATE enrollments SET status = 'completed', completed_at = NOW() 
                           WHERE user_id = :user_id AND course_id = :course_id";
            $stmt = $db->prepare($sql_update);
            $stmt->execute([':user_id' => $user_id, ':course_id' => $course_id]);
            
            $code_verification = 'CERT-' . strtoupper(uniqid());
            
            $sql_cert = "INSERT IGNORE INTO certifications (user_id, course_id, code_verification) 
                         VALUES (:user_id, :course_id, :code)";
            $stmt = $db->prepare($sql_cert);
            $stmt->execute([
                ':user_id' => $user_id,
                ':course_id' => $course_id,
                ':code' => $code_verification
            ]);
        }
        header('Location: index.php?action=certificates');
        exit;
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/views/register.php';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];
    
            try {
                $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $password,
                    ':role' => $role
                ]);
                header('Location: index.php?action=login');
                exit;
            } catch (PDOException $e) {
                echo "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }
        break;

    default:
        http_response_code(404);
        echo "<h1>Erreur 404 : Page introuvable</h1>";
        break;
}