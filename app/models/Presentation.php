<?php
class Presentation {
    private $db;
    private $id;
    private $subject_id;
    private $scheduled_date;
    private $status;
    private $created_at;
    private $updated_at;
    private $notes;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUpcomingPresentationsForStudent($studentId) {
        $stmt = $this->db->prepare(
            "SELECT 
                p.*,
                s.title as subject_title,
                s.description as subject_description
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

    public function getUpcomingPresentations() {
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
                GROUP BY p.id, p.scheduled_date, p.status, s.title, s.description
                ORDER BY p.scheduled_date ASC
            ";

            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching upcoming presentations: " . $e->getMessage());
            return [];
        }
    }

    public function createPresentation($data) {
        try {
            $this->db->beginTransaction();

            $query = "
                INSERT INTO presentations (subject_id, scheduled_date, status)
                VALUES (:subject_id, :scheduled_date, 'scheduled')
                RETURNING id
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'subject_id' => $data['subject_id'],
                'scheduled_date' => $data['scheduled_date']
            ]);

            $presentation_id = $stmt->fetchColumn();

            $this->db->commit();

            return $presentation_id;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error creating presentation: " . $e->getMessage());
            return false;
        }
    }

    public function assignStudentToPresentation($student_id, $presentation_id) {
        try {
            $query = "
                INSERT INTO student_presentations (student_id, presentation_id)
                VALUES (:student_id, :presentation_id)
            ";

            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'student_id' => $student_id,
                'presentation_id' => $presentation_id
            ]);
        } catch (PDOException $e) {
            error_log("Error assigning student to presentation: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($presentationId, $status) {
        $stmt = $this->db->prepare(
            "UPDATE presentations 
             SET status = :status::presentation_status 
             WHERE id = :id"
        );
        
        return $stmt->execute([
            'id' => $presentationId,
            'status' => $status
        ]);
    }

    public function getPresentationsByDate($date) {
        $stmt = $this->db->prepare(
            "SELECT 
                p.*,
                s.title as subject_title,
                string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ') as presenters
            FROM presentations p
            JOIN subjects s ON p.subject_id = s.id
            JOIN student_presentations sp ON p.id = sp.presentation_id
            JOIN users u ON sp.student_id = u.id
            WHERE DATE(p.scheduled_date) = :date
            AND p.status = 'scheduled'
            GROUP BY p.id, s.title
            ORDER BY p.scheduled_date ASC"
        );
        
        $stmt->execute(['date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPresentationsByDateRange($start, $end) {
        $stmt = $this->db->prepare(
            "SELECT 
                p.*,
                s.title as subject_title,
                string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ') as presenters
            FROM presentations p
            JOIN subjects s ON p.subject_id = s.id
            JOIN student_presentations sp ON p.id = sp.presentation_id
            JOIN users u ON sp.student_id = u.id
            WHERE p.scheduled_date BETWEEN :start AND :end
            AND p.status = 'scheduled'
            GROUP BY p.id, s.title
            ORDER BY p.scheduled_date ASC"
        );
        
        $stmt->execute([
            'start' => $start,
            'end' => $end
        ]);
        
        $presentations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($p) {
            return [
                'id' => $p['id'],
                'title' => $p['subject_title'],
                'start' => $p['scheduled_date'],
                'description' => "Presenters: " . $p['presenters']
            ];
        }, $presentations);
    }

    public function getPresentationById($id) {
        try {
            $query = "
                SELECT 
                    p.*,
                    s.title as subject_title,
                    s.description as subject_description,
                    array_agg(u.id) as student_ids,
                    array_agg(CONCAT(u.first_name, ' ', u.last_name)) as student_names
                FROM presentations p
                JOIN subjects s ON p.subject_id = s.id
                LEFT JOIN student_presentations sp ON p.id = sp.presentation_id
                LEFT JOIN users u ON sp.student_id = u.id
                WHERE p.id = :id
                GROUP BY p.id, s.title, s.description
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching presentation by ID: " . $e->getMessage());
            return null;
        }
    }

    public function updatePresentation($id, $data) {
        try {
            $this->db->beginTransaction();

            $query = "
                UPDATE presentations 
                SET subject_id = :subject_id,
                    scheduled_date = :scheduled_date,
                    status = :status,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'id' => $id,
                'subject_id' => $data['subject_id'],
                'scheduled_date' => $data['scheduled_date'],
                'status' => $data['status'] ?? 'scheduled'
            ]);

            if (isset($data['student_ids'])) {
                $this->db->prepare("DELETE FROM student_presentations WHERE presentation_id = ?")->execute([$id]);

                foreach ($data['student_ids'] as $student_id) {
                    $this->assignStudentToPresentation($student_id, $id);
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error updating presentation: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUpcomingPresentations() {
        try {
            $query = "
                SELECT 
                    p.id,
                    p.scheduled_date,
                    p.status,
                    s.title as subject_title,
                    string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ') as student_names
                FROM presentations p
                JOIN subjects s ON p.subject_id = s.id
                LEFT JOIN student_presentations sp ON p.id = sp.presentation_id
                LEFT JOIN users u ON sp.student_id = u.id
                WHERE p.scheduled_date >= CURRENT_DATE
                GROUP BY p.id, p.scheduled_date, p.status, s.title
                ORDER BY p.scheduled_date ASC
            ";

            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching presentations: " . $e->getMessage());
            return [];
        }
    }

    public function getPresentationStudents($presentation_id) {
        try {
            $query = "
                SELECT student_id
                FROM student_presentations
                WHERE presentation_id = :presentation_id
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['presentation_id' => $presentation_id]);
            return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'student_id');
        } catch (PDOException $e) {
            error_log("Error fetching presentation students: " . $e->getMessage());
            return [];
        }
    }

    public function deletePresentation($id) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("DELETE FROM student_presentations WHERE presentation_id = :id");
            $stmt->execute(['id' => $id]);
            
                $stmt = $this->db->prepare("DELETE FROM presentations WHERE id = :id");
            $result = $stmt->execute(['id' => $id]);
            
            $this->db->commit();
            return $result;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error deleting presentation: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalPresentationsCount() {
        try {
            $query = "SELECT COUNT(*) as count FROM presentations";
            
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error getting total presentations count: " . $e->getMessage());
            return 0;
        }
    }

    public function getUpcomingPresentationsCount() {
        try {
            $query = "
                SELECT COUNT(*) as count 
                FROM presentations 
                WHERE scheduled_date > CURRENT_DATE
            ";
            
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error getting upcoming presentations count: " . $e->getMessage());
            return 0;
        }
    }

    public function getTodayPresentationsCount() {
        try {
            $query = "
                SELECT COUNT(*) as count 
                FROM presentations 
                WHERE DATE(scheduled_date) = CURRENT_DATE
            ";
            
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error getting today's presentations count: " . $e->getMessage());
            return 0;
        }
    }
} 