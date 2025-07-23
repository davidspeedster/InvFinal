<?php
require '../db.php'; session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}

// CREATE
if (isset($_POST['new'])) {
  $db->faq->insertOne([
    'question'=>$_POST['question'],
    'answer'=>$_POST['answer']
  ]);
  header('Location: manage-faq.php'); exit;
}

// DELETE
if (isset($_GET['delete'])) {
  $db->faq->deleteOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['delete'])]);
  header('Location: manage-faq.php'); exit;
}

// UPDATE
if (isset($_POST['edit_id'])) {
  $db->faq->updateOne(
    ['_id'=>new MongoDB\BSON\ObjectId($_POST['edit_id'])],
    ['$set'=>['question'=>$_POST['question'],'answer'=>$_POST['answer']]]
  );
  header('Location: manage-faq.php'); exit;
}

// EDIT FORM DATA
$edit = false;
if (isset($_GET['edit'])) {
  $edit = $db->faq->findOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['edit'])]);
}

$faqs = $db->faq->find([]);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Manage FAQ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
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
    <thead><tr><th>Question</th><th>Action</th></tr></thead>
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
</body></html>