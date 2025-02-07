<?php
class NotificationController {
    private $notificationModel;
    
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->notificationModel = new Notification();
    }
    
    public function index() {
        $notifications = $this->notificationModel->getNotificationsForUser($_SESSION['user_id']);
        require APP_PATH . '/app/views/notifications/index.php';
    }
    
    public function markAsRead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/notifications');
            exit;
        }
        
        $notificationId = $_POST['notification_id'] ?? null;
        if ($notificationId) {
            $this->notificationModel->markAsRead($notificationId);
        }
        
        header('Location: ' . BASE_URL . '/notifications');
        exit;
    }
} 