<?php
require '../db.php'; session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php"); exit;
}
// DELETE
if (isset($_GET['delete'])) {
    $db->investments->deleteOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['delete']), 'user_id'=>new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
    header('Location: view-submissions.php'); exit;
}
$proposals = $db->investments->find(['user_id'=>new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>My Submissions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2>My Business Proposals</h2>
  <table class="table">
    <thead><tr><th>Title</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($proposals as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['title']) ?></td>
      <td><?= htmlspecialchars($p['status']) ?></td>
      <td>
        <a href="?delete=<?= $p['_id'] ?>" onclick="return confirm('Delete proposal?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>
</body></html>