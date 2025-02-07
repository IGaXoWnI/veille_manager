<?php
class DashboardController {
    private $userModel;
    private $presentationModel;
    private $subjectModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->userModel = new User();
        $this->presentationModel = new Presentation();
        $this->subjectModel = new Subject();
    }

    public function index() {
        if ($_SESSION['user_role'] === 'teacher') {
            header('Location: ' . BASE_URL . '/dashboard/teacher');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/student');
        }
        exit;
    }

    public function teacherDashboard() {
        if ($_SESSION['user_role'] !== 'teacher') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $subjectModel = new Subject();
        $presentationModel = new Presentation();
        $userModel = new User();

        $pendingSubjects = $subjectModel->getPendingSubjects();
        $approvedSubjectsCount = $subjectModel->getApprovedSubjectsCount();
        $todayPresentations = $presentationModel->getTodayPresentationsCount();
        $upcomingPresentations = $presentationModel->getUpcomingPresentationsCount();
        $totalPresentations = $presentationModel->getTotalPresentationsCount();
        $totalStudents = $userModel->getActiveStudentsCount();

        require APP_PATH . '/app/views/dashboard/teacher.php';
    }

    public function studentDashboard() {
        if ($_SESSION['user_role'] !== 'student') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $upcomingPresentations = $this->presentationModel->getUpcomingPresentationsForStudent($_SESSION['user_id']);
        
        require APP_PATH . '/app/views/dashboard/student.php';
    }
} 