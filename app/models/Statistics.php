<?php
class Statistics {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTeacherDashboardStats() {
        try {
            $todayPresentations = $this->getTodayPresentations();
            
            return [
                'pendingSubjectsCount' => $this->getPendingSubjectsCount(),
                'approvedSubjectsCount' => $this->getApprovedSubjectsCount(),
                'todayPresentationsCount' => count($todayPresentations),
                'upcomingPresentationsCount' => $this->getUpcomingPresentationsCount(),
                'totalPresentationsCount' => $this->getTotalPresentationsCount(),
                'totalStudentsCount' => $this->getActiveStudentsCount(),
                'todayPresentations' => $todayPresentations
            ];
        } catch (PDOException $e) {
            error_log("Error getting teacher dashboard stats: " . $e->getMessage());
            return [
                'pendingSubjectsCount' => 0,
                'approvedSubjectsCount' => 0,
                'todayPresentationsCount' => 0,
                'upcomingPresentationsCount' => 0,
                'totalPresentationsCount' => 0,
                'totalStudentsCount' => 0,
                'todayPresentations' => []
            ];
        }
    }

    public function getStudentDashboardStats($studentId) {
        try {
            return [
                'totalPresentations' => $this->getStudentTotalPresentations($studentId),
                'upcomingPresentations' => $this->getStudentUpcomingPresentations($studentId),
                'completedPresentations' => $this->getStudentCompletedPresentations($studentId),
                'suggestedSubjects' => $this->getStudentSuggestedSubjects($studentId)
            ];
        } catch (PDOException $e) {
            error_log("Error getting student dashboard stats: " . $e->getMessage());
            return null;
        }
    }

    private function getPendingSubjectsCount() {
        $query = "SELECT COUNT(*) as count FROM subjects WHERE status = 'pending'";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getApprovedSubjectsCount() {
        $query = "SELECT COUNT(*) as count FROM subjects WHERE status = 'approved'";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getTodayPresentationsCount() {
        try {
            $query = "
                SELECT COUNT(*) as count 
                FROM presentations 
                WHERE DATE(scheduled_date) = CURRENT_DATE 
                AND status = 'scheduled'::presentation_status
            ";
            
            $stmt = $this->db->query($query);
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            error_log("Error getting today's presentations count: " . $e->getMessage());
            return 0;
        }
    }

    private function getUpcomingPresentationsCount() {
        $query = "SELECT COUNT(*) as count FROM presentations WHERE scheduled_date > CURRENT_DATE";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getTotalPresentationsCount() {
        $query = "SELECT COUNT(*) as count FROM presentations";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getActiveStudentsCount() {
        $query = "SELECT COUNT(*) as count FROM users WHERE role = 'student' AND is_active = true";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getTodayPresentations() {
        try {
            $query = "
                SELECT 
                    p.*,
                    s.title as subject_title,
                    s.description as subject_description,
                    string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ') as student_names
                FROM presentations p
                JOIN subjects s ON p.subject_id = s.id
                LEFT JOIN student_presentations sp ON p.id = sp.presentation_id
                LEFT JOIN users u ON sp.student_id = u.id
                WHERE DATE(p.scheduled_date) = CURRENT_DATE 
                AND p.status = 'scheduled'::presentation_status
                GROUP BY p.id, p.scheduled_date, p.status, s.title, s.description
                ORDER BY p.scheduled_date ASC
            ";
            
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting today's presentations: " . $e->getMessage());
            return [];
        }
    }

    private function getStudentTotalPresentations($studentId) {
        $query = "
            SELECT COUNT(*) as count 
            FROM student_presentations 
            WHERE student_id = :student_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getStudentUpcomingPresentations($studentId) {
        $query = "
            SELECT COUNT(*) as count 
            FROM student_presentations sp
            JOIN presentations p ON sp.presentation_id = p.id
            WHERE sp.student_id = :student_id 
            AND p.scheduled_date > CURRENT_DATE
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getStudentCompletedPresentations($studentId) {
        $query = "
            SELECT COUNT(*) as count 
            FROM student_presentations sp
            JOIN presentations p ON sp.presentation_id = p.id
            WHERE sp.student_id = :student_id 
            AND p.scheduled_date < CURRENT_DATE
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    private function getStudentSuggestedSubjects($studentId) {
        $query = "
            SELECT COUNT(*) as count 
            FROM subjects 
            WHERE suggested_by = :student_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
} 