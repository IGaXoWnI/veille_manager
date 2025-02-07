<?php
class PresentationController {
    private $presentationModel;
    private $subjectModel;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->presentationModel = new Presentation();
        $this->subjectModel = new Subject();
        $this->userModel = new User();
    }

    public function showScheduleForm() {
        $subjects = $this->subjectModel->getApprovedSubjects();
        $students = $this->userModel->getAllStudents();
        require APP_PATH . '/app/views/presentations/schedule.php';
    }

    public function schedule() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/presentations/schedule');
            exit;
        }

        $subject_id = $_POST['subject_id'] ?? null;
        $student_ids = $_POST['student_ids'] ?? [];
        $scheduled_date = $_POST['scheduled_date'] ?? null;

        if (!$subject_id || empty($student_ids) || !$scheduled_date) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: ' . BASE_URL . '/presentations/schedule');
            exit;
        }

        $scheduled_datetime = date('Y-m-d H:i:s', strtotime("$scheduled_date 09:00:00"));

        $presentation_id = $this->presentationModel->createPresentation([
            'subject_id' => $subject_id,
            'scheduled_date' => $scheduled_datetime
        ]);

        if ($presentation_id) {
            foreach ($student_ids as $student_id) {
                $this->presentationModel->assignStudentToPresentation($student_id, $presentation_id);
            }
            $_SESSION['success'] = 'Presentation scheduled successfully';
        } else {
            $_SESSION['error'] = 'Failed to schedule presentation';
        }

        header('Location: ' . BASE_URL . '/calendar');
        exit;
    }

    public function manageSchedule() {
        $presentations = $this->presentationModel->getAllUpcomingPresentations();
        require APP_PATH . '/app/views/presentations/manage.php';
    }

    public function showEditForm() {
        $presentation_id = $_GET['id'] ?? null;
        if (!$presentation_id) {
            header('Location: ' . BASE_URL . '/presentations/manage');
            exit;
        }

        $presentation = $this->presentationModel->getPresentationById($presentation_id);
        $subjects = $this->subjectModel->getApprovedSubjects();
        $students = $this->userModel->getAllStudents();
        $selectedStudents = $this->presentationModel->getPresentationStudents($presentation_id);

        require APP_PATH . '/app/views/presentations/edit.php';
    }

    public function updateSchedule() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/presentations/manage');
            exit;
        }

        $presentation_id = $_POST['presentation_id'] ?? null;
        $subject_id = $_POST['subject_id'] ?? null;
        $student_ids = $_POST['student_ids'] ?? [];
        $scheduled_date = $_POST['scheduled_date'] ?? null;

        if (!$presentation_id || !$subject_id || empty($student_ids) || !$scheduled_date) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: ' . BASE_URL . '/presentations/edit?id=' . $presentation_id);
            exit;
        }

        $scheduled_datetime = date('Y-m-d H:i:s', strtotime("$scheduled_date 09:00:00"));

        if ($this->presentationModel->updatePresentation($presentation_id, [
            'subject_id' => $subject_id,
            'scheduled_date' => $scheduled_datetime,
            'student_ids' => $student_ids
        ])) {
            $_SESSION['success'] = 'Presentation updated successfully';
            header('Location: ' . BASE_URL . '/presentations/manage');
        } else {
            $_SESSION['error'] = 'Failed to update presentation';
            header('Location: ' . BASE_URL . '/presentations/edit?id=' . $presentation_id);
        }
        exit;
    }

    public function deleteSchedule() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            if (!isset($_POST['presentation_id'])) {
                throw new Exception('Presentation ID is required');
            }

            $presentationId = $_POST['presentation_id'];

            if ($this->presentationModel->deletePresentation($presentationId)) {
                $_SESSION['success_message'] = 'Presentation deleted successfully';
            } else {
                throw new Exception('Failed to delete presentation');
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/subjects/manage');
        exit;
    }
} 