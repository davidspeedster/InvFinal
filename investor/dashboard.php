<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'investor') {
    header("Location: ../auth/login.php");
    exit;
}
require '../db.php';

// Investor's stats
$funds = $db->funds->find(['user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
$user = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
$username = $user['username'] ?? '';
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

<body class="d-flex flex-column min-vh-100">
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

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="welcome">Hi, <?= htmlspecialchars($username) ?></h1>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="dashboard-footer mt-auto py-3 bg-light border-top" style="font-size: 0.98rem;">
        <div class="container text-center text-muted">
            <span>
                &copy; <?= date('Y') ?> <b>InvestHub</b>. All rights reserved.
            </span>
            <span class="d-none d-md-inline mx-2">|</span>
            <span class="d-none d-md-inline">
                <a href="../about.html" class="text-muted text-decoration-none me-2">About</a>
                <a href="../faq.html" class="text-muted text-decoration-none me-2">FAQ</a>
                <a href="../contact.php" class="text-muted text-decoration-none">Contact Support</a>
            </span>
        </div>
    </footer>
</body>

</html>