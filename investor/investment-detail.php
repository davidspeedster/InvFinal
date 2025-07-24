<?php
require '../db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'investor') {
  header("Location: ../auth/login.php");
  exit;
}

$inv = $db->investments->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);
if (!$inv) {
  echo "Investment not found.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $amount = (float) $_POST['amount'];
  $requested = (float) $inv['requested_amount'];

  // Calculate total already invested
  $totalInvested = 0;
  $cursor = $db->funds->find(['investment_id' => $inv['_id']]);
  foreach ($cursor as $record) {
    $totalInvested += (float) $record['amount'];
  }

  $remaining = $requested - $totalInvested;

  // Check receipt file
  if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
    $error = "Please upload a valid receipt.";
  } elseif ($amount <= 0) {
    $error = "Investment amount must be greater than 0.";
  } elseif ($amount > $remaining) {
    $error = "You can only invest up to $" . number_format($remaining, 2) . " in this project.";
  } else {
    // Process file upload
    $uploadDir = '../uploads/receipts/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileTmp = $_FILES['receipt']['tmp_name'];
    $fileName = basename($_FILES['receipt']['name']);
    $targetPath = $uploadDir . time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $fileName);

    if (move_uploaded_file($fileTmp, $targetPath)) {
      $db->funds->insertOne([
        'investment_id' => $inv['_id'],
        'user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id']),
        'amount' => $amount,
        'receipt_path' => $targetPath,
        'status' => 'pending',
        'created_at' => new MongoDB\BSON\UTCDateTime()
      ]);
      $msg = "Investment submitted and awaiting admin approval.";
    } else {
      $error = "Failed to upload receipt. Try again.";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($inv['business_name']) ?> - Investment Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../assets/css/bootstrap.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    .card {
      border: none;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
    }

    .logo-box {
      max-width: 120px;
      height: auto;
    }

    .info-label {
      font-weight: 600;
      color: #333;
    }

    .info-value {
      color: #555;
    }
  </style>
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
            <form method="post" action="../auth/logout.php" class="d-inline">
              <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <div class="container mt-5 mb-5">
    <div class="card p-4">
      <div class="row align-items-center">
        <!-- Logo & Title -->
        <div class="col-md-3 text-center mb-3 mb-md-0">
          <?php if (!empty($inv['logo'])): ?>
            <img src="../uploads/<?= htmlspecialchars($inv['logo']) ?>" class="img-fluid logo-box" alt="Business Logo">
          <?php else: ?>
            <img src="../assets/images/no-logo.png" class="img-fluid logo-box" alt="No Logo">
          <?php endif; ?>
        </div>

        <!-- Business Info -->
        <div class="col-md-9">
          <h2 class="mb-3"><?= htmlspecialchars($inv['business_name']) ?></h2>
          <p class="text-muted"><?= nl2br(htmlspecialchars($inv['short_description'])) ?></p>

          <div class="row">
            <div class="col-md-6 mb-2"><span class="info-label">TIN:</span> <span class="info-value"><?= htmlspecialchars($inv['tin_number']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Sector:</span> <span class="info-value"><?= htmlspecialchars($inv['sector']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Founder:</span> <span class="info-value"><?= htmlspecialchars($inv['founder_name']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Birthdate:</span> <span class="info-value"><?= htmlspecialchars($inv['founder_birthdate']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Contact:</span> <span class="info-value"><?= htmlspecialchars($inv['contact']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Requested Funding:</span> <span class="info-value">$<?= number_format((float)$inv['requested_amount'], 2) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Status:</span> <span class="info-value"><?= htmlspecialchars($inv['status'] ?? 'Pending') ?></span></div>
          </div>
        </div>
      </div>

      <hr class="my-4">

      <!-- Description -->
      <div class="mb-4">
        <h4>Business Proposal / Pitch</h4>
        <p><?= nl2br(htmlspecialchars($inv['description'])) ?></p>
      </div>
      <div class="mb-4">
        <h6>In order to invest in this company, please deposit the amount you wish to invest into the following account: 1000*********. Once completed please provide the picture of the reciept in the form of an attachment and include it in your submission.
        </h6>
      </div>
      <!-- Investment Form -->
      <?php if (isset($msg)): ?>
        <div class="alert alert-success"><?= $msg ?></div>
      <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data" class="mt-3">
        <input type="number" step="0.01" name="amount" placeholder="Amount to Invest" class="form-control mb-2" required>

        <label class="form-label">Upload Payment Receipt</label>
        <input type="file" name="receipt" class="form-control mb-3" accept="image/*" required>

        <button type="submit" class="btn btn-success">Invest</button>
      </form>
    </div>
  </div>

  <footer class="dashboard-footer mt-auto py-3 bg-light border-top" style="font-size: 0.98rem;">
    <div class="container text-center text-muted">
      <span>
        &copy; <?= date('Y') ?> <b>InvestHub</b>. All rights reserved.
      </span>

    </div>
  </footer>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>