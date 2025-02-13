<?php
require_once '../classes/User.php';
require_once '../classes/Session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (!$name || !$email || !$password || !$confirm_password) {
        Session::setFlash('error', 'All fields are required');
        header('Location: ../registration_page.php');
        exit();
    }

    if ($password !== $confirm_password) {
        Session::setFlash('error', 'Passwords do not match');
        header('Location: ../registration_page.php');
        exit();
    }

    if (strlen($password) < 6) {
        Session::setFlash('error', 'Password must be at least 6 characters long');
        header('Location: ../registration_page.php');
        exit();
    }

    $user = new User();
    $result = $user->register($name, $email, $password);

    if ($result['success']) {
        Session::setFlash('success', $result['message']);
        header('Location: ../login_page.php');
    } else {
        Session::setFlash('error', $result['message']);
        header('Location: ../registration_page.php');
    }
    exit();
} else {
    header('Location: ../registration_page.php');
    exit();
}
