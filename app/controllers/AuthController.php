<?php
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        require_once APP_PATH . '/app/views/auth/login.php';
    }

    public function showRegister() {
        require_once APP_PATH . '/app/views/auth/register.php';
    }

    public function login() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new Exception('Email and password are required');
            }

            $userModel = new User();
            $user = $userModel->getUserByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception('Invalid email or password');
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];

            if ($user['role'] === 'teacher') {
                header('Location: ' . BASE_URL . '/dashboard/teacher');
            } else {
                header('Location: ' . BASE_URL . '/dashboard/student');
            }
            exit;

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function register() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . '/register');
                exit;
            }

            $required = ['email', 'password', 'first_name', 'last_name', 'role'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("$field is required");
                }
            }

            $userId = $this->userModel->register([
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'role' => $_POST['role']
            ]);
            
            $_SESSION['success_message'] = 'Registration successful. Please wait for account activation.';
            header('Location: ' . BASE_URL . '/login');
            exit;

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/register');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}