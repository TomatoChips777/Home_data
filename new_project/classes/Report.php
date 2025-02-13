<?php
require_once 'Database.php';
require_once 'Session.php';

class Report {
    private $db;
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Database connection failed");
        }
    }

    public function createReport($location, $issueType, $description, $imagePath = null) {
        try {
            if (!Session::isLoggedIn()) {
                return [
                    'success' => false,
                    'message' => 'User not logged in'
                ];
            }

            $userId = Session::get('id');
            if (!$userId) {
                return [
                    'success' => false,
                    'message' => 'User ID not found in session'
                ];
            }

            // Validate inputs
            if (empty($location) || empty($issueType) || empty($description)) {
                return [
                    'success' => false,
                    'message' => 'All required fields must be filled'
                ];
            }

            $stmt = $this->conn->prepare("INSERT INTO reports (user_id, location, issue_type, description, image_path) VALUES (?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                error_log("Prepare statement failed: " . $this->conn->error);
                return [
                    'success' => false,
                    'message' => 'Database error occurred'
                ];
            }

            $result = $stmt->execute([$userId, $location, $issueType, $description, $imagePath]);
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Report submitted successfully',
                    'report_id' => $this->conn->lastInsertId()
                ];
            } else {
                error_log("Execute failed: " . $stmt->errorInfo()[2]);
                return [
                    'success' => false,
                    'message' => 'Failed to save report'
                ];
            }
        } catch (PDOException $e) {
            error_log("Report creation error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            error_log("General error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }

    public function getReportsByUser($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM reports WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get reports error: " . $e->getMessage());
            return [];
        }
    }

    public function getAllReports() {
        try {
            $stmt = $this->conn->prepare("
                SELECT r.*, u.name as reporter_name 
                FROM reports r 
                JOIN users u ON r.user_id = u.id 
                ORDER BY r.created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get all reports error: " . $e->getMessage());
            return [];
        }
    }

    public function updateStatus($reportId, $status) {
        try {
            if (!in_array($status, ['pending', 'in_progress', 'resolved'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid status'
                ];
            }

            $stmt = $this->conn->prepare("UPDATE reports SET status = ? WHERE id = ?");
            $result = $stmt->execute([$status, $reportId]);
            
            return [
                'success' => $result,
                'message' => $result ? 'Status updated successfully' : 'Failed to update status'
            ];
        } catch (PDOException $e) {
            error_log("Update status error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error occurred'
            ];
        }
    }
}
