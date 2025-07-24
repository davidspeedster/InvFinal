<?php
require '../db.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}
if (isset($_GET['delete'])) {
  $db->users->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete'])]);
  header('Location: manage-users.php');
  exit;
}
$users = $db->users->find([]);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
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
    <h2>All Users</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td>
              <?php if ($u['role'] != 'admin'): ?>
                <a href="?delete=<?= $u['_id'] ?>" onclick="return confirm('Delete user?')" class="btn btn-danger btn-sm">Delete</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
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