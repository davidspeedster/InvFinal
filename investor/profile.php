<?php
require '../db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'investor') {
    header('Location: ../login.php');
    exit;
}
$user = $db->users->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $newpw = $_POST['new_password'];
    $confpw = $_POST['confirm_password'];
    if ($newpw && $newpw === $confpw && strlen($newpw) >= 6) {
        $db->users->updateOne(
            ['_id' => $user['_id']],
            ['$set' => ['password' => password_hash($newpw, PASSWORD_DEFAULT)]]
        );
        $success = "Password updated successfully.";
    } else {
        $error = "Passwords must match and be at least 6 characters.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Profile | InvestHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom styles (optional) -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="../assets/images/favicon.png" type="image/x-icon">
    <style>
        body {
            background: #f6f7fb;
        }

        .dashboard-header {
            background: #fff;
            box-shadow: 0 1px 8px rgba(0, 0, 0, .04);
        }

        .dashboard-header .dropdown-toggle::after {
            margin-left: .5em;
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
    <!-- Dashboard Header -->
    <header class="dashboard-header py-2 mb-3 sticky-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a href="dashboard.php" class="d-flex align-items-center text-decoration-none">
                <img src="../assets/images/favicon.png" style="width:32px;height:32px;" class="me-2" alt="InvestHub">
                <span class="fw-bold" style="font-size:1.3rem;color:#32429a;">InvestHub</span>
            </a>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="investments-history.php">My Investments</a></li>
                    <li class="nav-item"><a class="nav-link active" href="profile.php">Profile</a></li>
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
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Profile</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php elseif ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <dl class="row mb-3">
                            <dt class="col-sm-4">Username:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($user['username']) ?></dd>
                            <dt class="col-sm-4">Email:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($user['email']) ?></dd>
                            <dt class="col-sm-4">Role:</dt>
                            <dd class="col-sm-8"><?= ucfirst($user['role']) ?></dd>
                        </dl>
                        <hr>
                        <h6>Change Password</h6>
                        <form method="post" class="mb-0">
                            <div class="mb-2">
                                <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                            </div>
                            <div class="mb-2">
                                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm">Change Password</button>
                        </form>
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