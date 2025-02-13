<?php
require_once '../classes/User.php';
require_once '../classes/Session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        Session::setFlash('error', 'All fields are required');
        header('Location: ../login_page.php');
        exit();
    }

    $user = new User();
    if ($user->login($email, $password)) {
        // Start session and get role
        Session::start();
        $role = Session::get('role');
        
        // Redirect based on role
        if ($role === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../student/dashboard.php');
        }
        exit();
    } else {
        Session::setFlash('error', 'Invalid email or password');
        header('Location: ../login_page.php');
        exit();
    }
} else {
    header('Location: ../login_page.php');
    exit();
}
?>