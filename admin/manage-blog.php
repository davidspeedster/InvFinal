<?php
require '../db.php'; session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}

// CREATE
if (isset($_POST['new'])) {
  $db->blog->insertOne([
    'title'=>$_POST['title'],
    'content'=>$_POST['content'],
    'created_at'=>new MongoDB\BSON\UTCDateTime()
  ]);
  header('Location: manage-blog.php'); exit;
}

// DELETE
if (isset($_GET['delete'])) {
  $db->blog->deleteOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['delete'])]);
  header('Location: manage-blog.php'); exit;
}

// UPDATE
if (isset($_POST['edit_id'])) {
  $db->blog->updateOne(
    ['_id'=>new MongoDB\BSON\ObjectId($_POST['edit_id'])],
    ['$set'=>['title'=>$_POST['title'],'content'=>$_POST['content']]]
  );
  header('Location: manage-blog.php'); exit;
}

// EDIT FORM DATA
$edit = false;
if (isset($_GET['edit'])) {
  $edit = $db->blog->findOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['edit'])]);
}

$blogs = $db->blog->find([]);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Manage Blog</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container mt-5">
  <h2>Manage Blog</h2>
  <!-- Add or Edit Form -->
  <form method="post" class="mb-4">
    <input type="hidden" name="edit_id" value="<?= $edit ? $edit['_id'] : '' ?>">
    <div class="mb-2">
      <input name="title" class="form-control" placeholder="Blog Title" required value="<?= $edit ? htmlspecialchars($edit['title']) : '' ?>">
    </div>
    <div class="mb-2">
      <textarea name="content" class="form-control" placeholder="Blog Content" required><?= $edit ? htmlspecialchars($edit['content']) : '' ?></textarea>
    </div>
    <button class="btn btn-success" name="<?= $edit ? 'edit' : 'new' ?>"><?= $edit ? 'Update' : 'Add New' ?></button>
    <?php if ($edit): ?>
      <a href="manage-blog.php" class="btn btn-secondary">Cancel</a>
    <?php endif; ?>
  </form>
  <!-- Blog List -->
  <table class="table">
    <thead><tr><th>Title</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($blogs as $b): ?>
    <tr>
      <td><?= htmlspecialchars($b['title']) ?></td>
      <td>
        <a href="?edit=<?= $b['_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="?delete=<?= $b['_id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body></html>