<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php");
    exit;
}
require '../db.php';

// Stats for this entrepreneur
$myId = new MongoDB\BSON\ObjectId($_SESSION['user_id']);
$user = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
$username = $user['username'] ?? '';
$totalProposals = $db->investments->countDocuments(['user_id' => $myId]);
$approved = $db->investments->countDocuments(['user_id' => $myId, 'status' => 'approved']);
$pending = $db->investments->countDocuments(['user_id' => $myId, 'status' => 'pending']);
$rejected = $db->investments->countDocuments(['user_id' => $myId, 'status' => 'rejected']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Entrepreneur Dashboard | InvestHub</title>
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
            <h1 class="welcome">Hello, <?= htmlspecialchars($username) ?></h1>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="dashboard-card bg-primary text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-briefcase icon-lg me-3"></i>
                        <div>
                            <h2><?= $totalProposals ?></h2>
                            <div>My Proposals</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-success text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle icon-lg me-3"></i>
                        <div>
                            <h2><?= $approved ?></h2>
                            <div>Approved</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-warning text-dark p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock-history icon-lg me-3"></i>
                        <div>
                            <h2><?= $pending ?></h2>
                            <div>Pending</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card bg-danger text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-x-circle icon-lg me-3"></i>
                        <div>
                            <h2><?= $rejected ?></h2>
                            <div>Rejected</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3">Quick Actions</h3>
        <div class="row g-3">
            <div class="col-md-4">
                <a href="submit-proposal.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                    <i class="bi bi-pencil-square icon-lg"></i><br>
                    <span>Submit New Proposal</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="view-submissions.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                    <i class="bi bi-list-ul icon-lg"></i><br>
                    <span>View My Submissions</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="../index.php" class="dashboard-card bg-light text-dark d-block text-center p-4">
                    <i class="bi bi-house icon-lg"></i><br>
                    <span>Back to Site</span>
                </a>
            </div>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>