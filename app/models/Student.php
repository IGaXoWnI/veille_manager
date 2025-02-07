<?php
class Student extends User {
    public function getUpcomingPresentations($studentId) {
        $stmt = $this->db->prepare(
            "SELECT p.*, s.title as subject_title, s.description as subject_description
             FROM presentations p
             JOIN student_presentations sp ON p.id = sp.presentation_id
             JOIN subjects s ON p.subject_id = s.id
             WHERE sp.student_id = :student_id
             AND p.scheduled_date >= CURRENT_DATE
             AND p.status = 'scheduled'
             ORDER BY p.scheduled_date ASC"
        );
        
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSuggestedSubjects($studentId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM subjects 
             WHERE suggested_by = :student_id 
             ORDER BY created_at DESC"
        );
        
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentStats($studentId) {
        try {
            $query = "
                SELECT 
                    COUNT(DISTINCT sp.presentation_id) as total_presentations,
                    COUNT(DISTINCT s.id) as total_suggestions,
                    (
                        SELECT scheduled_date 
                        FROM presentations p 
                        JOIN student_presentations sp2 ON p.id = sp2.presentation_id 
                        WHERE sp2.student_id = :student_id 
                        ORDER BY scheduled_date DESC 
                        LIMIT 1
                    ) as last_presentation_date
                FROM student_presentations sp
                LEFT JOIN subjects s ON s.suggested_by = :student_id
                WHERE sp.student_id = :student_id
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['student_id' => $studentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting student stats: " . $e->getMessage());
            return null;
        }
    }

    public function getPastPresentations($studentId) {
        $stmt = $this->db->prepare(
            "SELECT p.*, s.title as subject_title
             FROM presentations p
             JOIN student_presentations sp ON p.id = sp.presentation_id
             JOIN subjects s ON p.subject_id = s.id
             WHERE sp.student_id = :student_id
             AND p.scheduled_date < CURRENT_DATE
             ORDER BY p.scheduled_date DESC"
        );
        
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 