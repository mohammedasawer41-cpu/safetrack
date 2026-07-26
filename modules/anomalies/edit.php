<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Invalid anomaly.');

$stmt = $pdo->prepare("SELECT * FROM anomalies WHERE id=?");
$stmt->execute([$id]);
$anomaly = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anomaly) die('Anomaly not found.');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $severity = trim($_POST['severity'] ?? '');
    $status = trim($_POST['status'] ?? '');
    
    if (empty($title)) {
        $error = 'Title is required.';
    } else {
        try {
            $upd_stmt = $pdo->prepare(
                "UPDATE anomalies SET title=?, description=?, severity=?, status=? WHERE id=?"
            );
            $upd_stmt->execute([
                $title,
                $description,
                $severity,
                $status,
                $id
            ]);
            $message = 'Anomaly updated successfully!';
            $stmt->execute([$id]);
            $anomaly = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $error = 'Error updating anomaly: ' . $e->getMessage();
        }
    }
}
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit Anomaly</h1></div>
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
<div class="card-header"><h3 class="card-title">Anomaly Information</h3></div>
<form method="POST">
<div class="card-body">
<div class="form-group mb-3">
<label>Title</label>
<input type="text" name="title" value="<?= htmlspecialchars($anomaly['title']) ?>" class="form-control" required>
</div>
<div class="form-group mb-3">
<label>Description</label>
<textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($anomaly['description'] ?? '') ?></textarea>
</div>
<div class="form-group mb-3">
<label>Severity</label>
<select name="severity" class="form-control" required>
<option value="Low" <?= $anomaly['severity'] == 'Low' ? 'selected' : '' ?>>Low</option>
<option value="Medium" <?= $anomaly['severity'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
<option value="High" <?= $anomaly['severity'] == 'High' ? 'selected' : '' ?>>High</option>
<option value="Critical" <?= $anomaly['severity'] == 'Critical' ? 'selected' : '' ?>>Critical</option>
</select>
</div>
<div class="form-group mb-3">
<label>Status</label>
<select name="status" class="form-control" required>
<option value="Open" <?= $anomaly['status'] == 'Open' ? 'selected' : '' ?>>Open</option>
<option value="Assigned" <?= $anomaly['status'] == 'Assigned' ? 'selected' : '' ?>>Assigned</option>
<option value="Closed" <?= $anomaly['status'] == 'Closed' ? 'selected' : '' ?>>Closed</option>
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