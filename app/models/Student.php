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
} 