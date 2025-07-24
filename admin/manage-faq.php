<?php
require '../db.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// CREATE
if (isset($_POST['new'])) {
  $db->faq->insertOne([
    'question' => $_POST['question'],
    'answer' => $_POST['answer']
  ]);
  header('Location: manage-faq.php');
  exit;
}

// DELETE
if (isset($_GET['delete'])) {
  $db->faq->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete'])]);
  header('Location: manage-faq.php');
  exit;
}

// UPDATE
if (isset($_POST['edit_id'])) {
  $db->faq->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($_POST['edit_id'])],
    ['$set' => ['question' => $_POST['question'], 'answer' => $_POST['answer']]]
  );
  header('Location: manage-faq.php');
  exit;
}

// EDIT FORM DATA
$edit = false;
if (isset($_GET['edit'])) {
  $edit = $db->faq->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['edit'])]);
}

$faqs = $db->faq->find([]);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Manage FAQ</title>
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
    <h2>Manage FAQ</h2>
    <form method="post" class="mb-4">
      <input type="hidden" name="edit_id" value="<?= $edit ? $edit['_id'] : '' ?>">
      <div class="mb-2">
        <input name="question" class="form-control" placeholder="Question" required value="<?= $edit ? htmlspecialchars($edit['question']) : '' ?>">
      </div>
      <div class="mb-2">
        <textarea name="answer" class="form-control" placeholder="Answer" required><?= $edit ? htmlspecialchars($edit['answer']) : '' ?></textarea>
      </div>
      <button class="btn btn-success" name="<?= $edit ? 'edit' : 'new' ?>"><?= $edit ? 'Update' : 'Add New' ?></button>
      <?php if ($edit): ?>
        <a href="manage-faq.php" class="btn btn-secondary">Cancel</a>
      <?php endif; ?>
    </form>
    <!-- FAQ List -->
    <table class="table">
      <thead>
        <tr>
          <th>Question</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($faqs as $f): ?>
          <tr>
            <td><?= htmlspecialchars($f['question']) ?></td>
            <td>
              <a href="?edit=<?= $f['_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="?delete=<?= $f['_id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
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