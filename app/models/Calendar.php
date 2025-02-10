<?php
class Calendar {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUpcomingEvents() {
        try {
            $query = "
                SELECT 
                    p.id,
                    p.scheduled_date,
                    p.status,
                    s.title as subject_title,
                    s.description as subject_description,
                    string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ') as student_names
                FROM presentations p
                JOIN subjects s ON p.subject_id = s.id
                LEFT JOIN student_presentations sp ON p.id = sp.presentation_id
                LEFT JOIN users u ON sp.student_id = u.id
                WHERE p.scheduled_date >= CURRENT_DATE 
                AND p.status = 'scheduled'::presentation_status
                GROUP BY p.id, p.scheduled_date, p.status, s.title, s.description
                ORDER BY p.scheduled_date ASC
            ";

            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching calendar events: " . $e->getMessage());
            return [];
        }
    }

    public function getEventsByDateRange($startDate, $endDate) {
        try {
            $query = "
                SELECT 
                    p.id,
                    p.scheduled_date,
                    s.title as subject_title,
                    s.description as subject_description,
                    string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ') as presenters
                FROM presentations p
                JOIN subjects s ON p.subject_id = s.id
                LEFT JOIN student_presentations sp ON p.id = sp.presentation_id
                LEFT JOIN users u ON sp.student_id = u.id
                WHERE p.scheduled_date BETWEEN :start_date AND :end_date
                AND p.status = 'scheduled'::presentation_status
                GROUP BY p.id, p.scheduled_date, s.title, s.description
                ORDER BY p.scheduled_date ASC
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching events by date range: " . $e->getMessage());
            return [];
        }
    }

    public function getEventsByDate($date) {
        try {
            $query = "
                SELECT 
                    p.id,
                    p.scheduled_date,
                    s.title as subject_title,
                    s.description as subject_description,
                    string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ') as presenters
                FROM presentations p
                JOIN subjects s ON p.subject_id = s.id
                LEFT JOIN student_presentations sp ON p.id = sp.presentation_id
                LEFT JOIN users u ON sp.student_id = u.id
                WHERE DATE(p.scheduled_date) = :date
                AND p.status = 'scheduled'::presentation_status
                GROUP BY p.id, p.scheduled_date, s.title, s.description
                ORDER BY p.scheduled_date ASC
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute(['date' => $date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching events by date: " . $e->getMessage());
            return [];
        }
    }
} 