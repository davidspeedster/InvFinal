<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
require '../db.php';

// Count stats
$totalUsers = $db->users->countDocuments();
$totalInvestments = $db->investments->countDocuments();
$totalBlog = $db->blog->countDocuments();
$totalMessages = $db->messages->countDocuments();
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

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="welcome">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
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
                        <i class="bi bi-briefcase icon-lg me-3"></i>
                        <div>
                            <h2><?= $totalInvestments ?></h2>
                            <div>Investments</div>
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
                <div class="dashboard-card bg-warning text-dark p-4">
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
                    <i class="bi bi-briefcase icon-lg"></i><br>
                    <span>Manage Investments</span>
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
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>