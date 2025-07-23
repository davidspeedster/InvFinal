<?php
require '../db.php'; session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'investor') {
    header("Location: ../auth/login.php"); exit;
}
$investments = $db->investments->find(['status'=>'approved']);
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>Browse Investments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Approved Investments</h2>
  <table class="table">
    <thead><tr><th>Title</th><th>Sector</th><th>Needed</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($investments as $inv): ?>
    <tr>
      <td><?= htmlspecialchars($inv['title']) ?></td>
      <td><?= htmlspecialchars($inv['sector']) ?></td>
      <td><?= htmlspecialchars($inv['funding_needed']) ?></td>
      <td>
        <a href="investment-detail.php?id=<?= $inv['_id'] ?>" class="btn btn-info btn-sm">View</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>
</body></html>