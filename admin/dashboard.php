<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
require '../db.php';

// Count stats
$totalUsers = $db->users->countDocuments();
$user = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
$username = $user['username'] ?? '';
$approvedInvestments = $db->investments->countDocuments(['status' => 'approved']);
$pendingInvestments = $db->investments->countDocuments(['status' => 'pending']);
$rejectedInvestments = $db->investments->countDocuments(['status' => 'rejected']);
$totalBlog = $db->blog->countDocuments();
$totalMessages = $db->messages->countDocuments();

$totalFunds = $db->funds->countDocuments();
$pendingFunds = $db->funds->countDocuments(['status' => 'pending']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | InvestHub</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="welcome">Welcome, <?= htmlspecialchars($username) ?></h1>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="dashboard-card bg-primary text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-people icon-lg me-3"></i>
                        <div>
                            <h2><?= $totalUsers ?></h2>
                            <div>Users</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-success text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle icon-lg me-3"></i>
                        <div>
                            <h2><?= $approvedInvestments ?></h2>
                            <div>Approved Investments</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-warning text-dark p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-hourglass-split icon-lg me-3"></i>
                        <div>
                            <h2><?= $pendingInvestments ?></h2>
                            <div>Pending Investments</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-danger text-dark p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-x-circle-fill icon-lg me-3"></i>
                        <div>
                            <h2><?= $rejectedInvestments ?></h2>
                            <div>Rejected Investments</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-primary text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cash-coin icon-lg me-3"></i>
                        <div>
                            <h2><?= $totalFunds ?></h2>
                            <div>Total Funds</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-warning text-dark p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-hourglass-split icon-lg me-3"></i>
                        <div>
                            <h2><?= $pendingFunds ?></h2>
                            <div>Pending Funds</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-info text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-journal-text icon-lg me-3"></i>
                        <div>
                            <h2><?= $totalBlog ?></h2>
                            <div>Blog Posts</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-primary text-dark p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope icon-lg me-3"></i>
                        <div>
                            <h2><?= $totalMessages ?></h2>
                            <div>Messages</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3">Quick Actions</h3>
        <div class="row g-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="manage-users.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-person-gear icon-lg"></i><br>
                        <span>Manage Users</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="manage-blog.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-journal-text icon-lg"></i><br>
                        <span>Manage Blog</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="manage-faq.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-question-circle icon-lg"></i><br>
                        <span>Manage FAQ</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="manage-investments.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-hourglass-split icon-lg"></i><br>
                        <span>Pending Investments</span>
                    </a>
                </div>
                <div class="col-md-3 mt-3">
                    <a href="approved-investments.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-check-circle icon-lg"></i><br>
                        <span>Approved Investments</span>
                    </a>
                </div>
                <div class="col-md-3 mt-3">
                    <a href="rejected-investments.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-x-circle-fill icon-lg"></i><br>
                        <span>Rejected Investments</span>
                    </a>
                </div>
                <div class="col-md-3 mt-3">
                    <a href="pending-funds.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-hourglass-split icon-lg"></i><br>
                        <span>Pending Funds</span>
                    </a>
                </div>
                <div class="col-md-3 mt-3">
                    <a href="funds-history.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-clock-history icon-lg"></i><br>
                        <span>Funds History</span>
                    </a>
                </div>
                <div class="col-md-3 mt-3">
                    <a href="manage-messages.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                        <i class="bi bi-envelope icon-lg"></i><br>
                        <span>Contact Messages</span>
                    </a>
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