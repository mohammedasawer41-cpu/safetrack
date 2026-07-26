<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Invalid CAPA action.');

$stmt = $pdo->prepare("SELECT * FROM corrective_actions WHERE id=?");
$stmt->execute([$id]);
$action = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$action) die('Action not found.');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_required = trim($_POST['action_required'] ?? '');
    $assigned_to = (int)($_POST['assigned_to'] ?? 0);
    $target_date = trim($_POST['target_date'] ?? '');
    $completion_date = trim($_POST['completion_date'] ?? '');
    $status = trim($_POST['status'] ?? '');
    
    if (empty($action_required)) {
        $error = 'Action required is required.';
    } else {
        try {
            $upd_stmt = $pdo->prepare(
                "UPDATE corrective_actions SET action_required=?, assigned_to=?, target_date=?, completion_date=?, status=? WHERE id=?"
            );
            $upd_stmt->execute([
                $action_required,
                $assigned_to ?: null,
                $target_date ?: null,
                $completion_date ?: null,
                $status,
                $id
            ]);
            $message = 'Action updated successfully!';
            $stmt->execute([$id]);
            $action = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $error = 'Error updating action: ' . $e->getMessage();
        }
    }
}

$users = $pdo->query("SELECT id, fullname FROM users WHERE status='Active' ORDER BY fullname")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit CAPA Action</h1></div>
<div class="col-sm-6 text-end"><a href="view.php?id=<?= $id ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">

<?php if ($message): ?>
<div class="alert alert-success alert-dismissible fade show">
<i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show">
<i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Action Information</h3></div>
<form method="POST">
<div class="card-body">
<div class="form-group mb-3">
<label>Action Required</label>
<textarea name="action_required" rows="4" class="form-control" required><?= htmlspecialchars($action['action_required']) ?></textarea>
</div>
<div class="form-group mb-3">
<label>Assigned To</label>
<select name="assigned_to" class="form-control">
<option value="">-- Select User --</option>
<?php foreach ($users as $user): $sel = $action['assigned_to'] == $user['id'] ? 'selected' : ''; ?>
<option value="<?= $user['id'] ?>" <?= $sel ?>><?= htmlspecialchars($user['fullname']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Target Date</label>
<input type="date" name="target_date" value="<?= $action['target_date'] ?>" class="form-control">
</div>
<div class="form-group mb-3">
<label>Completion Date</label>
<input type="date" name="completion_date" value="<?= $action['completion_date'] ?>" class="form-control">
</div>
<div class="form-group mb-3">
<label>Status</label>
<select name="status" class="form-control" required>
<option value="Open" <?= $action['status'] == 'Open' ? 'selected' : '' ?>>Open</option>
<option value="In Progress" <?= $action['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
<option value="Completed" <?= $action['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
</select>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
<a href="view.php?id=<?= $id ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>

</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>