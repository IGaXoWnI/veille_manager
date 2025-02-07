<?php
class UserManagementController {
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->userModel = new User();
    }

    public function index() {
        $pendingUsers = $this->userModel->getPendingUsers();
        $activeUsers = $this->userModel->getActiveUsers();
        require APP_PATH . '/app/views/user-management/index.php';
    }

    public function approveUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
            header('Location: ' . BASE_URL . '/user-management');
            exit;
        }

        $userId = $_POST['user_id'];
        if ($this->userModel->approveUser($userId)) {
            $_SESSION['success'] = 'User approved successfully';
        } else {
            $_SESSION['error'] = 'Failed to approve user';
        }

        header('Location: ' . BASE_URL . '/user-management');
        exit;
    }

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
            header('Location: ' . BASE_URL . '/user-management');
            exit;
        }

        $userId = $_POST['user_id'];
        if ($this->userModel->deleteUser($userId)) {
            $_SESSION['success'] = 'User deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete user';
        }

        header('Location: ' . BASE_URL . '/user-management');
        exit;
    }
} 