<?php
class User {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($userData) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $userData['email']]);
            if ($stmt->fetch()) {
                throw new Exception('Email already exists');
            }

            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

            $stmt = $this->db->prepare(
                "INSERT INTO users (email, password, first_name, last_name, role) 
                 VALUES (:email, :password, :first_name, :last_name, :role)"
            );

            $stmt->execute([
                'email' => $userData['email'],
                'password' => $hashedPassword,
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'role' => $userData['role']
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Registration failed: ' . $e->getMessage());
        }
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllStudents() {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = 'student'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $userData) {
        $sql = "UPDATE users SET ";
        $params = [];
        
        foreach ($userData as $key => $value) {
            if ($key !== 'id') {
                $sql .= "$key = :$key, ";
                $params[$key] = $value;
            }
        }
        $sql = rtrim($sql, ', ');
        $sql .= " WHERE id = :id";
        $params['id'] = $userId;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function login($email, $password) {
        try {
            $user = $this->findByEmail($email);
            
            if (!$user) {
                throw new Exception('Invalid email or password');
            }

            if (!password_verify($password, $user['password'])) {
                throw new Exception('Invalid email or password');
            }

            if (!$user['is_active']) {
                throw new Exception('Account not activated');
            }

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPendingUsers() {
        try {
            $query = "
                SELECT id, first_name, last_name, email, created_at
                FROM users 
                WHERE is_active = false AND role = 'student'
                ORDER BY created_at DESC
            ";
            
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching pending users: " . $e->getMessage());
            return [];
        }
    }

    public function getActiveUsers() {
        try {
            $query = "
                SELECT id, first_name, last_name, email, created_at
                FROM users 
                WHERE is_active = true AND role = 'student'
                ORDER BY last_name, first_name
            ";
            
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching active users: " . $e->getMessage());
            return [];
        }
    }

    public function approveUser($userId) {
        try {
            $query = "
                UPDATE users 
                SET is_active = true, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id
            ";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['id' => $userId]);
        } catch (PDOException $e) {
            error_log("Error approving user: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($userId) {
        try {
            $query = "DELETE FROM users WHERE id = :id AND role = 'student'";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['id' => $userId]);
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    public function getActiveStudentsCount() {
        try {
            $query = "
                SELECT COUNT(*) as count 
                FROM users 
                WHERE role = 'student' 
                AND is_active = 't'
            ";
            
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error getting active students count: " . $e->getMessage());
            return 0;
        }
    }
}