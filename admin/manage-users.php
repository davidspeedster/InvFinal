<?php
require '../db.php'; session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}
if (isset($_GET['delete'])) {
  $db->users->deleteOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['delete'])]);
  header('Location: manage-users.php'); exit;
}
$users = $db->users->find([]);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container mt-5">
  <h2>All Users</h2>
  <table class="table">
    <thead><tr>
      <th>Name</th><th>Email</th><th>Role</th><th>Action</th>
    </tr></thead><tbody>
    <?php foreach ($users as $u): ?>
    <tr>
      <td><?= htmlspecialchars($u['name']) ?></td>
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
</body></html>