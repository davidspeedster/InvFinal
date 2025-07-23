<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'investor') {
    header("Location: ../auth/login.php");
    exit;
}
require '../db.php';

// Investor's stats
$funds = $db->funds->find(['user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
$totalInvested = 0;
$myInvestments = [];
foreach ($funds as $f) {
    $totalInvested += $f['amount'];
    $myInvestments[] = $f;
}
$totalCount = count($myInvestments);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Investor Dashboard | InvestHub</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: #f7f8fc;
        }

        .dashboard-card {
            border-radius: 15px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            transition: box-shadow .2s;
        }

        .dashboard-card:hover {
            box-shadow: 0 4px 32px rgba(0, 0, 0, 0.15);
        }

        .icon-lg {
            font-size: 2.3rem;
        }

        .welcome {
            font-family: 'Krona One', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="welcome">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="dashboard-card bg-primary text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cash-coin icon-lg me-3"></i>
                        <div>
                            <h2>$<?= number_format($totalInvested, 2) ?></h2>
                            <div>Total Invested</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card bg-success text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-briefcase icon-lg me-3"></i>
                        <div>
                            <h2><?= $totalCount ?></h2>
                            <div>Investments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3">Quick Actions</h3>
        <div class="row g-3">
            <div class="col-md-4">
                <a href="browse-investments.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                    <i class="bi bi-search icon-lg"></i><br>
                    <span>Browse Investments</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="../index.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                    <i class="bi bi-house icon-lg"></i><br>
                    <span>Back to Site</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="investments-history.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                    <i class="bi bi-clock-history icon-lg"></i><br>
                    <span>My Investment History</span>
                </a>
            </div>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>