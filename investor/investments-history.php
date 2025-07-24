<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'investor') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all investments made by this investor
$history = $db->funds->find(
    ['user_id' => new MongoDB\BSON\ObjectId($user_id)],
    ['sort' => ['date' => -1]]
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Investment History | InvestHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <link href="../assets/css/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #f6f7fb;
        }

        .dashboard-header {
            background: #fff;
            box-shadow: 0 1px 8px rgba(0, 0, 0, .04);
        }

        .card {
            border-radius: 16px;
        }

        .btn-light {
            border-radius: 22px;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Header (reuse from profile page for consistency) -->
    <header class="dashboard-header py-2 mb-3 sticky-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a href="dashboard.php" class="d-flex align-items-center text-decoration-none">
                <img src="../assets/images/favicon.png" style="width:32px;height:32px;" class="me-2" alt="InvestHub">
                <span class="fw-bold" style="font-size:1.3rem;color:#32429a;">InvestHub</span>
            </a>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="investments-history.php">My Investments</a></li>
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
                        <form method="post" action="logout.php" class="d-inline">
                            <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container my-4">
        <a href="dashboard.php" class="btn btn-light mb-3"><i class="bi bi-arrow-left"></i> Back</a>
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Your Investments</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Project</th>
                                <th>Sector</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($history as $record):
                                $investment = $db->investments->findOne(['_id' => $record['investment_id']]);
                                $fund = $db->funds->findOne([
                                    '_id' => $record['_id'], 
                                    'user_id' => new MongoDB\BSON\ObjectId($user_id)
                                ]);
                                if (!$investment) continue;
                            ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <a href="investment-detail.php?id=<?= $investment['_id'] ?>">
                                            <?= htmlspecialchars($investment['business_name']) ?>
                                        </a>
                                    </td>
                                    <td><?= ucfirst(htmlspecialchars($investment['sector'])) ?></td>
                                    <td><?= isset($record['created_at']) ? date('d M Y', $record['created_at']->toDateTime()->getTimestamp()) : '-' ?></td>
                                    <td>$<?= number_format($record['amount'], 2) ?></td>
                                    <td>
            
                                            <?= htmlspecialchars($fund['status']) ?>
                                    
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if ($i == 1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No investments found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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