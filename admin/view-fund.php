<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
require '../db.php';

if (!isset($_GET['id'])) {
    echo "No fund selected.";
    exit;
}

$fund = $db->funds->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);
if (!$fund) {
    echo "Fund not found.";
    exit;
}

// Fetch user and investment details
$user = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($fund['user_id'])]);
$investment = isset($fund['investment_id']) ? $db->investments->findOne(['_id' => new MongoDB\BSON\ObjectId($fund['investment_id'])]) : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Fund Details | InvestHub Admin</title>
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
    <div class="container py-5">
        <a href="javascript:history.back()" class="btn btn-outline-secondary mb-4"><i class="bi bi-arrow-left"></i> Back</a>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-cash-coin" style="font-size:3rem;color:#32429a;"></i>
                            <h2 class="fw-bold mt-2 mb-1">Fund Details</h2>
                            <span class="badge bg-<?=
                                                    ($fund['status'] ?? 'pending') === 'approved' ? 'success' : (($fund['status'] ?? 'pending') === 'rejected' ? 'danger' : 'warning text-dark') ?>">
                                <?= ucfirst($fund['status'] ?? 'pending') ?>
                            </span>
                        </div>
                        <table class="table table-borderless mb-4">
                            <tr>
                                <th class="w-40 text-end">User:</th>
                                <td>
                                    <?= $user ? htmlspecialchars($user['username'] ?? $user['email']) : htmlspecialchars($fund['user_id']) ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end">Investment:</th>
                                <td>
                                    <?php if ($investment): ?>
                                        <a href="view-investment.php?id=<?= $investment['_id'] ?>">
                                            <?= htmlspecialchars($investment['business_name'] ?? $investment['_id']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= htmlspecialchars($fund['investment_id'] ?? '-') ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end">Amount:</th>
                                <td class="fw-bold text-primary">$<?= number_format($fund['amount'] ?? 0, 2) ?></td>
                            </tr>
                            <tr>
                                <th class="text-end">Receipt:</th>
                                <td>
                                    <?php if (!empty($fund['receipt_path'])): ?>
                                        <?php
                                        $ext = strtolower(pathinfo($fund['receipt_path'], PATHINFO_EXTENSION));
                                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                            <img src="<?= htmlspecialchars($fund['receipt_path']) ?>" alt="Receipt" style="max-width:200px;max-height:200px;">
                                            <br>
                                            <a href="<?= htmlspecialchars($fund['receipt_path']) ?>" target="_blank">View Full Receipt</a>
                                        <?php else: ?>
                                            <a href="<?= htmlspecialchars($fund['receipt_path']) ?>" target="_blank">Download Receipt</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No receipt uploaded</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end">Status:</th>
                                <td>
                                    <span class="badge bg-<?=
                                                            ($fund['status'] ?? 'pending') === 'approved' ? 'success' : (($fund['status'] ?? 'pending') === 'rejected' ? 'danger' : 'warning text-dark') ?>">
                                        <?= ucfirst($fund['status'] ?? 'pending') ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end">Date Submitted:</th>
                                <td>
                                    <?php
                                    if (!empty($fund['created_at']) && $fund['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                                        echo $fund['created_at']->toDateTime()->format('Y-m-d H:i:s');
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <!-- Add more fields as needed -->
                        </table>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="pending-funds.php" class="btn btn-secondary px-4">Back to Pending Funds</a>
                            <a href="funds-history.php" class="btn btn-outline-primary px-4">Funds History</a>
                        </div>
                    </div>
                </div>
            </div>
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