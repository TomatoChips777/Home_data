<?php
require_once 'classes/Session.php';

// Redirect if already logged in
if (Session::isLoggedIn()) {
    if (Session::get('role') === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: student/dashboard.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('./components/head.php'); ?>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-success text-white text-center py-3">
                        <h3 class="mb-0">Login</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php if($error = Session::getFlash('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if($success = Session::getFlash('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="./backend/login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" required 
                                           placeholder="Enter your email">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" required 
                                           placeholder="Enter your password">
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3 bg-light">
                        <div class="small mb-2">
                            <a href="registration_page.php" class="text-decoration-none">Need an account? Sign up!</a>
                        </div>
                        <div class="small">
                            <a href="#" class="text-decoration-none">Forgot password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="./assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>