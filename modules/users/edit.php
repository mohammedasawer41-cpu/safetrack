<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)$_GET['id'];
if(!$id) die("Invalid user.");

$stmt = $pdo->prepare("SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON r.id=u.role_id WHERE u.id=?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user) die("User not found.");

$roles = $pdo->query("SELECT * FROM roles");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit User</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">Edit User</h3></div>
<form method="POST" action="update.php">
<input type="hidden" name="id" value="<?= $id ?>">
<div class="card-body">
<div class="form-group mb-3">
<label>Full Name</label>
<input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required>
</div>
<div class="form-group mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
</div>
<div class="form-group mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
</div>
<div class="form-group mb-3">
<label>Role</label>
<select name="role_id" class="form-control" required>
<option value="">Select Role</option>
<?php while($row = $roles->fetch(PDO::FETCH_ASSOC)): ?>
<option value="<?= $row['id'] ?>" <?= $user['role_id'] == $row['id'] ? 'selected' : '' ?>><?= htmlspecialchars($row['role_name']) ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Status</label>
<select name="status" class="form-control" required>
<option value="Active" <?= $user['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
<option value="Inactive" <?= $user['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
</select>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>