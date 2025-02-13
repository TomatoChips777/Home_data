<?php
require_once '../classes/Session.php';
require_once '../classes/Report.php';

// Ensure only students can access this page
Session::start();
if (!Session::isLoggedIn() || Session::get('role') !== 'student') {
    header('Location: ../login_page.php');
    exit();
}

// Get user's reports
$report = new Report();
$userReports = $report->getReportsByUser(Session::get('id'));
?>
<!DOCTYPE html>
<html lang="en">
<?php include('../components/head.php'); ?>

<body class="bg-light">
    <?php include('../components/navbar.php'); ?>

    <div class="container py-5">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="bi bi-exclamation-triangle-fill display-4"></i>
                            </div>
                            <div class="col">
                                <h2 class="mb-0">Campus SOS Reporting System</h2>
                                <p class="mb-0">Report maintenance issues and campus concerns</p>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#newReportModal">
                                    <i class="bi bi-plus-circle me-2"></i>New Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-clock-history text-warning fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">Pending Reports</h6>
                                <h3 class="mb-0"><?php echo count(array_filter($userReports, function ($r) {
                                                        return $r['status'] === 'pending';
                                                    })); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-tools text-primary fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">In Progress</h6>
                                <h3 class="mb-0"><?php echo count(array_filter($userReports, function ($r) {
                                                        return $r['status'] === 'in_progress';
                                                    })); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">Resolved</h6>
                                <h3 class="mb-0"><?php echo count(array_filter($userReports, function ($r) {
                                                        return $r['status'] === 'resolved';
                                                    })); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">My Reports</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Issue Type</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($userReports)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        No reports submitted yet
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($userReports as $report): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($report['created_at'])); ?></td>
                                        <td><?php echo htmlspecialchars($report['location']); ?></td>
                                        <td><?php echo htmlspecialchars($report['issue_type']); ?></td>
                                        <td><?php echo htmlspecialchars($report['description']); ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'in_progress' => 'primary',
                                                'resolved' => 'success'
                                            ][$report['status']];
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $report['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($report['image_path']): ?>
                                                <a href="" data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $report['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-image"></i> View
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="imageModal<?php echo $report['id']; ?>" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="imageModalLabel"><?php echo htmlspecialchars($report['description']); ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <img src="../<?php echo htmlspecialchars($report['image_path']); ?>" class="card-img-top" alt="...">
                        
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Report Modal -->
    <div class="modal fade" id="newReportModal" tabindex="-1" aria-labelledby="newReportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newReportModalLabel">Submit New Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../backend/submit_report.php" method="POST" enctype="multipart/form-data" id="reportForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" required placeholder="e.g., Building A, Room 101">
                        </div>
                        <div class="mb-3">
                            <label for="issue_type" class="form-label">Issue Type</label>
                            <select class="form-select" id="issue_type" name="issue_type" required>
                                <option value="">Select issue type</option>
                                <option value="plumbing">Plumbing Issue</option>
                                <option value="electrical">Electrical Problem</option>
                                <option value="structural">Structural Damage</option>
                                <option value="cleaning">Cleaning Required</option>
                                <option value="safety">Safety Concern</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Please provide detailed description of the issue..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Optional: You can upload an image of the issue</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
        <script type="text/javascript" src="../assets/js/bootstrap.bundle.min.js"></script>

    
  
</body>

</html>