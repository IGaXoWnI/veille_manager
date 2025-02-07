<?php
class CalendarController {
    private $presentationModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->presentationModel = new Presentation();
    }

    public function index() {
        $presentations = $this->presentationModel->getUpcomingPresentations();
        
        $groupedPresentations = [];
        foreach ($presentations as $presentation) {
            $date = date('Y-m-d', strtotime($presentation['scheduled_date']));
            if (!isset($groupedPresentations[$date])) {
                $groupedPresentations[$date] = [];
            }
            $groupedPresentations[$date][] = $presentation;
        }

        ksort($groupedPresentations);

        require APP_PATH . '/app/views/calendar/index.php';
    }
} 