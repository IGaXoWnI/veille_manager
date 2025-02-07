<?php
class Subject {
    private $db;
    private $id;
    private $title;
    private $description;
    private $suggested_by;
    private $status;
    private $created_at;
    private $updated_at;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getSubjectsByStudent($studentId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM subjects 
             WHERE suggested_by = :student_id 
             ORDER BY created_at DESC"
        );
        
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingSubjects() {
        try {
            $query = "
                SELECT s.*, u.first_name, u.last_name 
                FROM subjects s
                JOIN users u ON s.suggested_by = u.id
                WHERE s.status = 'pending'
                ORDER BY s.created_at DESC
            ";
            
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting pending subjects: " . $e->getMessage());
            return [];
        }
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO subjects (title, description, suggested_by) 
             VALUES (:title, :description, :suggested_by)
             RETURNING id"
        );
        
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'suggested_by' => $data['suggested_by']
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }

    public function updateStatus($subjectId, $status) {
        $stmt = $this->db->prepare(
            "UPDATE subjects 
             SET status = :status::subject_status 
             WHERE id = :id"
        );
        
        return $stmt->execute([
            'id' => $subjectId,
            'status' => $status
        ]);
    }

    public function getAllSubjects() {
        $stmt = $this->db->prepare(
            "SELECT s.*, u.first_name, u.last_name 
             FROM subjects s 
             JOIN users u ON s.suggested_by = u.id 
             ORDER BY s.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApprovedSubjects() {
        $query = "
            SELECT 
                s.id,
                s.title,
                s.description,
                s.status,
                u.first_name || ' ' || u.last_name as suggested_by_name,
                s.created_at
            FROM subjects s
            LEFT JOIN users u ON s.suggested_by = u.id
            WHERE s.status = 'approved'
            ORDER BY s.created_at DESC
        ";

        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching approved subjects: " . $e->getMessage());
            return [];
        }
    }

    public function getSubjectById($id) {
        $query = "
            SELECT 
                s.*,
                u.first_name || ' ' || u.last_name as suggested_by_name
            FROM subjects s
            LEFT JOIN users u ON s.suggested_by = u.id
            WHERE s.id = :id
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching subject by ID: " . $e->getMessage());
            return null;
        }
    }

    public function createSubject($data) {
        $query = "
            INSERT INTO subjects (title, description, suggested_by, status)
            VALUES (:title, :description, :suggested_by, :status)
            RETURNING id
        ";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'suggested_by' => $data['suggested_by'],
                'status' => $data['status'] ?? 'pending'
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating subject: " . $e->getMessage());
            return false;
        }
    }

    public function updateSubject($id, $data) {
        $query = "
            UPDATE subjects 
            SET title = :title,
                description = :description,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ";

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'id' => $id,
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status']
            ]);
        } catch (PDOException $e) {
            error_log("Error updating subject: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSubject($id) {
        $query = "DELETE FROM subjects WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting subject: " . $e->getMessage());
            return false;
        }
    }

    public function getApprovedSubjectsCount() {
        try {
            $query = "SELECT COUNT(*) as count FROM subjects WHERE status = 'approved'";
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error getting approved subjects count: " . $e->getMessage());
            return 0;
        }
    }
} 