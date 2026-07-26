<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$stmt = $pdo->query("SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON r.id=u.role_id ORDER BY u.fullname");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1><i class="fas fa-users"></i> Users</h1></div>
<div class="col-sm-6 text-end"><a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> New User</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">All Users</h3></div>
<div class="card-body table-responsive">
<table class="table table-bordered table-hover">
<thead class="table-light">
<tr><th>Full Name</th><th>Email</th><th>Username</th><th>Role</th><th>Status</th><th>Actions</th></tr>
</thead>
<tbody>
<?php if($stmt->rowCount() > 0): ?>
<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
<td><?= htmlspecialchars($row['fullname']) ?></td>
<td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
<td><?= htmlspecialchars($row['username']) ?></td>
<td><span class="badge bg-primary"><?= htmlspecialchars($row['role_name']) ?></span></td>
<td><span class="badge bg-<?= $row['status'] == 'Active' ? 'success' : 'danger' ?>"><?= htmlspecialchars($row['status']) ?></span></td>
<td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6" class="text-center">No users found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>