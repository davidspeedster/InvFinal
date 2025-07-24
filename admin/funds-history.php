<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
require '../db.php';

// Fetch all funds
$funds = $db->funds->find([], ['sort' => ['created_at' => -1]]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Funds History | InvestHub Admin</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
    <div class="container py-4">
        <nav class="mb-4">
            <a href="pending-funds.php" class="btn btn-outline-secondary me-2">Pending Funds</a>
            <a href="funds-history.php" class="btn btn-primary">Funds History</a>
        </nav>
        <h1 class="mb-4">Funds History</h1>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Investment</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($funds as $fund): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($fund['user_id']) ?></td>
                            <td><?= htmlspecialchars($fund['investment_id'] ?? '-') ?></td>
                            <td>$<?= number_format($fund['amount'] ?? 0, 2) ?></td>
                            <td>
                                <?php
                                if (!empty($fund['created_at']) && $fund['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                                    echo $fund['created_at']->toDateTime()->format('Y-m-d H:i:s');
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $status = $fund['status'] ?? 'pending';
                                $badge = $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning text-dark');
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= ucfirst($status) ?></span>
                            </td>
                            <td>
                                <a href="view-fund.php?id=<?= $fund['_id'] ?>" class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
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