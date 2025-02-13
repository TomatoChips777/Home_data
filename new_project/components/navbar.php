<?php
require_once dirname(__DIR__) . '/classes/Session.php';
Session::start();
?>
<nav class="navbar navbar-expand-lg bg-success navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/new_project/index.php">Campus SOS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <?php if (Session::get('role') === 'admin'): ?>
                        <a class="nav-link" href="/new_project/admin/dashboard.php">Dashboard</a>
                    <?php else: ?>
                        <a class="nav-link" href="/new_project/student/dashboard.php">Dashboard</a>
                    <?php endif; ?>
                </li>
                <!-- <li class="nav-item">
                    <?php if (Session::get('role') === 'admin'): ?>
                        <a class="nav-link" href="/new_project/admin/manage_lost_found.php">Manage Lost & Found</a>
                    <?php endif; ?>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="/new_project/student/lost_found.php">Lost & Found</a>
                </li>
            </ul>
            <?php if (Session::isLoggedIn()): ?>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">
                        Welcome, <?php echo htmlspecialchars(Session::get('name')); ?>
                    </span>
                    <a href="/new_project/backend/logout.php" class="btn btn-light">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>