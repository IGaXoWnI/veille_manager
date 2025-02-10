<?php
class DashboardController {
    private $userModel;
    private $presentationModel;
    private $subjectModel;
    private $statisticsModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->userModel = new User();
        $this->presentationModel = new Presentation();
        $this->subjectModel = new Subject();
        $this->statisticsModel = new Statistics();
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

        $teacherModel = new Teacher();
        $stats = $this->statisticsModel->getTeacherDashboardStats();
        $pendingSubjects = $teacherModel->getPendingSubjects();
        
        // Extract all variables needed by the view
        $todayPresentations = $stats['todayPresentations'];
        $pendingSubjectsCount = $stats['pendingSubjectsCount'];
        $approvedSubjectsCount = $stats['approvedSubjectsCount'];
        $todayPresentationsCount = (int)$stats['todayPresentationsCount'];
        $upcomingPresentationsCount = $stats['upcomingPresentationsCount'];
        $totalPresentationsCount = $stats['totalPresentationsCount'];
        $totalStudentsCount = $stats['totalStudentsCount'];
        
        require APP_PATH . '/app/views/dashboard/teacher.php';
    }

    public function studentDashboard() {
        if ($_SESSION['user_role'] !== 'student') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $studentModel = new Student();
        $stats = $this->statisticsModel->getStudentDashboardStats($_SESSION['user_id']);
        $upcomingPresentations = $this->presentationModel->getUpcomingPresentationsForStudent($_SESSION['user_id']);
        $pastPresentations = $studentModel->getPastPresentations($_SESSION['user_id']);
        $suggestedSubjects = $this->subjectModel->getSubjectsByStudent($_SESSION['user_id']);
        
        require APP_PATH . '/app/views/dashboard/student.php';
    }
} 