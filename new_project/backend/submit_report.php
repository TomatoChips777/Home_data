<?php
require_once '../classes/Report.php';
require_once '../classes/Session.php';

// Check if user is logged in
Session::start();
if (!Session::isLoggedIn()) {
    header('Location: ../login_page.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $issueType = filter_input(INPUT_POST, 'issue_type', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    
    // Validate inputs
    if (!$location || !$issueType || !$description) {
        Session::setFlash('error', 'All fields are required');
        header('Location: ../student/dashboard.php');
        exit();
    }

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/reports/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        // Check if it's a valid image
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);
        if ($imageInfo === false) {
            Session::setFlash('error', 'Invalid image file');
            header('Location: ../student/dashboard.php');
            exit();
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = 'uploads/reports/' . $fileName;
        } else {
            Session::setFlash('error', 'Failed to upload image');
            header('Location: ../student/dashboard.php');
            exit();
        }
    }

    // Create report
    $report = new Report();
    $result = $report->createReport($location, $issueType, $description, $imagePath);

    if ($result['success']) {
        Session::setFlash('success', $result['message']);
    } else {
        Session::setFlash('error', $result['message']);
    }

    header('Location: ../student/dashboard.php');
    exit();
} else {
    header('Location: ../student/dashboard.php');
    exit();
}
