<?php
require '../db.php'; session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}
if (isset($_GET['delete'])) {
  $db->messages->deleteOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['delete'])]);
  header('Location: manage-messages.php'); exit;
}
$msgs = $db->messages->find([]);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Contact Messages</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container mt-5">
  <h2>Contact Messages</h2>
  <table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($msgs as $msg): ?>
    <tr>
      <td><?= htmlspecialchars($msg['name']) ?></td>
      <td><?= htmlspecialchars($msg['email']) ?></td>
      <td><?= htmlspecialchars($msg['message']) ?></td>
      <td>
        <a href="?delete=<?= $msg['_id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body></html>