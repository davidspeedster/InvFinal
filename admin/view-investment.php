<?php
require '../db.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
if (!isset($_GET['id'])) {
    echo "No investment selected.";
    exit;
}
$investment = $db->investments->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);
if (!$investment) {
    echo "Investment not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Investment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <header class="dashboard-header py-2 mb-3 sticky-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a href="dashboard.php" class="d-flex align-items-center text-decoration-none">
                <img src="../assets/images/favicon.png" style="width:32px;height:32px;" class="me-2" alt="InvestHub">
                <span class="fw-bold" style="font-size:1.3rem;color:#32429a;">InvestHub</span>
            </a>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                </ul>
            </nav>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                    id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../assets/images/resource/user.png" alt="profile" class="rounded-circle" style="width:34px;height:34px;">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Manage Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="post" action="../auth/logout.php" class="d-inline">
                            <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <?php if (!empty($investment['logo'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($investment['logo']) ?>" alt="Logo" class="rounded mb-3" style="max-width:120px;max-height:120px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                            <?php else: ?>
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width:120px;height:120px;">
                                    <span class="text-muted">No Logo</span>
                                </div>
                            <?php endif; ?>
                            <h2 class="fw-bold mt-3 mb-1"><?= htmlspecialchars($investment['business_name']) ?></h2>
                            <span class="badge bg-<?= ($investment['status'] ?? 'pending') === 'approved' ? 'success' : (($investment['status'] ?? 'pending') === 'rejected' ? 'danger' : 'warning text-dark') ?>">
                                <?= ucfirst($investment['status'] ?? 'pending') ?>
                            </span>
                        </div>
                        <table class="table table-borderless mb-4">
                            <tr>
                                <th class="w-25 text-end">TIN Number:</th>
                                <td><?= htmlspecialchars($investment['tin_number'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Contact:</th>
                                <td><?= htmlspecialchars($investment['contact'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Founder Name:</th>
                                <td><?= htmlspecialchars($investment['founder_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Founder Birthdate:</th>
                                <td><?= htmlspecialchars($investment['founder_birthdate'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Sector:</th>
                                <td><?= htmlspecialchars($investment['sector'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Short Description:</th>
                                <td><?= nl2br(htmlspecialchars($investment['short_description'] ?? '-')) ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Description:</th>
                                <td><?= nl2br(htmlspecialchars($investment['description'] ?? '-')) ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Requested Amount:</th>
                                <td class="fw-bold text-primary">$<?= number_format($investment['requested_amount'] ?? 0, 2) ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Created At:</th>
                                <td>
                                    <?php
                                    if (!empty($investment['created_at']) && $investment['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                                        echo $investment['created_at']->toDateTime()->format('Y-m-d H:i:s');
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="manage-investments.php?id=<?= $investment['_id'] ?>&status=approved" class="btn btn-success px-4">Approve</a>
                            <a href="manage-investments.php?id=<?= $investment['_id'] ?>&status=rejected" class="btn btn-warning px-4">Reject</a>
                            <a href="manage-investments.php" class="btn btn-secondary px-4">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="dashboard-footer mt-auto py-3 bg-light border-top" style="font-size: 0.98rem;">
        <div class="container text-center text-muted">
            <span>
                &copy; <?= date('Y') ?> <b>InvestHub</b>. All rights reserved.
            </span>

        </div>
    </footer>
</body>

</html>