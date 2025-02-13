<?php
require_once '../classes/Session.php';
require_once '../classes/LostFound.php';

Session::start();
if (!Session::isLoggedIn()) {
    header('Location: ../login_page.php');
    exit();
}

$lostFound = new LostFound();
$userId = Session::get('user_id');
$userReports = $lostFound->getReportsByUser($userId);

// Get reports for display
$searchKeyword = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
$reports = $searchKeyword ? $lostFound->searchReports($searchKeyword) : $lostFound->getAllReports();

// Count statistics
$lostCount = count(array_filter($reports, function ($r) {
    return $r['type'] === 'lost';
}));
$foundCount = count(array_filter($reports, function ($r) {
    return $r['type'] === 'found';
}));
$claimedCount = count(array_filter($reports, function ($r) {
    return $r['status'] === 'claimed';
}));
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
                <div class="card bg-primary text-white">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="bi bi-search display-4"></i>
                            </div>
                            <div class="col">
                                <h2 class="mb-0">Lost & Found</h2>
                                <p class="mb-0">Report lost items or help others find their belongings</p>
                            </div>
                            <div class="col-auto">
                                <button type="button"
                                    class="btn btn-light"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reportModal">
                                    <i class="bi bi-plus-lg"></i> New Report
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
                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-question-circle text-danger fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">Lost Items</h6>
                                <h3 class="mb-0"><?php echo $lostCount; ?></h3>
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
                                    <i class="bi bi-hand-thumbs-up text-success fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">Found Items</h6>
                                <h3 class="mb-0"><?php echo $foundCount; ?></h3>
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
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-check-circle text-info fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-1">Claimed Items</h6>
                                <h3 class="mb-0"><?php echo $claimedCount; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <input type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by item name, description, or location..."
                            value="<?php echo htmlspecialchars($searchKeyword ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reports List -->
        <div class="row">
            <?php if (empty($reports)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="lead mt-3">No reports found</p>
                </div>
            <?php else: ?>
                <?php foreach ($reports as $report): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <?php if ($report['image_path']): ?>
                                <img src="../<?php echo htmlspecialchars($report['image_path']); ?>"
                                    class="card-img-top"
                                    alt="Item image"
                                    style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">
                                        <?php echo htmlspecialchars($report['item_name']); ?>
                                    </h5>
                                    <span class="badge bg-<?php echo $report['type'] === 'lost' ? 'danger' : 'success'; ?>">
                                        <?php echo ucfirst($report['type']); ?>
                                    </span>
                                </div>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i>
                                        <?php echo htmlspecialchars($report['location']); ?>
                                    </small>
                                </p>
                                <p class="card-text">
                                    <?php echo htmlspecialchars($report['description']); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">
                                        <?php if ($report['is_anonymous']): ?>
                                            <i class="bi bi-person-fill-lock"></i> Anonymous User
                                        <?php else: ?>
                                            <i class="bi bi-person"></i> <?php echo htmlspecialchars($report['reporter_name']); ?>
                                        <?php endif; ?>
                                    </small>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contactModal<?php echo $report['id']; ?>"
                                        data-contact="<?php echo htmlspecialchars($report['contact_info']); ?>"
                                        data-item="<?php echo htmlspecialchars($report['item_name']); ?>">
                                        <i class="bi bi-chat-dots"></i> Contact
                                    </button>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    <?php echo date('M d, Y', strtotime($report['date_reported'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Modal -->
                    <div class="modal fade" id="contactModal<?php echo $report['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Contact Information</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>For item: <strong><?php echo htmlspecialchars($report['item_name']); ?></strong></p>
                                    <p class="mb-0">Contact Details:</p>
                                    <p class="border rounded p-3 bg-light">
                                        <?php echo nl2br(htmlspecialchars($report['contact_info'])); ?>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- New Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="newReportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Lost & Found Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="/new_project/backend/submit_lost_found.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Report Type</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="type" id="typeLost" value="lost" checked>
                                <label class="btn btn-outline-danger" for="typeLost">Lost Item</label>
                                <input type="radio" class="btn-check" name="type" id="typeFound" value="found">
                                <label class="btn btn-outline-success" for="typeFound">Found Item</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="item_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
                                <option value="">Select category...</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Books">Books</option>
                                <option value="Clothing">Clothing</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Documents">Documents</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Information</label>
                            <input type="text" class="form-control" name="contact_info" required>
                            <div class="form-text">How others can contact you about this item</div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_anonymous" id="is_anonymous">
                                <label class="form-check-label" for="is_anonymous">
                                    Report Anonymously
                                </label>
                                <div class="form-text">
                                    Your name will be hidden from public view if checked
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image (Optional)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="../assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>