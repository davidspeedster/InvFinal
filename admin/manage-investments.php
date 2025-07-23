<?php
require '../db.php'; session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}
// Approve/Reject
if (isset($_GET['id']) && isset($_GET['status'])) {
  $db->investments->updateOne(
    ['_id'=>new MongoDB\BSON\ObjectId($_GET['id'])],
    ['$set'=>['status'=>$_GET['status']]]
  );
  header('Location: manage-investments.php'); exit;
}
// Delete
if (isset($_GET['delete'])) {
  $db->investments->deleteOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['delete'])]);
  header('Location: manage-investments.php'); exit;
}
$investments = $db->investments->find([]);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Manage Investments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container mt-5">
  <h2>Manage Investments</h2>
  <table class="table">
    <thead><tr><th>Title</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($investments as $inv): ?>
    <tr>
      <td><?= htmlspecialchars($inv['title']) ?></td>
      <td><?= htmlspecialchars($inv['status'] ?? 'pending') ?></td>
      <td>
        <a href="?id=<?= $inv['_id'] ?>&status=approved" class="btn btn-success btn-sm">Approve</a>
        <a href="?id=<?= $inv['_id'] ?>&status=rejected" class="btn btn-warning btn-sm">Reject</a>
        <a href="?delete=<?= $inv['_id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body></html>