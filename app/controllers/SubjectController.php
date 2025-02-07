<?php
class SubjectController {
    private $subjectModel;
    private $presentationModel;
    
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->subjectModel = new Subject();
        $this->presentationModel = new Presentation();
    }
    
    public function showSuggestForm() {
        if ($_SESSION['user_role'] !== 'student') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        require APP_PATH . '/app/views/subjects/suggest.php';
    }
    
    public function suggest() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            if (empty($_POST['title']) || empty($_POST['description'])) {
                throw new Exception('Title and description are required');
            }
            
            $this->subjectModel->create([
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'suggested_by' => $_SESSION['user_id']
            ]);
            
            $_SESSION['success_message'] = 'Subject suggested successfully';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/subjects/suggest');
            exit;
        }
    }
    
    public function approve() {
        try {
            if ($_SESSION['user_role'] !== 'teacher') {
                throw new Exception('Unauthorized access');
            }
            
            $subjectId = $_POST['subject_id'] ?? null;
            if (!$subjectId) {
                throw new Exception('Subject ID is required');
            }
            
            $this->subjectModel->updateStatus($subjectId, 'approved');
            $_SESSION['success_message'] = 'Subject approved successfully';
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }
        
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
    
    public function reject() {
    }
    
    public function manage() {
        if ($_SESSION['user_role'] !== 'teacher') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $subjects = $this->subjectModel->getAllSubjects();
        
        $presentations = $this->presentationModel->getAllUpcomingPresentations();
        
        require APP_PATH . '/app/views/subjects/manage.php';
    }
} 