<?php
require_once '../classes/Session.php';
require_once '../classes/Report.php';

// Ensure only admin can access this page
Session::requireAdmin();

// Get all reports
$report = new Report();
$allReports = $report->getAllReports();

// Count reports by status
$pendingCount = count(array_filter($allReports, function($r) { return $r['status'] === 'pending'; }));
$inProgressCount = count(array_filter($allReports, function($r) { return $r['status'] === 'in_progress'; }));
$resolvedCount = count(array_filter($allReports, function($r) { return $r['status'] === 'resolved'; }));
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
                                <i class="bi bi-tools display-4"></i>
                            </div>
                            <div class="col">
                                <h2 class="mb-0">Maintenance Reports Dashboard</h2>
                                <p class="mb-0">Manage and respond to campus maintenance reports</p>
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
                                    <i class="bi bi-exclamation-circle text-warning fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">Pending Reports</h6>
                                <h3 class="mb-0"><?php echo $pendingCount; ?></h3>
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
                                    <i class="bi bi-gear text-primary fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">In Progress</h6>
                                <h3 class="mb-0"><?php echo $inProgressCount; ?></h3>
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
                                <h3 class="mb-0"><?php echo $resolvedCount; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">All Reports</h5>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-success active" data-filter="all">All</button>
                            <button type="button" class="btn btn-outline-warning" data-filter="pending">Pending</button>
                            <button type="button" class="btn btn-outline-primary" data-filter="in_progress">In Progress</button>
                            <button type="button" class="btn btn-outline-success" data-filter="resolved">Resolved</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reporter</th>
                                <th>Location</th>
                                <th>Issue Type</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($allReports)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                    No reports submitted yet
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($allReports as $report): ?>
                                <tr data-status="<?php echo $report['status']; ?>">
                                    <td><?php echo date('M d, Y', strtotime($report['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($report['reporter_name']); ?></td>
                                    <td><?php echo htmlspecialchars($report['location']); ?></td>
                                    <td><?php echo htmlspecialchars($report['issue_type']); ?></td>
                                    <td><?php echo htmlspecialchars($report['description']); ?></td>
                                    <td>
                                        <select class="form-select form-select-sm status-select" 
                                                data-report-id="<?php echo $report['id']; ?>"
                                                style="width: 130px;">
                                            <option value="pending" <?php echo $report['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="in_progress" <?php echo $report['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="resolved" <?php echo $report['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                        </select>
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
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewReportModal"
                                                data-report-id="<?php echo $report['id']; ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
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

    <!-- View Report Modal -->
    <div class="modal fade" id="viewReportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Report details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status change handler
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const reportId = this.dataset.reportId;
                const newStatus = this.value;
                
                fetch('../backend/update_report_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `report_id=${reportId}&status=${newStatus}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update row status
                        this.closest('tr').dataset.status = newStatus;
                    } else {
                        alert('Failed to update status');
                    }
                });
            });
        });

        // Filter buttons
        document.querySelectorAll('[data-filter]').forEach(button => {
            button.addEventListener('click', function() {
                // Update active state
                document.querySelectorAll('[data-filter]').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                // Filter rows
                const filter = this.dataset.filter;
                document.querySelectorAll('tbody tr[data-status]').forEach(row => {
                    if (filter === 'all' || row.dataset.status === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
    </script>
</body>
</html>
