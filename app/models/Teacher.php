<?php
class Teacher extends User {
    public function __construct() {
        parent::__construct();
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
} 