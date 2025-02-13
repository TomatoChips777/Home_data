<?php
require_once '../classes/Session.php';
require_once '../classes/LostFound.php';

// Check if user is logged in and is admin
Session::start();
if (!Session::isLoggedIn() || Session::get('role') !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = filter_input(INPUT_POST, 'report_id', FILTER_VALIDATE_INT);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $itemName = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $contactInfo = filter_input(INPUT_POST, 'contact_info', FILTER_SANITIZE_STRING);
    
    // Validate inputs
    if (!$reportId || !$type || !$itemName || !$category || !$description || !$location || !$status || !$contactInfo) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }
    
    // Update report
    $lostFound = new LostFound();
    $result = $lostFound->updateReport($reportId, $type, $itemName, $category, $description, $location, $status, $contactInfo);
    
    echo json_encode($result);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle delete request
    parse_str(file_get_contents("php://input"), $deleteParams);
    $reportId = filter_var($deleteParams['report_id'] ?? null, FILTER_VALIDATE_INT);
    
    if (!$reportId) {
        echo json_encode(['success' => false, 'message' => 'Report ID is required']);
        exit();
    }
    
    $lostFound = new LostFound();
    $result = $lostFound->deleteReport($reportId);
    
    echo json_encode($result);
    exit();
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}
?>
