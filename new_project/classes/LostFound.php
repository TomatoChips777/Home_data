<?php
require_once 'Database.php';

class LostFound {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->getConnection();
    }

    public function createReport($userId, $type, $itemName, $category, $description, $location, $imagePath = null, $contactInfo, $isAnonymous = false) {
        $sql = "INSERT INTO lost_found (user_id, type, item_name, category, description, location, image_path, contact_info, is_anonymous) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $type, $itemName, $category, $description, $location, $imagePath, $contactInfo, $isAnonymous]);
            return ['success' => true, 'message' => 'Report created successfully'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Failed to create report: ' . $e->getMessage()];
        }
    }

    public function updateReport($reportId, $type, $itemName, $category, $description, $location, $status, $contactInfo) {
        $sql = "UPDATE lost_found 
                SET type = ?, 
                    item_name = ?, 
                    category = ?, 
                    description = ?, 
                    location = ?, 
                    status = ?, 
                    contact_info = ?
                WHERE id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$type, $itemName, $category, $description, $location, $status, $contactInfo, $reportId]);
            return ['success' => true, 'message' => 'Report updated successfully'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Failed to update report: ' . $e->getMessage()];
        }
    }

    public function updateStatus($reportId, $status) {
        $sql = "UPDATE lost_found SET status = ? WHERE id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$status, $reportId]);
            return ['success' => true, 'message' => 'Status updated successfully'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Failed to update status: ' . $e->getMessage()];
        }
    }

    public function deleteReport($reportId) {
        $sql = "DELETE FROM lost_found WHERE id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$reportId]);
            return ['success' => true, 'message' => 'Report deleted successfully'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Failed to delete report: ' . $e->getMessage()];
        }
    }

    public function getReportsByUser($userId) {
        $sql = "SELECT lf.*, u.name as reporter_name 
                FROM lost_found lf 
                JOIN users u ON lf.user_id = u.id 
                WHERE lf.user_id = ? 
                ORDER BY lf.date_reported DESC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getAllReports() {
        $sql = "SELECT 
                    lf.*,
                    CASE 
                        WHEN lf.is_anonymous = 1 THEN 'Anonymous User'
                        ELSE u.name 
                    END as reporter_name
                FROM lost_found lf 
                JOIN users u ON lf.user_id = u.id 
                ORDER BY lf.date_reported DESC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getReportById($reportId) {
        $sql = "SELECT 
                    lf.*,
                    CASE 
                        WHEN lf.is_anonymous = 1 THEN 'Anonymous User'
                        ELSE u.name 
                    END as reporter_name
                FROM lost_found lf 
                JOIN users u ON lf.user_id = u.id 
                WHERE lf.id = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$reportId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function searchReports($keyword) {
        $sql = "SELECT 
                    lf.*,
                    CASE 
                        WHEN lf.is_anonymous = 1 THEN 'Anonymous User'
                        ELSE u.name 
                    END as reporter_name
                FROM lost_found lf 
                JOIN users u ON lf.user_id = u.id 
                WHERE lf.item_name LIKE ? 
                OR lf.description LIKE ? 
                OR lf.location LIKE ? 
                ORDER BY lf.date_reported DESC";
        
        $keyword = "%$keyword%";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$keyword, $keyword, $keyword]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getPaginatedReports($page = 1, $itemsPerPage = 10, $type = 'all', $status = 'all', $sortField = 'date_reported', $sortOrder = 'DESC') {
        // Build the base query
        $sql = "SELECT lf.*, 
                CASE 
                    WHEN lf.is_anonymous = 1 THEN 'Anonymous User'
                    ELSE u.name 
                END as reporter_name
                FROM lost_found lf
                JOIN users u ON lf.user_id = u.id
                WHERE 1=1";
        
        $params = [];

        // Add type filter
        if ($type !== 'all') {
            $sql .= " AND lf.type = ?";
            $params[] = $type;
        }

        // Add status filter
        if ($status !== 'all') {
            $sql .= " AND lf.status = ?";
            $params[] = $status;
        }

        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM lost_found lf WHERE 1=1";
        if ($type !== 'all') {
            $countSql .= " AND lf.type = ?";
        }
        if ($status !== 'all') {
            $countSql .= " AND lf.status = ?";
        }
        $totalItems = $this->pdo->prepare($countSql);
        $totalItems->execute($params);
        $totalItems = $totalItems->fetch(PDO::FETCH_ASSOC)['total'];

        // Add sorting
        $allowedSortFields = ['date_reported', 'type', 'item_name', 'category', 'location', 'status'];
        $sortField = in_array($sortField, $allowedSortFields) ? $sortField : 'date_reported';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        
        $sql .= " ORDER BY lf.$sortField $sortOrder";

        // Add pagination
        $offset = ($page - 1) * $itemsPerPage;
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $itemsPerPage;
        $params[] = $offset;

        // Get paginated results
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'items' => $items,
            'total' => $totalItems,
            'page' => $page,
            'itemsPerPage' => $itemsPerPage,
            'totalPages' => ceil($totalItems / $itemsPerPage)
        ];
    }

    public function getReportCounts() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN type = 'lost' THEN 1 ELSE 0 END) as lost_count,
                    SUM(CASE WHEN type = 'found' THEN 1 ELSE 0 END) as found_count,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved_count,
                    SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_count
                FROM lost_found";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                'total' => 0,
                'lost_count' => 0,
                'found_count' => 0,
                'pending_count' => 0,
                'resolved_count' => 0,
                'closed_count' => 0
            ];
        }
    }
}
