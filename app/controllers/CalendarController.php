<?php
class CalendarController {
    private $calendarModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->calendarModel = new Calendar();
    }

    public function index() {
        $events = $this->calendarModel->getUpcomingEvents();
        
        $groupedPresentations = [];
        foreach ($events as $event) {
            $date = date('Y-m-d', strtotime($event['scheduled_date']));
            if (!isset($groupedPresentations[$date])) {
                $groupedPresentations[$date] = [];
            }
            $groupedPresentations[$date][] = [
                'scheduled_date' => $event['scheduled_date'],
                'subject_title' => $event['subject_title'],
                'subject_description' => $event['subject_description'],
                'student_names' => $event['student_names']
            ];
        }

        ksort($groupedPresentations);
        
        require APP_PATH . '/app/views/calendar/index.php';
    }

    public function getEventsByRange() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            exit;
        }

        $startDate = $_GET['start'] ?? date('Y-m-d');
        $endDate = $_GET['end'] ?? date('Y-m-d', strtotime('+30 days'));

        $events = $this->calendarModel->getEventsByDateRange($startDate, $endDate);

        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }
} 