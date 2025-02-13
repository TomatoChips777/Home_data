<?php
require_once '../classes/Report.php';
require_once '../classes/Session.php';

// Check if user is admin
Session::start();
if (!Session::isLoggedIn() || Session::get('role') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = filter_input(INPUT_POST, 'report_id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    // Validate inputs
    if (!$reportId || !in_array($status, ['pending', 'in_progress', 'resolved'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit();
    }

    // Update report status
    $report = new Report();
    $success = $report->updateStatus($reportId, $status);

    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Status updated successfully' : 'Failed to update status'
    ]);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
