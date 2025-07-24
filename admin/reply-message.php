<?php
require '../db.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "No message selected.";
    exit;
}

$msg = $db->messages->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);
if (!$msg) {
    echo "Message not found.";
    exit;
}

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply = trim($_POST['reply'] ?? '');
    if ($reply) {
        // Send email
        $to = $msg['email'];
        $subject = "Reply from InvestHub";
        $headers = "From: no-reply@yourdomain.com\r\nContent-Type: text/plain; charset=UTF-8";
        if (mail($to, $subject, $reply, $headers)) {
            $sent = true;
        } else {
            $error = "Failed to send email. Please check your server mail configuration.";
        }
    } else {
        $error = "Reply message cannot be empty.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reply to Message</title>
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
        <h2>Reply to <?= htmlspecialchars($msg['name']) ?></h2>
        <p><b>Email:</b> <?= htmlspecialchars($msg['email']) ?></p>
        <p><b>Original Message:</b><br><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
        <?php if ($sent): ?>
            <div class="alert alert-success">Reply sent successfully!</div>
            <a href="manage-messages.php" class="btn btn-secondary">Back to Messages</a>
        <?php else: ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="reply" class="form-label">Your Reply</label>
                    <textarea name="reply" id="reply" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Reply</button>
                <a href="manage-messages.php" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        <?php endif; ?>
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