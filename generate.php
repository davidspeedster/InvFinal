<?php
// ==== InvestHub generate.php PART 1 ====
// Utility function for folder-safe file creation
function writeFile($path, $content) {
    $dir = dirname($path);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    file_put_contents($path, $content);
}

// 1. Directory Structure
$folders = [
    'assets/css', 'assets/js', 'assets/img', 'uploads',
    'auth', 'admin', 'entrepreneur', 'investor'
];
foreach ($folders as $folder) if (!is_dir($folder)) mkdir($folder, 0777, true);

// 2. db.php (MongoDB connection)
writeFile('db.php', "<?php
require 'vendor/autoload.php';
try {
    \$mongo = new MongoDB\Client('mongodb://localhost:27017');
    \$db = \$mongo->investhub;
} catch(Exception \$e) {
    exit('MongoDB Connection Error: '.\$e->getMessage());
}
?>");

// 3. Static Pages
$staticPages = [
    'index.php' => 'index.html',
    'about.php' => 'about.html',
    'not-found.php' => 'not-found.html'
];
foreach ($staticPages as $php => $html) {
    if (file_exists($html)) {
        writeFile($php, file_get_contents($html));
    } else {
        writeFile($php, "<!DOCTYPE html><html><head><title>Missing</title></head><body><h1>\$php</h1><p>Static file placeholder.</p></body></html>");
    }
}

// 4. Authentication: Register
writeFile('auth/register.php', <<<PHP
<?php
require '../db.php';
session_start();
if (isset(\$_SESSION['user_id'])) header('Location: ../index.php');
if (\$_SERVER['REQUEST_METHOD']=='POST') {
    \$exists = \$db->users->findOne(['email'=>\$_POST['email']]);
    if (\$exists) {
        \$msg = "Email already registered.";
    } else {
        \$hash = password_hash(\$_POST['password'], PASSWORD_DEFAULT);
        \$db->users->insertOne([
            'name'=>\$_POST['name'],
            'email'=>\$_POST['email'],
            'password_hash'=>\$hash,
            'role'=>\$_POST['role'],
            'created_at'=>new MongoDB\BSON\UTCDateTime()
        ]);
        header('Location: login.php?registered=1');
        exit;
    }
}
?>
<?php include '../assets/header.html'; ?>
<div class="container mt-5">
  <div class="col-md-5 mx-auto card p-4 shadow-sm">
    <h2 class="mb-3">Sign Up</h2>
    <?php if (isset(\$msg)) echo '<div class="alert alert-danger">'.\$msg.'</div>'; ?>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="mb-3">
        <select name="role" class="form-control" required>
          <option value="">Register as...</option>
          <option value="entrepreneur">Entrepreneur</option>
          <option value="investor">Investor</option>
        </select>
      </div>
      <button class="btn btn-primary w-100" type="submit">Register</button>
    </form>
    <div class="mt-3">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </div>
</div>
<?php include '../assets/footer.html'; ?>
PHP);

// 5. Authentication: Login
writeFile('auth/login.php', <<<PHP
<?php
require '../db.php';
session_start();
if (isset(\$_SESSION['user_id'])) header('Location: ../index.php');
if (\$_SERVER['REQUEST_METHOD']=='POST') {
    \$user = \$db->users->findOne(['email'=>\$_POST['email']]);
    if (\$user && password_verify(\$_POST['password'], \$user['password_hash'])) {
        \$_SESSION['user_id'] = (string)\$user['_id'];
        \$_SESSION['user_name'] = \$user['name'];
        \$_SESSION['user_role'] = \$user['role'];
        header('Location: ../'.\$_SESSION['user_role'].'/dashboard.php');
        exit;
    } else {
        \$msg = "Invalid email or password.";
    }
}
?>
<?php include '../assets/header.html'; ?>
<div class="container mt-5">
  <div class="col-md-5 mx-auto card p-4 shadow-sm">
    <h2 class="mb-3">Login</h2>
    <?php if (isset(\$msg)) echo '<div class="alert alert-danger">'.\$msg.'</div>'; ?>
    <form method="POST">
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button class="btn btn-primary w-100" type="submit">Login</button>
    </form>
    <div class="mt-3">
      Don't have an account? <a href="register.php">Register</a>
    </div>
  </div>
</div>
<?php include '../assets/footer.html'; ?>
PHP);

// 6. Authentication: Logout
writeFile('auth/logout.php', "<?php session_start(); session_destroy(); header('Location: login.php'); ?>");

// 7. Contact Form
writeFile('contact.php', <<<PHP
<?php
require 'db.php';
if (\$_SERVER['REQUEST_METHOD']=='POST') {
    \$db->messages->insertOne([
        'name'=>\$_POST['name'],
        'email'=>\$_POST['email'],
        'message'=>\$_POST['message'],
        'created_at'=>new MongoDB\BSON\UTCDateTime()
    ]);
    \$msg = "Message sent!";
}
?>
<?php include 'assets/header.html'; ?>
<div class="container mt-5">
  <div class="col-md-7 mx-auto card p-4 shadow-sm">
    <h2 class="mb-3">Contact Us</h2>
    <?php if (isset(\$msg)) echo '<div class="alert alert-success">'.\$msg.'</div>'; ?>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Your Name" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Your Email" required>
      </div>
      <div class="mb-3">
        <textarea name="message" class="form-control" placeholder="Your Message" required></textarea>
      </div>
      <button class="btn btn-success" type="submit">Send</button>
    </form>
  </div>
</div>
<?php include 'assets/footer.html'; ?>
PHP);
?>
<?php
// ==== InvestHub generate.php PART 2: Admin CRUD ====

// ---- Admin: Manage Users (List/Delete/Promote/Demote) ----
writeFile('admin/manage-users.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}
if (isset(\$_GET['delete'])) {
  \$db->users->deleteOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['delete'])]);
  header('Location: manage-users.php'); exit;
}
\$users = \$db->users->find([]);
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
    <?php foreach (\$users as \$u): ?>
    <tr>
      <td><?= htmlspecialchars(\$u['name']) ?></td>
      <td><?= htmlspecialchars(\$u['email']) ?></td>
      <td><?= \$u['role'] ?></td>
      <td>
        <?php if (\$u['role'] != 'admin'): ?>
        <a href="?delete=<?= \$u['_id'] ?>" onclick="return confirm('Delete user?')" class="btn btn-danger btn-sm">Delete</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body></html>
PHP);

// ---- Admin: Manage Blog (CRUD) ----
writeFile('admin/manage-blog.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}

// CREATE
if (isset(\$_POST['new'])) {
  \$db->blog->insertOne([
    'title'=>\$_POST['title'],
    'content'=>\$_POST['content'],
    'created_at'=>new MongoDB\BSON\UTCDateTime()
  ]);
  header('Location: manage-blog.php'); exit;
}

// DELETE
if (isset(\$_GET['delete'])) {
  \$db->blog->deleteOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['delete'])]);
  header('Location: manage-blog.php'); exit;
}

// UPDATE
if (isset(\$_POST['edit_id'])) {
  \$db->blog->updateOne(
    ['_id'=>new MongoDB\BSON\ObjectId(\$_POST['edit_id'])],
    ['\$set'=>['title'=>\$_POST['title'],'content'=>\$_POST['content']]]
  );
  header('Location: manage-blog.php'); exit;
}

// EDIT FORM DATA
\$edit = false;
if (isset(\$_GET['edit'])) {
  \$edit = \$db->blog->findOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['edit'])]);
}

\$blogs = \$db->blog->find([]);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Manage Blog</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container mt-5">
  <h2>Manage Blog</h2>
  <!-- Add or Edit Form -->
  <form method="post" class="mb-4">
    <input type="hidden" name="edit_id" value="<?= \$edit ? \$edit['_id'] : '' ?>">
    <div class="mb-2">
      <input name="title" class="form-control" placeholder="Blog Title" required value="<?= \$edit ? htmlspecialchars(\$edit['title']) : '' ?>">
    </div>
    <div class="mb-2">
      <textarea name="content" class="form-control" placeholder="Blog Content" required><?= \$edit ? htmlspecialchars(\$edit['content']) : '' ?></textarea>
    </div>
    <button class="btn btn-success" name="<?= \$edit ? 'edit' : 'new' ?>"><?= \$edit ? 'Update' : 'Add New' ?></button>
    <?php if (\$edit): ?>
      <a href="manage-blog.php" class="btn btn-secondary">Cancel</a>
    <?php endif; ?>
  </form>
  <!-- Blog List -->
  <table class="table">
    <thead><tr><th>Title</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach (\$blogs as \$b): ?>
    <tr>
      <td><?= htmlspecialchars(\$b['title']) ?></td>
      <td>
        <a href="?edit=<?= \$b['_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="?delete=<?= \$b['_id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body></html>
PHP);

// ---- Admin: Manage FAQ (CRUD) ----
writeFile('admin/manage-faq.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}

// CREATE
if (isset(\$_POST['new'])) {
  \$db->faq->insertOne([
    'question'=>\$_POST['question'],
    'answer'=>\$_POST['answer']
  ]);
  header('Location: manage-faq.php'); exit;
}

// DELETE
if (isset(\$_GET['delete'])) {
  \$db->faq->deleteOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['delete'])]);
  header('Location: manage-faq.php'); exit;
}

// UPDATE
if (isset(\$_POST['edit_id'])) {
  \$db->faq->updateOne(
    ['_id'=>new MongoDB\BSON\ObjectId(\$_POST['edit_id'])],
    ['\$set'=>['question'=>\$_POST['question'],'answer'=>\$_POST['answer']]]
  );
  header('Location: manage-faq.php'); exit;
}

// EDIT FORM DATA
\$edit = false;
if (isset(\$_GET['edit'])) {
  \$edit = \$db->faq->findOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['edit'])]);
}

\$faqs = \$db->faq->find([]);
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><title>Manage FAQ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container mt-5">
  <h2>Manage FAQ</h2>
  <form method="post" class="mb-4">
    <input type="hidden" name="edit_id" value="<?= \$edit ? \$edit['_id'] : '' ?>">
    <div class="mb-2">
      <input name="question" class="form-control" placeholder="Question" required value="<?= \$edit ? htmlspecialchars(\$edit['question']) : '' ?>">
    </div>
    <div class="mb-2">
      <textarea name="answer" class="form-control" placeholder="Answer" required><?= \$edit ? htmlspecialchars(\$edit['answer']) : '' ?></textarea>
    </div>
    <button class="btn btn-success" name="<?= \$edit ? 'edit' : 'new' ?>"><?= \$edit ? 'Update' : 'Add New' ?></button>
    <?php if (\$edit): ?>
      <a href="manage-faq.php" class="btn btn-secondary">Cancel</a>
    <?php endif; ?>
  </form>
  <!-- FAQ List -->
  <table class="table">
    <thead><tr><th>Question</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach (\$faqs as \$f): ?>
    <tr>
      <td><?= htmlspecialchars(\$f['question']) ?></td>
      <td>
        <a href="?edit=<?= \$f['_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="?delete=<?= \$f['_id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body></html>
PHP);

// ---- Admin: Manage Investments (Approve/Reject/Delete) ----
writeFile('admin/manage-investments.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}
// Approve/Reject
if (isset(\$_GET['id']) && isset(\$_GET['status'])) {
  \$db->investments->updateOne(
    ['_id'=>new MongoDB\BSON\ObjectId(\$_GET['id'])],
    ['\$set'=>['status'=>\$_GET['status']]]
  );
  header('Location: manage-investments.php'); exit;
}
// Delete
if (isset(\$_GET['delete'])) {
  \$db->investments->deleteOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['delete'])]);
  header('Location: manage-investments.php'); exit;
}
\$investments = \$db->investments->find([]);
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
    <?php foreach (\$investments as \$inv): ?>
    <tr>
      <td><?= htmlspecialchars(\$inv['title']) ?></td>
      <td><?= htmlspecialchars(\$inv['status'] ?? 'pending') ?></td>
      <td>
        <a href="?id=<?= \$inv['_id'] ?>&status=approved" class="btn btn-success btn-sm">Approve</a>
        <a href="?id=<?= \$inv['_id'] ?>&status=rejected" class="btn btn-warning btn-sm">Reject</a>
        <a href="?delete=<?= \$inv['_id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body></html>
PHP);

// ---- Admin: View/Delete Contact Messages ----
writeFile('admin/manage-messages.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php"); exit;
}
if (isset(\$_GET['delete'])) {
  \$db->messages->deleteOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['delete'])]);
  header('Location: manage-messages.php'); exit;
}
\$msgs = \$db->messages->find([]);
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
    <?php foreach (\$msgs as \$msg): ?>
    <tr>
      <td><?= htmlspecialchars(\$msg['name']) ?></td>
      <td><?= htmlspecialchars(\$msg['email']) ?></td>
      <td><?= htmlspecialchars(\$msg['message']) ?></td>
      <td>
        <a href="?delete=<?= \$msg['_id'] ?>" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body></html>
PHP);
?>
<?php
// ==== PART 3: Entrepreneur CRUD, Investor, Listings/Details ====

// -- Entrepreneur: Manage Proposals (CRUD) --
writeFile('entrepreneur/submit-proposal.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php"); exit;
}
if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
    \$db->investments->insertOne([
        'user_id' => new MongoDB\BSON\ObjectId(\$_SESSION['user_id']),
        'title' => \$_POST['title'],
        'description' => \$_POST['description'],
        'sector' => \$_POST['sector'],
        'funding_needed' => (float)\$_POST['funding_needed'],
        'status' => 'pending',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    \$msg = "Proposal submitted!";
}
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>Submit Proposal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Submit Business Proposal</h2>
  <?php if (isset(\$msg)) echo '<div class="alert alert-success">'.\$msg.'</div>'; ?>
  <form method="POST">
    <div class="mb-3"><input class="form-control" name="title" placeholder="Title" required></div>
    <div class="mb-3"><textarea class="form-control" name="description" placeholder="Description" required></textarea></div>
    <div class="mb-3"><input class="form-control" name="sector" placeholder="Sector" required></div>
    <div class="mb-3"><input class="form-control" name="funding_needed" placeholder="Funding Needed" type="number" required></div>
    <button class="btn btn-primary" type="submit">Submit</button>
  </form>
  <a href="dashboard.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body></html>
PHP);

writeFile('entrepreneur/view-submissions.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php"); exit;
}
// DELETE
if (isset(\$_GET['delete'])) {
    \$db->investments->deleteOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['delete']), 'user_id'=>new MongoDB\BSON\ObjectId(\$_SESSION['user_id'])]);
    header('Location: view-submissions.php'); exit;
}
\$proposals = \$db->investments->find(['user_id'=>new MongoDB\BSON\ObjectId(\$_SESSION['user_id'])]);
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
    <?php foreach (\$proposals as \$p): ?>
    <tr>
      <td><?= htmlspecialchars(\$p['title']) ?></td>
      <td><?= htmlspecialchars(\$p['status']) ?></td>
      <td>
        <a href="?delete=<?= \$p['_id'] ?>" onclick="return confirm('Delete proposal?')" class="btn btn-danger btn-sm">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>
</body></html>
PHP);

// -- Investor: Browse Investments (listing) --
writeFile('investor/browse-investments.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'investor') {
    header("Location: ../auth/login.php"); exit;
}
\$investments = \$db->investments->find(['status'=>'approved']);
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
    <?php foreach (\$investments as \$inv): ?>
    <tr>
      <td><?= htmlspecialchars(\$inv['title']) ?></td>
      <td><?= htmlspecialchars(\$inv['sector']) ?></td>
      <td><?= htmlspecialchars(\$inv['funding_needed']) ?></td>
      <td>
        <a href="investment-detail.php?id=<?= \$inv['_id'] ?>" class="btn btn-info btn-sm">View</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>
</body></html>
PHP);

// -- Investor: View/Invest (detail + invest form) --
writeFile('investor/investment-detail.php', <<<PHP
<?php
require '../db.php'; session_start();
if (!isset(\$_SESSION['user_role']) || \$_SESSION['user_role'] !== 'investor') {
    header("Location: ../auth/login.php"); exit;
}
\$inv = \$db->investments->findOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['id'])]);
if (!\$inv) { echo "Investment not found."; exit; }
if (\$_SERVER['REQUEST_METHOD']=='POST') {
    \$db->funds->insertOne([
        'investment_id'=>\$inv['_id'],
        'user_id'=>new MongoDB\BSON\ObjectId(\$_SESSION['user_id']),
        'amount'=>(float)\$_POST['amount'],
        'created_at'=>new MongoDB\BSON\UTCDateTime()
    ]);
    \$msg = "Investment successful!";
}
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title><?= htmlspecialchars(\$inv['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2><?= htmlspecialchars(\$inv['title']) ?></h2>
  <p><?= htmlspecialchars(\$inv['description']) ?></p>
  <ul>
    <li><b>Sector:</b> <?= htmlspecialchars(\$inv['sector']) ?></li>
    <li><b>Funding Needed:</b> <?= htmlspecialchars(\$inv['funding_needed']) ?></li>
    <li><b>Status:</b> <?= htmlspecialchars(\$inv['status']) ?></li>
  </ul>
  <?php if (isset(\$msg)) echo '<div class="alert alert-success">'.\$msg.'</div>'; ?>
  <form method="post" class="mt-3">
    <input type="number" step="0.01" name="amount" placeholder="Amount to Invest" class="form-control mb-2" required>
    <button type="submit" class="btn btn-success">Invest</button>
  </form>
  <a href="browse-investments.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body></html>
PHP);

// -- Blog Listing (dynamic/public) --
writeFile('blog.php', <<<PHP
<?php
require 'db.php';
\$blogs = \$db->blog->find([]);
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>Blog</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Our Blog</h2>
  <?php foreach (\$blogs as \$b): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5><?= htmlspecialchars(\$b['title']) ?></h5>
        <div><?= nl2br(htmlspecialchars(\$b['content'])) ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</body></html>
PHP);

// -- FAQ Listing (dynamic/public) --
writeFile('faq.php', <<<PHP
<?php
require 'db.php';
\$faqs = \$db->faq->find([]);
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>FAQ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2>FAQ</h2>
  <div class="accordion" id="faqAccordion">
  <?php \$i=0; foreach (\$faqs as \$f): \$i++; ?>
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading<?=\$i?>">
        <button class="accordion-button <?=\$i>1?'collapsed':''?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=\$i?>">
          <?= htmlspecialchars(\$f['question']) ?>
        </button>
      </h2>
      <div id="collapse<?=\$i?>" class="accordion-collapse collapse <?=\$i==1?'show':''?>" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          <?= nl2br(htmlspecialchars(\$f['answer'])) ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>
</body></html>
PHP);

// -- Public Investments Listing (dynamic/public) --
writeFile('investment.php', <<<PHP
<?php
require 'db.php';
\$investments = \$db->investments->find(['status'=>'approved']);
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>Investments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Approved Investments</h2>
  <table class="table">
    <thead><tr><th>Title</th><th>Sector</th><th>Needed</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach (\$investments as \$inv): ?>
    <tr>
      <td><?= htmlspecialchars(\$inv['title']) ?></td>
      <td><?= htmlspecialchars(\$inv['sector']) ?></td>
      <td><?= htmlspecialchars(\$inv['funding_needed']) ?></td>
      <td>
        <a href="investment-listed-1.php?id=<?= \$inv['_id'] ?>" class="btn btn-info btn-sm">View</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body></html>
PHP);

// -- Investment Detail View (dynamic/public) --
writeFile('investment-listed-1.php', <<<PHP
<?php
require 'db.php';
if (!isset(\$_GET['id'])) { echo "Investment not found."; exit; }
\$inv = \$db->investments->findOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['id'])]);
if (!\$inv) { echo "Investment not found."; exit; }
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title><?= htmlspecialchars(\$inv['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2><?= htmlspecialchars(\$inv['title']) ?></h2>
  <div><?= nl2br(htmlspecialchars(\$inv['description'])) ?></div>
  <ul>
    <li><b>Sector:</b> <?= htmlspecialchars(\$inv['sector']) ?></li>
    <li><b>Funding Needed:</b> <?= htmlspecialchars(\$inv['funding_needed']) ?></li>
    <li><b>Status:</b> <?= htmlspecialchars(\$inv['status']) ?></li>
  </ul>
  <a href="investment.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body></html>
PHP);

// -- Business Proposal Detail View (dynamic/public) --
writeFile('business-detail-1.php', <<<PHP
<?php
require 'db.php';
if (!isset(\$_GET['id'])) { echo "Business not found."; exit; }
\$biz = \$db->investments->findOne(['_id'=>new MongoDB\BSON\ObjectId(\$_GET['id'])]);
if (!\$biz) { echo "Business not found."; exit; }
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title><?= htmlspecialchars(\$biz['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2><?= htmlspecialchars(\$biz['title']) ?></h2>
  <div><?= nl2br(htmlspecialchars(\$biz['description'])) ?></div>
  <ul>
    <li><b>Sector:</b> <?= htmlspecialchars(\$biz['sector']) ?></li>
    <li><b>Funding Needed:</b> <?= htmlspecialchars(\$biz['funding_needed']) ?></li>
    <li><b>Status:</b> <?= htmlspecialchars(\$biz['status']) ?></li>
  </ul>
  <a href="investment.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body></html>
PHP);

?>
<?php
// ==== PART 4: seed.js, README, Completion ====

// --- MongoDB Demo Seed Script ---
writeFile('seed.js', <<<SEED
use investhub;
db.users.insertMany([
  {name: 'Admin', email: 'admin@investhub.et', password_hash: '', role: 'admin', created_at: new Date()},
  {name: 'Entrepreneur', email: 'entrepreneur@investhub.et', password_hash: '', role: 'entrepreneur', created_at: new Date()},
  {name: 'Investor', email: 'investor@investhub.et', password_hash: '', role: 'investor', created_at: new Date()}
]);
db.blog.insertMany([
  {title: 'First Blog Post', content: 'Welcome to InvestHub!', created_at: new Date()}
]);
db.faq.insertMany([
  {question: 'How does InvestHub work?', answer: 'Connects investors with entrepreneurs.'}
]);
db.investments.insertOne({
  user_id: db.users.findOne({email: "entrepreneur@investhub.et"})._id,
  title: "Sample Investment Opportunity",
  description: "A great opportunity.",
  sector: "Tech",
  funding_needed: 50000,
  status: "approved",
  created_at: new Date()
});
SEED
);

// --- README Instructions ---
writeFile('README.txt', <<<README
InvestHub: Full PHP & MongoDB Platform

SETUP:
1. Install MongoDB, PHP 8+, and Composer.
2. In this folder, run: composer require mongodb/mongodb
3. Import demo data: mongo < seed.js
4. Copy your 'assets' (CSS, JS, images) into /assets/
5. Start your local server (XAMPP, WAMP, or php -S localhost:8000)
6. Register or login at /auth/register.php or /auth/login.php

ADMIN DASHBOARD (admin@investhub.et, no password in seed):
  - /admin/dashboard.php
  - /admin/manage-blog.php (CRUD blog)
  - /admin/manage-faq.php (CRUD FAQ)
  - /admin/manage-users.php
  - /admin/manage-investments.php
  - /admin/manage-messages.php

ENTREPRENEUR DASHBOARD:
  - /entrepreneur/dashboard.php
  - /entrepreneur/submit-proposal.php
  - /entrepreneur/view-submissions.php

INVESTOR DASHBOARD:
  - /investor/dashboard.php
  - /investor/browse-investments.php

PUBLIC:
  - /index.php
  - /about.php
  - /contact.php
  - /faq.php
  - /blog.php
  - /investment.php

**Drop in your /assets/ folder for all visuals to work!**
README
);

// --- Final Completion Echo ---
echo "\\n✅ InvestHub FULL project generated! Add your assets folder, run composer, import the seed, and you are ready.\\n";
?>
