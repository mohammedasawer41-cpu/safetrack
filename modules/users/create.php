<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$roles = $pdo->query("SELECT * FROM roles");
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>New User</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">
<div class="card">
<div class="card-header"><h3 class="card-title">Create New User</h3></div>
<form method="POST" action="store.php">
<div class="card-body">
<div class="form-group mb-3"><label>Full Name</label><input type="text" name="fullname" class="form-control" required></div>
<div class="form-group mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
<div class="form-group mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
<div class="form-group mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
<div class="form-group mb-3"><label>Role</label><select name="role_id" class="form-control" required><option value="">Select Role</option><?php while($row = $roles->fetch(PDO::FETCH_ASSOC)): ?><option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['role_name']) ?></option><?php endwhile; ?></select></div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create User</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>
</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>