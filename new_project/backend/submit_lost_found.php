<?php
require_once '../classes/Session.php';
require_once '../classes/LostFound.php';

// Check if user is logged in
Session::start();
if (!Session::isLoggedIn()) {
    header('Location: ../login_page.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $itemName = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $contactInfo = filter_input(INPUT_POST, 'contact_info', FILTER_SANITIZE_STRING);
    $isAnonymous = isset($_POST['is_anonymous']) ? true : false;
    
    // Validate inputs
    if (!$type || !$itemName || !$category || !$description || !$location || !$contactInfo) {
        Session::setFlash('error', 'All fields are required');
        header('Location: ../student/lost_found.php');
        exit();
    }

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/lost_found/';
        
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
            header('Location: ../student/lost_found.php');
            exit();
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = 'uploads/lost_found/' . $fileName;
        } else {
            Session::setFlash('error', 'Failed to upload image');
            header('Location: ../student/lost_found.php');
            exit();
        }
    }

    // Create report
    $lostFound = new LostFound();
    $result = $lostFound->createReport(
        Session::get('id'),
        $type,
        $itemName,
        $category,
        $description,
        $location,
        $imagePath,
        $contactInfo,
        $isAnonymous
    );

    if ($result['success']) {
        Session::setFlash('success', $result['message']);
    } else {
        Session::setFlash('error', $result['message']);
    }

    header('Location: ../student/lost_found.php');
    exit();
} else {
    header('Location: ../student/lost_found.php');
    exit();
}
